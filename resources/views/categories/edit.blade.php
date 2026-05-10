<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Category — StyleHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'DM Sans',sans-serif;}.font-display{font-family:'Syne',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="flex min-h-screen">
@include('partials.pm_sidebar')
<div class="ml-64 flex-1">
    <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center gap-3 sticky top-0 z-30">
        <a href="{{ route('product_manager.categories.index') }}" class="text-gray-400 hover:text-gray-700 text-sm">← Back</a>
        <h1 class="font-display font-semibold text-gray-900 text-lg">Edit: {{ $category->name }}</h1>
    </header>
    <main class="p-6 max-w-lg">
        @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">
            @foreach($errors->all() as $error)<p>• {{ $error }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('product_manager.categories.update', $category) }}"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Icon (Emoji)</label>
                <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" maxlength="10"
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="flex gap-3 pt-2 border-t border-gray-100">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    Update Category
                </button>
                <a href="{{ route('product_manager.categories.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2.5 rounded-xl transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </main>
</div>
</div>
</body>
</html>
