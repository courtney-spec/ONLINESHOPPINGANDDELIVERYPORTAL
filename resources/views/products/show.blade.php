<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->name }} — StyleHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'DM Sans',sans-serif;}.font-display{font-family:'Syne',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="flex min-h-screen">
@include('partials.pm_sidebar')
<div class="ml-64 flex-1">
    <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-3">
            <a href="{{ route('product_manager.products.index') }}" class="text-gray-400 hover:text-gray-700 text-sm">← Back</a>
            <h1 class="font-display font-semibold text-gray-900 text-lg">Product Details</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('product_manager.products.edit', $product) }}"
               class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">Edit</a>
            <form method="POST" action="{{ route('product_manager.products.destroy', $product) }}"
                  onsubmit="return confirm('Delete this product?')">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold px-4 py-2 rounded-xl transition-colors">Delete</button>
            </form>
        </div>
    </header>
    <main class="p-6 max-w-3xl">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex gap-6 mb-6">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                         class="w-32 h-32 rounded-2xl object-cover border border-gray-100 flex-shrink-0">
                @else
                    <div class="w-32 h-32 rounded-2xl bg-blue-50 flex items-center justify-center text-5xl flex-shrink-0">📦</div>
                @endif
                <div>
                    <h2 class="font-display text-2xl font-bold text-gray-900 mb-1">{{ $product->name }}</h2>
                    <p class="text-gray-500 text-sm mb-3">{{ $product->category->icon }} {{ $product->category->name }}</p>
                    <span class="px-3 py-1 text-sm rounded-full font-semibold {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($product->status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Price</p>
                    <p class="font-display font-bold text-xl text-gray-900">UGX {{ number_format($product->price) }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Stock</p>
                    <p class="font-display font-bold text-xl {{ $product->stock == 0 ? 'text-red-600' : ($product->stock <= 5 ? 'text-amber-600' : 'text-green-600') }}">
                        {{ $product->stock }} units
                    </p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Added</p>
                    <p class="font-semibold text-gray-900">{{ $product->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            @if($product->description)
            <div>
                <p class="text-sm font-semibold text-gray-700 mb-2">Description</p>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $product->description }}</p>
            </div>
            @endif
        </div>
    </main>
</div>
</div>
</body>
</html>
