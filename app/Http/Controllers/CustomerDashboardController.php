<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $stats = [
            'total_orders'   => 0,
            'pending_orders' => 0,
            'cart_items'     => 0,
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