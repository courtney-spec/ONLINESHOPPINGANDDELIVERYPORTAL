<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $cart = session()->get('cart', []);

        $stats = [
            'total_orders'   => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'cart_items'     => array_sum(array_column($cart, 'quantity')) ?: 0,
            'wishlist'       => 0,
        ];

        // Products with search & category filter
        $query = Product::with('category')->where('status', 'active');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products   = $query->latest()->get();
        $categories = Category::all();

        return view('dashboard.customer', compact('stats', 'user', 'products', 'categories'));
    }
}