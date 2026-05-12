<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class CustomerCheckoutController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.dashboard')->with('error', 'Your cart is empty.');
        }

        $items = collect($cart)
            ->map(function (array $item, $productId) {
                $product = \App\Models\Product::find($productId);

                if (! $product) {
                    return null;
                }

                return (object) [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity'],
                ];
            })
            ->filter()
            ->values();

        $total = $items->sum('subtotal');
        $user = auth()->user();

        return view('checkout.index', compact('items', 'total', 'user'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:cash_on_delivery,card,mobile_money',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.dashboard')->with('error', 'Your cart is empty.');
        }

        // Calculate total and validate stock availability
        $total = 0;
        $items = [];
        foreach ($cart as $productId => $cartItem) {
            $product = Product::find($productId);
            if ($product) {
                // Check if enough stock is available
                if ($product->stock < $cartItem['quantity']) {
                    return redirect()->route('customer.cart.index')->with('error', 'Not enough stock for ' . $product->name . '. Available: ' . $product->stock);
                }

                $subtotal = $product->price * $cartItem['quantity'];
                $total += $subtotal;
                $items[] = [
                    'product_id' => $productId,
                    'quantity' => $cartItem['quantity'],
                    'price' => $product->price,
                ];
            }
        }

        // Create Order record
        $order = Order::create([
            'user_id' => auth()->id(),
            'total' => $total,
            'status' => 'pending',
            'payment_method' => $data['payment_method'],
            'shipping_address' => $data['address'] . ', ' . $data['city'] . ' ' . $data['postal_code'],
            'customer_name' => $data['full_name'],
            'customer_email' => $data['email'],
            'customer_phone' => $data['phone'],
        ]);

        // Create OrderItem records and reduce stock
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Reduce product stock
            $product = Product::find($item['product_id']);
            if ($product) {
                $product->reduceStock($item['quantity']);
            }
        }

        // Clear the cart
        session()->forget('cart');

        return redirect()->route('customer.dashboard')->with('success', 'Order placed successfully! You can view your orders in the Orders section.');
    }
}
