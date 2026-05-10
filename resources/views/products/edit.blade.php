<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product — StyleHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'DM Sans',sans-serif;}.font-display{font-family:'Syne',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="flex min-h-screen">
@include('partials.pm_sidebar')
<div class="ml-64 flex-1">
    <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center gap-3 sticky top-0 z-30">
        <a href="{{ route('product_manager.products.index') }}" class="text-gray-400 hover:text-gray-700 text-sm">← Back</a>
        <h1 class="font-display font-semibold text-gray-900 text-lg">Edit: {{ $product->name }}</h1>
    </header>
    <main class="p-6 max-w-3xl">

        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">
            <p class="font-semibold mb-1">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('product_manager.products.update', $product) }}" enctype="multipart/form-data"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="active"   {{ old('status', $product->status) === 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Price --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Price (UGX) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0" step="0.01"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- Stock --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Stock Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- Image --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Product Image</label>
                    @if($product->image)
                    <div class="mb-3 flex items-center gap-4">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                             class="w-20 h-20 rounded-xl object-cover border border-gray-200">
                        <p class="text-xs text-gray-400">Current image — upload a new one to replace it</p>
                    </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP — max 2MB</p>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    Update Product
                </button>
                <a href="{{ route('product_manager.products.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </main>
</div>
</div>
</body>
</html>
