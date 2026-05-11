<x-app-layout>
    <x-slot name="title">My Orders</x-slot>

    <x-slot name="sidebar">
        @php
            $links = [
                ['href' => route('customer.dashboard'), 'icon' => '🏠', 'label' => 'My Dashboard'],
                ['href' => route('customer.cart.index'), 'icon' => '🛒', 'label' => 'My Cart'],
                ['href' => route('customer.orders.index'), 'icon' => '📦', 'label' => 'My Orders'],
                ['href' => route('profile.edit'), 'icon' => '👤', 'label' => 'Profile'],
            ];
        @endphp
        @foreach($links as $link)
            <a href="{{ $link['href'] }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-colors {{ request()->url() === $link['href'] ? 'bg-orange-50 text-orange-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                <span>{{ $link['icon'] }}</span>
                {{ $link['label'] }}
            </a>
        @endforeach
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h3 class="font-bold text-lg mb-4">Customer Menu</h3>
                <nav class="space-y-3">
                    <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-100 transition">
                        📦 Browse Products
                    </a>
                    <a href="{{ route('customer.cart.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100 transition">
                        🛒 Shopping Cart
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="block px-4 py-2 rounded bg-orange-100 text-orange-600 font-semibold">
                        📋 My Orders
                    </a>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded hover:bg-gray-100 transition">
                        👤 Profile
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-3xl font-bold mb-6 text-gray-800">My Orders</h1>

                @if ($orders->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg mb-4">No orders yet</p>
                        <p class="text-gray-400 mb-6">Start shopping to place your first order</p>
                        <a href="{{ route('customer.dashboard') }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded transition">
                            Continue Shopping
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($orders as $order)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-start">
                                    <!-- Order ID & Date -->
                                    <div>
                                        <p class="text-gray-500 text-sm">Order ID</p>
                                        <p class="font-semibold text-gray-800">#{{ $order->id }}</p>
                                        <p class="text-gray-500 text-sm">{{ $order->created_at->format('M d, Y') }}</p>
                                    </div>

                                    <!-- Items Count -->
                                    <div>
                                        <p class="text-gray-500 text-sm">Items</p>
                                        <p class="font-semibold text-gray-800">{{ $order->items->count() }}</p>
                                        <p class="text-gray-500 text-sm">{{ $order->items->sum('quantity') }} products</p>
                                    </div>

                                    <!-- Total -->
                                    <div>
                                        <p class="text-gray-500 text-sm">Total</p>
                                        <p class="font-semibold text-lg text-orange-600">₱{{ number_format($order->total, 2) }}</p>
                                    </div>

                                    <!-- Payment Method -->
                                    <div>
                                        <p class="text-gray-500 text-sm">Payment</p>
                                        <p class="font-semibold text-gray-800">
                                            @if ($order->payment_method === 'cash_on_delivery')
                                                Cash on Delivery
                                            @elseif ($order->payment_method === 'card')
                                                Credit Card
                                            @else
                                                Mobile Money
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Status & Action -->
                                    <div class="text-right">
                                        <p class="text-gray-500 text-sm mb-2">Status</p>
                                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                            @if ($order->status === 'pending')
                                                bg-yellow-100 text-yellow-800
                                            @elseif ($order->status === 'completed')
                                                bg-green-100 text-green-800
                                            @else
                                                bg-red-100 text-red-800
                                            @endif
                                        ">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        <a href="{{ route('customer.orders.show', $order) }}" class="block text-orange-500 hover:text-orange-600 text-sm mt-2 font-semibold">
                                            View Details →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>
