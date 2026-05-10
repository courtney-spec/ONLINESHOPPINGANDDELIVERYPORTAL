<x-app-layout>
    <x-slot name="title">My Dashboard</x-slot>

    <x-slot name="sidebar">
        @php
            $links = [
                ['href' => route('customer.dashboard'), 'icon' => '🏠', 'label' => 'My Dashboard'],
                ['href' => '#', 'icon' => '🛒', 'label' => 'My Cart'],
                ['href' => '#', 'icon' => '📦', 'label' => 'My Orders'],
                ['href' => '#', 'icon' => '🚚', 'label' => 'Track Delivery'],
                ['href' => '#', 'icon' => '❤️', 'label' => 'Wishlist'],
                ['href' => '#', 'icon' => '👤', 'label' => 'My Profile'],
            ];
        @endphp
        @foreach($links as $link)
            <a href="{{ $link['href'] }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-colors
                      {{ request()->url() === $link['href'] ? 'bg-orange-50 text-orange-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                <span>{{ $link['icon'] }}</span>
                {{ $link['label'] }}
            </a>
        @endforeach
    </x-slot>

    {{-- Welcome --}}
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold text-gray-900">
            Hey, {{ auth()->user()->name }}! 👋
        </h2>
        <p class="text-gray-500 text-sm mt-1">What would you like to shop for today?</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        @php
            $cards = [
                ['label' => 'Total Orders',   'value' => $stats['total_orders'],   'icon' => '📦', 'color' => 'bg-orange-500'],
                ['label' => 'Pending Orders', 'value' => $stats['pending_orders'], 'icon' => '🕐', 'color' => 'bg-amber-500'],
                ['label' => 'Cart Items',     'value' => $stats['cart_items'],     'icon' => '🛒', 'color' => 'bg-blue-500'],
                ['label' => 'Wishlist',       'value' => $stats['wishlist'],       'icon' => '❤️', 'color' => 'bg-rose-500'],
            ];
        @endphp
        @foreach($cards as $card)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 {{ $card['color'] }} rounded-xl flex items-center justify-center text-2xl">
                    {{ $card['icon'] }}
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">{{ $card['label'] }}</p>
                    <p class="text-2xl font-display font-bold text-gray-900">{{ $card['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Shop CTA Banner --}}
    <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl p-6 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-display text-xl font-bold mb-1">Ready to shop?</h3>
                <p class="text-orange-100 text-sm">Browse our latest products below with fast delivery</p>
            </div>
            <span class="text-4xl">🛍️</span>
        </div>
    </div>

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('customer.dashboard') }}"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 Search products..."
                   class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">

            <select name="category"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 text-gray-600">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->icon }} {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                Search
            </button>

            @if(request('search') || request('category'))
                <a href="{{ route('customer.dashboard') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors text-center">
                    Clear
                </a>
            @endif
        </div>
    </form>

    {{-- Products Grid --}}
    <div class="mb-4 flex items-center justify-between">
        <h3 class="font-display font-semibold text-gray-900">
            {{ request('search') || request('category') ? 'Search Results' : 'Available Products' }}
        </h3>
        <span class="text-xs text-gray-400">{{ $products->count() }} product(s)</span>
    </div>

    @if($products->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm text-center py-16">
            <div class="text-5xl mb-3">🔍</div>
            <p class="font-medium text-gray-500">No products found</p>
            <p class="text-sm text-gray-400 mt-1">Try a different search or category</p>
            <a href="{{ route('customer.dashboard') }}"
               class="inline-block mt-4 bg-orange-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl">
                View All Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($products as $product)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow group">

                    {{-- Product Image --}}
                    <div class="h-48 bg-gray-50 flex items-center justify-center overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <span class="text-6xl">📦</span>
                        @endif
                    </div>

                    {{-- Product Info --}}
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <span class="text-xs text-gray-400 font-medium">
                                    {{ $product->category->icon }} {{ $product->category->name }}
                                </span>
                                <h4 class="font-semibold text-gray-900 mt-0.5">{{ $product->name }}</h4>
                            </div>
                            {{-- Stock Badge --}}
                            @if($product->stock == 0)
                                <span class="text-xs bg-red-100 text-red-700 font-semibold px-2 py-1 rounded-full flex-shrink-0">Out</span>
                            @elseif($product->stock <= 5)
                                <span class="text-xs bg-amber-100 text-amber-700 font-semibold px-2 py-1 rounded-full flex-shrink-0">Low</span>
                            @else
                                <span class="text-xs bg-green-100 text-green-700 font-semibold px-2 py-1 rounded-full flex-shrink-0">In Stock</span>
                            @endif
                        </div>

                        @if($product->description)
                            <p class="text-xs text-gray-400 mb-3 line-clamp-2">{{ $product->description }}</p>
                        @endif

                        <div class="flex items-center justify-between mt-3">
                            <p class="font-display font-bold text-lg text-gray-900">
                                UGX {{ number_format($product->price) }}
                            </p>
                            <button
                                class="bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold px-4 py-2 rounded-xl transition-colors
                                       {{ $product->stock == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $product->stock == 0 ? 'disabled' : '' }}>
                                🛒 Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</x-app-layout>
