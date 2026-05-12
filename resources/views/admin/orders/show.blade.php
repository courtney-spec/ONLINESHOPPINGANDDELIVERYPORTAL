<x-app-layout>
    <x-slot name="title">Order #{{ $order->id }}</x-slot>

    <x-slot name="sidebar">
        @php
            $links = [
                ['href' => route('admin.dashboard'), 'icon' => '📊', 'label' => 'Dashboard'],
                ['href' => route('admin.orders.index'), 'icon' => '🛒', 'label' => 'Orders'],
                ['href' => '#', 'icon' => '👥', 'label' => 'Manage Users'],
                ['href' => '#', 'icon' => '📦', 'label' => 'Products'],
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
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                    <p class="text-sm text-gray-500 mt-1">Placed by {{ $order->user?->name ?? $order->customer_name }} on {{ $order->created_at->format('M d, Y') }}</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-100 transition">
                    ← Back to all orders
                </a>
            </div>

            <div class="grid gap-4 lg:grid-cols-2 mb-8">
                <div class="rounded-2xl border border-gray-100 p-5 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Order ID</span>
                            <span class="font-semibold text-gray-900">#{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Customer</span>
                            <span class="font-semibold text-gray-900">{{ $order->user?->name ?? $order->customer_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Email</span>
                            <span>{{ $order->customer_email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phone</span>
                            <span>{{ $order->customer_phone }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Payment</span>
                            <span class="font-semibold text-gray-900">{{ ucwords(str_replace('_', ' ', $order->payment_method)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Status</span>
                            <span class="font-semibold text-gray-900">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total</span>
                            <span class="font-semibold text-orange-600">UGX {{ number_format($order->total) }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-100 p-5 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h2>
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $order->shipping_address }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Items</h2>

                @if ($order->items->isEmpty())
                    <p class="text-gray-500">This order has no items.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($order->items as $item)
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center border-b border-gray-100 pb-4">
                                <div class="md:col-span-2">
                                    <p class="font-semibold text-gray-900">{{ $item->product?->name ?? 'Deleted product' }}</p>
                                    <p class="text-sm text-gray-500">{{ $item->quantity }} × UGX {{ number_format($item->price) }}</p>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <span>Subtotal</span>
                                    <p class="font-semibold text-gray-900 mt-1">UGX {{ number_format($item->price * $item->quantity) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
