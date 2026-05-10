<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ── List all products with search & filter ──
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by stock
        if ($request->filled('stock')) {
            if ($request->stock === 'low') {
                $query->where('stock', '>', 0)->where('stock', '<=', 5);
            } elseif ($request->stock === 'out') {
                $query->where('stock', 0);
            } elseif ($request->stock === 'in') {
                $query->where('stock', '>', 5);
            }
        }

        $products   = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    // ── Show create form ──
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // ── Store new product ──
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'status'      => 'required|in:active,inactive',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('product_manager.products.index')
                         ->with('success', 'Product added successfully!');
    }

    // ── Show single product ──
    public function show(Product $product)
    {
        $product->load('category');
        return view('products.show', compact('product'));
    }

    // ── Show edit form ──
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    // ── Update product ──
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'status'      => 'required|in:active,inactive',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('product_manager.products.index')
                         ->with('success', 'Product updated successfully!');
    }

    // ── Delete product ──
    public function destroy(Product $product)
    {
        // Delete image from storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('product_manager.products.index')
                         ->with('success', 'Product deleted successfully!');
    }
}
