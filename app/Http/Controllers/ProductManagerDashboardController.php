<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;  // ← ADD THIS LINE
use Illuminate\View\View;

class ProductManagerDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_products'   => Product::count(),
            'low_stock'        => Product::where('stock', '>', 0)->where('stock', '<=', 5)->count(),
            'categories'       => Category::count(),
            'out_of_stock'     => Product::where('stock', 0)->count(),
        ];

        $recent_products = Product::with('category')->latest()->take(5)->get();

        return view('dashboard.product_manager', compact('stats', 'recent_products'));
    }
}