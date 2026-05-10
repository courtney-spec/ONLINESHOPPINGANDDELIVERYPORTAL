<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Categories — StyleHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'DM Sans',sans-serif;}.font-display{font-family:'Syne',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="flex min-h-screen">
@include('partials.pm_sidebar')
<div class="ml-64 flex-1">
    <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
        <h1 class="font-display font-semibold text-gray-900 text-lg">Categories</h1>
        <a href="{{ route('product_manager.categories.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">+ Add Category</a>
    </header>
    <main class="p-6">
        @if(session('success'))<div class="mb-5 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">✅ {{ session('success') }}</div>@endif
        @if(session('error'))<div class="mb-5 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">❌ {{ session('error') }}</div>@endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse($categories as $category)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl">
                        {{ $category->icon ?? '📦' }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $category->products_count }} product(s)</p>
                        @if($category->description)
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col gap-2 ml-3 flex-shrink-0">
                    <a href="{{ route('product_manager.categories.edit', $category) }}"
                       class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1.5 rounded-lg font-medium transition-colors text-center">Edit</a>
                    <form method="POST" action="{{ route('product_manager.categories.destroy', $category) }}"
                          onsubmit="return confirm('Delete {{ $category->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1.5 rounded-lg font-medium transition-colors w-full">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-16">
                <div class="text-5xl mb-3">📁</div>
                <p class="font-medium text-gray-500">No categories yet</p>
                <a href="{{ route('product_manager.categories.create') }}" class="inline-block mt-4 bg-blue-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl">+ Add Category</a>
            </div>
            @endforelse
        </div>
    </main>
</div>
</div>
</body>
</html>
