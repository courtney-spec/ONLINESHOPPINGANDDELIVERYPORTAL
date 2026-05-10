<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:10',
        ]);

        Category::create($request->only('name', 'description', 'icon'));

        return redirect()->route('product_manager.categories.index')
                         ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:10',
        ]);

        $category->update($request->only('name', 'description', 'icon'));

        return redirect()->route('product_manager.categories.index')
                         ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Prevent deletion if category has products
        if ($category->products()->count() > 0) {
            return redirect()->route('product_manager.categories.index')
                             ->with('error', 'Cannot delete category — it has products assigned to it.');
        }

        $category->delete();

        return redirect()->route('product_manager.categories.index')
                         ->with('success', 'Category deleted successfully!');
    }
}
