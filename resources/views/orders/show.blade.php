<x-app-layout>
    <x-slot name="title">Order Details</x-slot>

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
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Order #{{ $order->id }}</h1>
                        <p class="text-gray-500">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                    </div>
                    <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold
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
                </div>

                <!-- Order Items -->
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Order Items</h2>
                    <div class="space-y-4">
                        @foreach ($order->items as $item)
                            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                                <div class="flex items-center space-x-4">
                                    @if ($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                            <span class="text-gray-400">No image</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                                        <p class="text-gray-500 text-sm">SKU: {{ $item->product->sku ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-600">₱{{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                                    <p class="font-semibold text-gray-800">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Shipping Address -->
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">Shipping Address</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $order->shipping_address }}</p>
                        </div>

                        <!-- Customer Details -->
                        <div>
                            <h3 class="font-bold text-gray-800 mb-2">Delivery Contact</h3>
                            <p class="text-gray-600 text-sm"><strong>Name:</strong> {{ $order->customer_name }}</p>
                            <p class="text-gray-600 text-sm"><strong>Email:</strong> {{ $order->customer_email }}</p>
                            <p class="text-gray-600 text-sm"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                        </div>

                        <!-- Order Total -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-800 mb-3">Order Total</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal:</span>
                                    <span>₱{{ number_format($order->total, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping:</span>
                                    <span>Free</span>
                                </div>
                                <div class="border-t border-gray-200 pt-2 mt-2 flex justify-between font-bold text-lg text-orange-600">
                                    <span>Total:</span>
                                    <span>₱{{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <h3 class="font-bold text-gray-800 mb-2">Payment Method</h3>
                    <p class="text-gray-600">
                        @if ($order->payment_method === 'cash_on_delivery')
                            💵 Cash on Delivery (Pay upon delivery)
                        @elseif ($order->payment_method === 'card')
                            💳 Credit Card
                        @else
                            📱 Mobile Money
                        @endif
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="{{ route('customer.orders.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded text-center transition">
                    ← Back to Orders
                </a>
                <a href="{{ route('customer.dashboard') }}" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-6 rounded text-center transition">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
