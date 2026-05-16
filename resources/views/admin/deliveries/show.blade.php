<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delivery {{ $delivery->tracking_number }} — StyleHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'DM Sans',sans-serif;}.font-display{font-family:'Syne',sans-serif;}</style>
</head>
<body class="bg-gray-50 min-h-screen">
<div class="flex min-h-screen">
    <aside class="w-64 bg-white border-r border-gray-100 shadow-sm flex flex-col fixed inset-y-0 left-0 z-40">
        <div class="flex items-center gap-2 px-6 py-5 border-b border-gray-100">
            <span class="text-2xl">🛒</span><span class="font-display font-bold text-xl text-gray-900">StyleHub</span>
        </div>
        <div class="px-6 py-4 border-b border-gray-100">
            <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-purple-100 text-purple-700 mb-2">⚙️ Admin</span>
            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
        </div>
        <nav class="flex-1 px-4 py-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl text-gray-600 hover:bg-gray-50">📊 Dashboard</a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl text-gray-600 hover:bg-gray-50">🛒 Orders</a>
            <a href="{{ route('admin.deliveries.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl bg-purple-50 text-purple-700 font-semibold">🚚 Deliveries</a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl text-gray-600 hover:bg-gray-50">👥 Users</a>
            <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl text-gray-600 hover:bg-gray-50">📈 Reports</a>
        </nav>
        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl">Sign Out</button>
            </form>
        </div>
    </aside>

    <div class="ml-64 flex-1">
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.deliveries.index') }}" class="text-gray-400 hover:text-gray-700 text-sm">← Back</a>
                <h1 class="font-display font-semibold text-gray-900 text-lg">Delivery: {{ $delivery->tracking_number }}</h1>
            </div>
            <a href="{{ route('admin.deliveries.edit', $delivery) }}"
               class="bg-purple-500 hover:bg-purple-600 text-white text-sm font-semibold px-4 py-2 rounded-xl">
               Update Status
            </a>
        </header>

        <main class="p-6 max-w-4xl">
            @if(session('success'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">✅ {{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                {{-- Delivery Info --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-display font-semibold text-gray-900 mb-4">Delivery Information</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tracking Number</span>
                            <span class="font-mono font-semibold text-gray-900">{{ $delivery->tracking_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="px-2.5 py-1 text-xs rounded-full font-semibold {{ $delivery->status_color }}">
                                {{ $delivery->status_icon }} {{ $delivery->status_label }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Courier</span>
                            <span class="font-medium text-gray-900">{{ $delivery->courier_name ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Est. Delivery</span>
                            <span class="font-medium text-gray-900">
                                {{ $delivery->estimated_delivery_date ? $delivery->estimated_delivery_date->format('M d, Y') : '—' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Dispatched At</span>
                            <span class="font-medium text-gray-900">{{ $delivery->dispatched_at ? $delivery->dispatched_at->format('M d, Y H:i') : '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Delivered At</span>
                            <span class="font-medium text-gray-900">{{ $delivery->delivered_at ? $delivery->delivered_at->format('M d, Y H:i') : '—' }}</span>
                        </div>
                    </div>
                    @if($delivery->notes)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs font-semibold text-gray-500 mb-1">Notes</p>
                            <p class="text-sm text-gray-600">{{ $delivery->notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Recipient & Order Info --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-display font-semibold text-gray-900 mb-4">Recipient & Order</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Recipient</span>
                            <span class="font-medium text-gray-900">{{ $delivery->recipient_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Phone</span>
                            <span class="font-medium text-gray-900">{{ $delivery->recipient_phone ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Order #</span>
                            <a href="{{ route('admin.orders.show', $delivery->order) }}" class="text-purple-600 hover:underline font-medium">#{{ $delivery->order_id }}</a>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Customer</span>
                            <span class="font-medium text-gray-900">{{ $delivery->order->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Order Total</span>
                            <span class="font-semibold text-gray-900">UGX {{ number_format($delivery->order->total) }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 mb-1">Delivery Address</p>
                        <p class="text-sm text-gray-600">{{ $delivery->delivery_address }}</p>
                    </div>
                </div>
            </div>

            {{-- Delivery Timeline --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
                <h3 class="font-display font-semibold text-gray-900 mb-5">Delivery Timeline</h3>
                <div class="flex items-center justify-between relative">
                    <div class="absolute left-0 right-0 h-1 bg-gray-200 top-5 z-0"></div>
                    @php
                        $steps = [
                            ['key' => 'pending',          'label' => 'Pending',          'icon' => '🕐'],
                            ['key' => 'dispatched',       'label' => 'Dispatched',       'icon' => '📦'],
                            ['key' => 'out_for_delivery', 'label' => 'Out for Delivery', 'icon' => '🚚'],
                            ['key' => 'delivered',        'label' => 'Delivered',        'icon' => '✅'],
                        ];
                        $statusOrder = ['pending' => 0, 'dispatched' => 1, 'out_for_delivery' => 2, 'delivered' => 3, 'failed' => 4];
                        $currentOrder = $statusOrder[$delivery->status] ?? 0;
                    @endphp
                    @foreach($steps as $step)
                        @php $stepOrder = $statusOrder[$step['key']] ?? 0; @endphp
                        <div class="flex flex-col items-center z-10 flex-1">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg mb-2
                                {{ $currentOrder >= $stepOrder ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                {{ $step['icon'] }}
                            </div>
                            <p class="text-xs font-semibold text-center {{ $currentOrder >= $stepOrder ? 'text-purple-700' : 'text-gray-400' }}">
                                {{ $step['label'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
                @if($delivery->status === 'failed')
                    <div class="mt-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl text-center">
                        ❌ This delivery has failed. Please update the status or reassign.
                    </div>
                @endif
            </div>

            {{-- Order Items --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-display font-semibold text-gray-900 mb-4">Items in this Order</h3>
                <div class="space-y-3">
                    @foreach($delivery->order->items as $item)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <div class="flex items-center gap-3">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">📦</div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $item->product->name }}</p>
                                <p class="text-xs text-gray-400">Qty: {{ $item->quantity }}</p>
                            </div>
                        </div>
                        <p class="font-semibold text-gray-900 text-sm">UGX {{ number_format($item->price * $item->quantity) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
