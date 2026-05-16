<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Track Order #{{ $order->id }} — StyleHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'DM Sans',sans-serif;}.font-display{font-family:'Syne',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white border-r border-gray-100 shadow-sm flex flex-col fixed inset-y-0 left-0 z-40">
        <div class="flex items-center gap-2 px-6 py-5 border-b border-gray-100">
            <span class="text-2xl">🛒</span>
            <span class="font-display font-bold text-xl text-gray-900">StyleHub</span>
        </div>
        <div class="px-6 py-4 border-b border-gray-100">
            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-orange-100 text-orange-700 mb-2">🛍️ Customer</span>
            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
        </div>
        <nav class="flex-1 px-4 py-4 space-y-1">
            <a href="{{ route('customer.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl text-gray-600 hover:bg-gray-50">🏠 Dashboard</a>
            <a href="{{ route('customer.cart.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl text-gray-600 hover:bg-gray-50">🛒 My Cart</a>
            <a href="{{ route('customer.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl bg-orange-50 text-orange-700 font-semibold">📦 My Orders</a>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <div class="ml-64 flex-1">
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center gap-3 sticky top-0 z-30">
            <a href="{{ route('customer.orders.index') }}" class="text-gray-400 hover:text-gray-700 text-sm">← My Orders</a>
            <h1 class="font-display font-semibold text-gray-900 text-lg">Track Order #{{ $order->id }}</h1>
        </header>

        <main class="p-6 max-w-3xl">

            @if(!$order->delivery)
                {{-- No delivery assigned yet --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center mb-6">
                    <div class="text-5xl mb-4">📋</div>
                    <h2 class="font-display font-bold text-xl text-gray-900 mb-2">Delivery Not Assigned Yet</h2>
                    <p class="text-gray-500 text-sm">Your order has been received and is being processed. A delivery will be assigned to it shortly.</p>
                    <div class="mt-6 inline-flex items-center gap-2 bg-yellow-50 border border-yellow-200 text-yellow-700 text-sm px-4 py-2 rounded-xl">
                        🕐 Order Status: <span class="font-semibold capitalize">{{ $order->status }}</span>
                    </div>
                </div>
            @else
                @php $delivery = $order->delivery; @endphp

                {{-- Tracking Number Banner --}}
                <div class="bg-gradient-to-r from-orange-500 to-amber-500 rounded-2xl p-5 text-white mb-6 flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-xs mb-1">Tracking Number</p>
                        <p class="font-mono font-bold text-2xl">{{ $delivery->tracking_number }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-orange-100 text-xs mb-1">Current Status</p>
                        <span class="bg-white/20 text-white font-semibold text-sm px-3 py-1 rounded-full">
                            {{ $delivery->status_icon }} {{ $delivery->status_label }}
                        </span>
                    </div>
                </div>

                {{-- Delivery Timeline --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
                    <h3 class="font-display font-semibold text-gray-900 mb-6">Delivery Progress</h3>

                    @php
                        $steps = [
                            ['key' => 'pending',          'label' => 'Order Placed',     'sublabel' => 'Your order is confirmed',        'icon' => '🕐'],
                            ['key' => 'dispatched',       'label' => 'Dispatched',       'sublabel' => 'Your order is on the way',       'icon' => '📦'],
                            ['key' => 'out_for_delivery', 'label' => 'Out for Delivery', 'sublabel' => 'Rider is heading to you',        'icon' => '🚚'],
                            ['key' => 'delivered',        'label' => 'Delivered',        'sublabel' => 'Order successfully delivered',   'icon' => '✅'],
                        ];
                        $statusOrder = ['pending' => 0, 'dispatched' => 1, 'out_for_delivery' => 2, 'delivered' => 3];
                        $currentOrder = $statusOrder[$delivery->status] ?? 0;
                    @endphp

                    <div class="space-y-4">
                        @foreach($steps as $i => $step)
                            @php $stepOrder = $statusOrder[$step['key']] ?? 0; $isActive = $currentOrder >= $stepOrder; $isCurrent = $currentOrder === $stepOrder; @endphp
                            <div class="flex items-start gap-4">
                                {{-- Icon --}}
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg flex-shrink-0
                                        {{ $isActive ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-400' }}
                                        {{ $isCurrent ? 'ring-4 ring-orange-200' : '' }}">
                                        {{ $step['icon'] }}
                                    </div>
                                    @if($i < count($steps) - 1)
                                        <div class="w-0.5 h-8 mt-1 {{ $isActive && $currentOrder > $stepOrder ? 'bg-orange-400' : 'bg-gray-200' }}"></div>
                                    @endif
                                </div>
                                {{-- Text --}}
                                <div class="pt-2">
                                    <p class="font-semibold text-sm {{ $isActive ? 'text-gray-900' : 'text-gray-400' }}">{{ $step['label'] }}</p>
                                    <p class="text-xs {{ $isActive ? 'text-gray-500' : 'text-gray-300' }}">{{ $step['sublabel'] }}</p>
                                    @if($isCurrent && $delivery->status === 'dispatched' && $delivery->dispatched_at)
                                        <p class="text-xs text-orange-600 font-medium mt-1">{{ $delivery->dispatched_at->format('M d, Y H:i') }}</p>
                                    @endif
                                    @if($isCurrent && $delivery->status === 'delivered' && $delivery->delivered_at)
                                        <p class="text-xs text-green-600 font-medium mt-1">{{ $delivery->delivered_at->format('M d, Y H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if($delivery->status === 'failed')
                            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
                                ❌ Delivery failed. Please contact support for assistance.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Delivery Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="font-display font-semibold text-gray-900 mb-3">Delivery Details</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Courier</span>
                                <span class="font-medium text-gray-900">{{ $delivery->courier_name ?? 'Not assigned yet' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Estimated Delivery</span>
                                <span class="font-medium text-gray-900">
                                    {{ $delivery->estimated_delivery_date ? $delivery->estimated_delivery_date->format('M d, Y') : 'To be confirmed' }}
                                </span>
                            </div>
                            @if($delivery->notes)
                            <div class="pt-2 border-t border-gray-100">
                                <p class="text-gray-500 mb-1">Notes</p>
                                <p class="text-gray-700">{{ $delivery->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="font-display font-semibold text-gray-900 mb-3">Delivery Address</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $delivery->delivery_address }}</p>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500">Recipient</p>
                            <p class="font-medium text-gray-900 text-sm">{{ $delivery->recipient_name }}</p>
                            @if($delivery->recipient_phone)
                                <p class="text-xs text-gray-500">{{ $delivery->recipient_phone }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Order Items --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-display font-semibold text-gray-900 mb-4">Items Ordered</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-3">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center text-lg">📦</div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-400">Qty: {{ $item->quantity }} × UGX {{ number_format($item->price) }}</p>
                            </div>
                        </div>
                        <p class="font-semibold text-gray-900 text-sm">UGX {{ number_format($item->price * $item->quantity) }}</p>
                    </div>
                    @endforeach
                    <div class="flex justify-between pt-3 font-bold text-gray-900">
                        <span>Total</span>
                        <span>UGX {{ number_format($order->total) }}</span>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
