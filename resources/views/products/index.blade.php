<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products — StyleHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'DM Sans',sans-serif;}.font-display{font-family:'Syne',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="flex min-h-screen">
@include('partials.pm_sidebar')
<div class="ml-64 flex-1">
    <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
        <h1 class="font-display font-semibold text-gray-900 text-lg">All Products</h1>
        <a href="{{ route('product_manager.products.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">+ Add Product</a>
    </header>
    <main class="p-6">

        {{-- Flash --}}
        @if(session('success'))<div class="mb-5 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">✅ {{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-5 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">❌ {{ session('error') }}</div>@endif

        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('product_manager.products.index') }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Search products..."
                       class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 col-span-1 md:col-span-1">

                <select name="category" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-600">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="status" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-600">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <select name="stock" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 text-gray-600">
                    <option value="">All Stock Levels</option>
                    <option value="in"  {{ request('stock') === 'in'  ? 'selected' : '' }}>In Stock</option>
                    <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Low Stock (≤5)</option>
                    <option value="out" {{ request('stock') === 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">Apply Filters</button>
                <a href="{{ route('product_manager.products.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">Clear</a>
            </div>
        </form>

        {{-- Products Table --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <span class="text-sm text-gray-500">{{ $products->total() }} product(s) found</span>
            </div>
            @if($products->isEmpty())
                <div class="text-center py-16">
                    <div class="text-5xl mb-3">📦</div>
                    <p class="font-medium text-gray-500">No products found</p>
                    <a href="{{ route('product_manager.products.create') }}" class="inline-block mt-4 bg-blue-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl">+ Add Product</a>
                </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Product</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Category</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Price (UGX)</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Stock</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                             class="w-10 h-10 rounded-lg object-cover border border-gray-100">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-xl">📦</div>
                                    @endif
                                    <span class="font-medium text-gray-900">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-gray-500">{{ $product->category->icon }} {{ $product->category->name }}</td>
                            <td class="py-3 px-4 font-semibold text-gray-800">{{ number_format($product->price) }}</td>
                            <td class="py-3 px-4">
                                <span class="font-semibold {{ $product->stock == 0 ? 'text-red-600' : ($product->stock <= 5 ? 'text-amber-600' : 'text-green-600') }}">
                                    {{ $product->stock }}
                                    @if($product->stock == 0) <span class="text-xs font-normal">(Out)</span>
                                    @elseif($product->stock <= 5) <span class="text-xs font-normal">(Low)</span>
                                    @endif
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 text-xs rounded-full font-semibold {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('product_manager.products.show', $product) }}"
                                       class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg font-medium transition-colors">View</a>
                                    <a href="{{ route('product_manager.products.edit', $product) }}"
                                       class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg font-medium transition-colors">Edit</a>
                                    <form method="POST" action="{{ route('product_manager.products.destroy', $product) }}"
                                          onsubmit="return confirm('Delete {{ $product->name }}? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg font-medium transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            @if($products->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $products->links() }}
            </div>
            @endif
            @endif
        </div>
    </main>
</div>
</div>
</body>
</html>
