<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users'    => User::count(),
            'total_orders'   => Order::count(),
            'total_products' => Product::count(),
            'revenue'        => Order::sum('total'),
        ];

        $products   = Product::with('category')->latest()->get();
        $categories = Category::withCount('products')->get();

        return view('dashboard.admin', compact('stats', 'products', 'categories'));
    }
}