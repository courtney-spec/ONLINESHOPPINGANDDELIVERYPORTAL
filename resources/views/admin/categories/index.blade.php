<x-app-layout>
    <x-slot name="title">Manage Categories</x-slot>

    <x-slot name="sidebar">
        @php
            $links = [
                ['href' => route('admin.dashboard'), 'icon' => '📊', 'label' => 'Dashboard'],
                ['href' => route('admin.users.index'), 'icon' => '👥', 'label' => 'Manage Users'],
                ['href' => '#', 'icon' => '📦', 'label' => 'Products'],
                ['href' => route('admin.orders.index'), 'icon' => '🛒', 'label' => 'Orders'],
                ['href' => route('admin.categories.index'), 'icon' => '📁', 'label' => 'Categories'],
            ];
        @endphp
        @foreach($links as $link)
            <a href="{{ $link['href'] }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-colors {{ request()->url() === $link['href'] ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                <span>{{ $link['icon'] }}</span>
                {{ $link['label'] }}
            </a>
        @endforeach
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manage Categories</h1>
                    <p class="text-sm text-gray-500 mt-1">Create and manage product categories.</p>
                </div>
                <a href="{{ route('admin.categories.create') }}"
                   class="inline-flex items-center gap-2 rounded-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 text-sm font-semibold transition">
                    + New Category
                </a>
            </div>

            @if($categories->isEmpty())
                <div class="text-center py-16">
                    <p class="text-gray-500 text-lg mb-3">No categories yet.</p>
                    <p class="text-gray-400">Create your first category to get started.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($categories as $category)
                        <div class="border border-gray-100 rounded-xl p-5 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="text-3xl">{{ $category->icon }}</div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $category->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $category->products_count }} product(s)</p>
                                    </div>
                                </div>
                            </div>
                            @if($category->description)
                                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($category->description, 100) }}</p>
                            @endif
                            <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="text-blue-600 hover:text-blue-700 font-semibold text-sm">Edit</a>
                                <span class="text-gray-300">|</span>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this category?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 font-semibold text-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
