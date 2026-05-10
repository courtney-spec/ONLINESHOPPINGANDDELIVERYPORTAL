<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_users'    => \App\Models\User::count(),
            'total_orders'   => 0,
            'total_products' => \App\Models\Product::count(),
            'revenue'        => 0,
        ];

        $products   = Product::with('category')->latest()->get();
        $categories = Category::withCount('products')->get();

        return view('dashboard.admin', compact('stats', 'products', 'categories'));
    }
}