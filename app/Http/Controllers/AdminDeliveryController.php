<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminDeliveryController extends Controller
{
    // ── List all deliveries ──────────────────────
    public function index(Request $request): View
    {
        $query = Delivery::with(['order.user'])->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by tracking number or recipient
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('tracking_number', 'like', '%' . $request->search . '%')
                  ->orWhere('recipient_name', 'like', '%' . $request->search . '%');
            });
        }

        $deliveries = $query->paginate(10)->withQueryString();

        $stats = [
            'total'          => Delivery::count(),
            'pending'        => Delivery::where('status', 'pending')->count(),
            'dispatched'     => Delivery::where('status', 'dispatched')->count(),
            'out_for_delivery' => Delivery::where('status', 'out_for_delivery')->count(),
            'delivered'      => Delivery::where('status', 'delivered')->count(),
            'failed'         => Delivery::where('status', 'failed')->count(),
        ];

        return view('admin.deliveries.index', compact('deliveries', 'stats'));
    }

    // ── Show assign delivery form ────────────────
    public function create(Request $request): View
    {
        // Get orders that don't have a delivery assigned yet
        $orders = Order::with('user')
            ->whereDoesntHave('delivery')
            ->latest()
            ->get();

        $selectedOrder = null;
        if ($request->filled('order_id')) {
            $selectedOrder = Order::with('user')->find($request->order_id);
        }

        return view('admin.deliveries.create', compact('orders', 'selectedOrder'));
    }

    // ── Assign delivery to order ─────────────────
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_id'               => 'required|exists:orders,id|unique:deliveries,order_id',
            'recipient_name'         => 'required|string|max:255',
            'recipient_phone'        => 'nullable|string|max:20',
            'delivery_address'       => 'required|string',
            'courier_name'           => 'nullable|string|max:255',
            'estimated_delivery_date'=> 'nullable|date|after:today',
            'notes'                  => 'nullable|string',
        ]);

        $validated['tracking_number'] = Delivery::generateTrackingNumber();
        $validated['status']          = 'pending';

        Delivery::create($validated);

        // Update order status to processing
        $order = Order::find($validated['order_id']);
        $order->update(['status' => 'processing']);

        return redirect()->route('admin.deliveries.index')
                         ->with('success', 'Delivery assigned successfully! Tracking: ' . $validated['tracking_number']);
    }

    // ── Show delivery detail ─────────────────────
    public function show(Delivery $delivery): View
    {
        $delivery->load('order.user', 'order.items.product');
        return view('admin.deliveries.show', compact('delivery'));
    }

    // ── Show edit/update status form ─────────────
    public function edit(Delivery $delivery): View
    {
        $delivery->load('order.user');
        return view('admin.deliveries.edit', compact('delivery'));
    }

    // ── Update delivery status ───────────────────
    public function update(Request $request, Delivery $delivery): RedirectResponse
    {
        $validated = $request->validate([
            'status'                 => 'required|in:pending,dispatched,out_for_delivery,delivered,failed',
            'courier_name'           => 'nullable|string|max:255',
            'estimated_delivery_date'=> 'nullable|date',
            'notes'                  => 'nullable|string',
            'recipient_phone'        => 'nullable|string|max:20',
        ]);

        // Set timestamps based on status change
        if ($validated['status'] === 'dispatched' && $delivery->status !== 'dispatched') {
            $validated['dispatched_at'] = now();
        }

        if ($validated['status'] === 'delivered' && $delivery->status !== 'delivered') {
            $validated['delivered_at'] = now();
            // Update order status to delivered
            $delivery->order->update(['status' => 'delivered']);
        }

        $delivery->update($validated);

        return redirect()->route('admin.deliveries.show', $delivery)
                         ->with('success', 'Delivery status updated to: ' . ucfirst(str_replace('_', ' ', $validated['status'])));
    }
}
