<x-app-layout>
    <x-slot name="title">Edit Category</x-slot>

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
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Category</h1>
                        <p class="text-sm text-gray-500 mt-1">Update category information.</p>
                    </div>
                    <a href="{{ route('admin.categories.index') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-100 transition">
                        ← Back
                    </a>
                </div>

                <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon (emoji)</label>
                        <input type="text" id="icon" name="icon" value="{{ old('icon', $category->icon) }}" maxlength="5"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition text-2xl"
                               required>
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit"
                                class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded-xl transition">
                            Update Category
                        </button>
                        <a href="{{ route('admin.categories.index') }}"
                           class="text-gray-600 hover:text-gray-800 font-semibold py-3 px-6 rounded-xl transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
