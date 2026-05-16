<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>

    <x-slot name="sidebar">
        @php
            $links = [
                ['href' => route('admin.dashboard'), 'icon' => '📊', 'label' => 'Dashboard'],
                ['href' => route('admin.users.index'), 'icon' => '👥', 'label' => 'Manage Users'],
                ['href' => route('admin.dashboard'),           'icon' => '📦', 'label' => 'Products'],
                ['href' => route('admin.orders.index'), 'icon' => '🛒', 'label' => 'Orders'],
                ['href' => route('admin.deliveries.index'), 'icon' => '🚚', 'label' => 'Deliveries'],
                ['href' => route('admin.reports'), 'icon' => '📈', 'label' => 'Reports'],
            ];
        @endphp
        @foreach($links as $link)
            <a href="{{ $link['href'] }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-colors
                      {{ request()->url() === $link['href'] ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                <span>{{ $link['icon'] }}</span>
                {{ $link['label'] }}
            </a>
        @endforeach
    </x-slot>

    {{-- Welcome --}}
    <div class="mb-8">
        <h2 class="font-display text-2xl font-bold text-gray-900">
            Welcome back, {{ auth()->user()->name }} 👋
        </h2>
        <p class="text-gray-500 text-sm mt-1">Here's what's happening in your store today.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
        @php
            $cards = [
                ['label' => 'Total Users',    'value' => $stats['total_users'],           'icon' => '👥', 'color' => 'bg-purple-500'],
                ['label' => 'Total Orders',   'value' => $stats['total_orders'],           'icon' => '🛒', 'color' => 'bg-orange-500'],
                ['label' => 'Total Products', 'value' => $stats['total_products'],         'icon' => '📦', 'color' => 'bg-blue-500'],
                ['label' => 'Revenue (UGX)',  'value' => number_format($stats['revenue']), 'icon' => '💰', 'color' => 'bg-green-500'],
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

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
        <h3 class="font-display font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach([
                ['icon' => '👁️', 'label' => 'View Orders',   'href' => route('admin.orders.index')],
                ['icon' => '👤', 'label' => 'Manage Users',  'href' => route('admin.users.index')],
                ['icon' => '📁', 'label' => 'Categories',    'href' => route('admin.categories.index')],
                ['icon' => '📊', 'label' => 'View Reports',  'href' => route('admin.reports')],
            ] as $action)
                <a href="{{ $action['href'] }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-dashed border-gray-200
                          hover:border-purple-300 hover:bg-purple-50 transition-colors text-center group">
                    <span class="text-3xl">{{ $action['icon'] }}</span>
                    <span class="text-xs font-semibold text-gray-600 group-hover:text-purple-700">{{ $action['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>


    {{-- All Products Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-display font-semibold text-gray-900">All Products</h3>
            <span class="text-xs text-gray-400">{{ $products->count() }} total</span>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-16">
                <div class="text-5xl mb-3">📦</div>
                <p class="font-medium text-gray-500">No products have been added yet</p>
                <p class="text-sm text-gray-400 mt-1">Products added by the Product Manager will appear here</p>
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
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             class="w-9 h-9 rounded-lg object-cover border border-gray-100">
                                    @else
                                        <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-lg">📦</div>
                                    @endif
                                    <span class="font-medium text-gray-900">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-gray-500">{{ $product->category->icon }} {{ $product->category->name }}</td>
                            <td class="py-3 px-4 font-semibold text-gray-800">{{ number_format($product->price) }}</td>
                            <td class="py-3 px-4">
                                <span class="font-semibold
                                    {{ $product->stock == 0 ? 'text-red-600' : ($product->stock <= 5 ? 'text-amber-600' : 'text-green-600') }}">
                                    {{ $product->stock }}
                                    @if($product->stock == 0)<span class="text-xs font-normal">(Out)</span>
                                    @elseif($product->stock <= 5)<span class="text-xs font-normal">(Low)</span>
                                    @endif
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 text-xs rounded-full font-semibold
                                    {{ $product->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</x-app-layout>
