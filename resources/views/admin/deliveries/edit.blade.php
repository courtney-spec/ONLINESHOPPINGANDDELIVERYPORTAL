<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Delivery — StyleHub</title>
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
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center gap-3 sticky top-0 z-30">
            <a href="{{ route('admin.deliveries.show', $delivery) }}" class="text-gray-400 hover:text-gray-700 text-sm">← Back</a>
            <h1 class="font-display font-semibold text-gray-900 text-lg">Update Delivery Status</h1>
        </header>

        <main class="p-6 max-w-xl">
            @if($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl">
                    @foreach($errors->all() as $error)<p>• {{ $error }}</p>@endforeach
                </div>
            @endif

            {{-- Current Status --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tracking Number</p>
                    <p class="font-mono font-bold text-gray-900">{{ $delivery->tracking_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 mb-1">Current Status</p>
                    <span class="px-3 py-1 text-sm rounded-full font-semibold {{ $delivery->status_color }}">
                        {{ $delivery->status_icon }} {{ $delivery->status_label }}
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.deliveries.update', $delivery) }}"
                  class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
                @csrf @method('PATCH')

                {{-- New Status --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Update Status to <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 gap-2">
                        @foreach([
                            ['value' => 'pending',          'label' => 'Pending',          'icon' => '🕐', 'color' => 'border-yellow-300 bg-yellow-50 text-yellow-700'],
                            ['value' => 'dispatched',       'label' => 'Dispatched',       'icon' => '📦', 'color' => 'border-blue-300 bg-blue-50 text-blue-700'],
                            ['value' => 'out_for_delivery', 'label' => 'Out for Delivery', 'icon' => '🚚', 'color' => 'border-orange-300 bg-orange-50 text-orange-700'],
                            ['value' => 'delivered',        'label' => 'Delivered',        'icon' => '✅', 'color' => 'border-green-300 bg-green-50 text-green-700'],
                            ['value' => 'failed',           'label' => 'Failed',           'icon' => '❌', 'color' => 'border-red-300 bg-red-50 text-red-700'],
                        ] as $option)
                        <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all
                                      {{ old('status', $delivery->status) === $option['value'] ? $option['color'] . ' border-2' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="status" value="{{ $option['value'] }}"
                                   {{ old('status', $delivery->status) === $option['value'] ? 'checked' : '' }}
                                   class="accent-purple-500">
                            <span class="text-lg">{{ $option['icon'] }}</span>
                            <span class="font-semibold text-sm">{{ $option['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Courier --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Courier / Rider Name</label>
                    <input type="text" name="courier_name" value="{{ old('courier_name', $delivery->courier_name) }}"
                           placeholder="e.g. John Rider"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                </div>

                {{-- Estimated Delivery Date --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Estimated Delivery Date</label>
                    <input type="date" name="estimated_delivery_date"
                           value="{{ old('estimated_delivery_date', $delivery->estimated_delivery_date?->format('Y-m-d')) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Recipient Phone</label>
                    <input type="text" name="recipient_phone" value="{{ old('recipient_phone', $delivery->recipient_phone) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="3"
                              placeholder="Any updates or notes about this delivery..."
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 resize-none">{{ old('notes', $delivery->notes) }}</textarea>
                </div>

                <div class="flex gap-3 pt-2 border-t border-gray-100">
                    <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold px-6 py-2.5 rounded-xl transition-colors">
                        Save Update
                    </button>
                    <a href="{{ route('admin.deliveries.show', $delivery) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2.5 rounded-xl">
                        Cancel
                    </a>
                </div>
            </form>
        </main>
    </div>
</div>
</body>
</html>
