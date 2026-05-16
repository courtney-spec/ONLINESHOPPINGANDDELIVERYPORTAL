<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class CustomerDeliveryController extends Controller
{
    // Track delivery for a specific order
    public function track(Order $order): View
    {
        // Make sure customer can only track their own orders
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('delivery', 'orderItems.product');

        return view('customer.orders.track', compact('order'));
    }
}
