<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assign Delivery — StyleHub</title>
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
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl">Sign Out</button>
            </form>
        </div>
    </aside>

    <div class="ml-64 flex-1">
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center gap-3 sticky top-0 z-30">
            <a href="{{ route('admin.deliveries.index') }}" class="text-gray-400 hover:text-gray-700 text-sm">← Back</a>
            <h1 class="font-display font-semibold text-gray-900 text-lg">Assign Delivery to Order</h1>
        </header>

        <main class="p-6 max-w-2xl">
            @if($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">
                    @foreach($errors->all() as $error)<p>• {{ $error }}</p>@endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.deliveries.store') }}"
                  class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf

                {{-- Order Selection --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Select Order <span class="text-red-500">*</span></label>
                    <select name="order_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 @error('order_id') border-red-400 @enderror">
                        <option value="">Choose an order...</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}"
                                {{ (old('order_id') == $order->id || ($selectedOrder && $selectedOrder->id == $order->id)) ? 'selected' : '' }}>
                                Order #{{ $order->id }} — {{ $order->user->name }} — UGX {{ number_format($order->total) }}
                            </option>
                        @endforeach
                    </select>
                    @if($orders->isEmpty())
                        <p class="text-xs text-amber-600 mt-1">⚠️ All orders already have deliveries assigned, or there are no orders yet.</p>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Recipient Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Recipient Name <span class="text-red-500">*</span></label>
                        <input type="text" name="recipient_name" value="{{ old('recipient_name', $selectedOrder?->user?->name) }}" required
                               placeholder="Full name of recipient"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                    </div>

                    {{-- Recipient Phone --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Recipient Phone</label>
                        <input type="text" name="recipient_phone" value="{{ old('recipient_phone') }}"
                               placeholder="+256 700 000000"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                    </div>

                    {{-- Courier Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Courier / Rider Name</label>
                        <input type="text" name="courier_name" value="{{ old('courier_name') }}"
                               placeholder="e.g. John Rider"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                    </div>

                    {{-- Estimated Delivery Date --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Estimated Delivery Date</label>
                        <input type="date" name="estimated_delivery_date" value="{{ old('estimated_delivery_date') }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                    </div>
                </div>

                {{-- Delivery Address --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Delivery Address <span class="text-red-500">*</span></label>
                    <textarea name="delivery_address" rows="2" required
                              placeholder="Full delivery address..."
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 resize-none">{{ old('delivery_address', $selectedOrder?->shipping_address) }}</textarea>
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Delivery Notes</label>
                    <textarea name="notes" rows="2"
                              placeholder="Any special instructions for the delivery..."
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 resize-none">{{ old('notes') }}</textarea>
                </div>

                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">
                        🚚 Assign Delivery
                    </button>
                    <a href="{{ route('admin.deliveries.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2.5 rounded-xl transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </main>
    </div>
</div>
</body>
</html>
