<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }
}
