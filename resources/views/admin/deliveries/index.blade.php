<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deliveries — StyleHub</title>
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
            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
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
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <div class="ml-64 flex-1">
        <header class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
            <h1 class="font-display font-semibold text-gray-900 text-lg">Delivery Management</h1>
            <a href="{{ route('admin.deliveries.create') }}" class="bg-purple-500 hover:bg-purple-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
                + Assign Delivery
            </a>
        </header>

        <main class="p-6">
            @if(session('success'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-xl">✅ {{ session('success') }}</div>
            @endif

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
                @foreach([
                    ['label' => 'Total',          'value' => $stats['total'],          'color' => 'bg-gray-500',   'icon' => '📦'],
                    ['label' => 'Pending',         'value' => $stats['pending'],        'color' => 'bg-yellow-500', 'icon' => '🕐'],
                    ['label' => 'Dispatched',      'value' => $stats['dispatched'],     'color' => 'bg-blue-500',   'icon' => '📮'],
                    ['label' => 'Out for Delivery','value' => $stats['out_for_delivery'],'color' => 'bg-orange-500','icon' => '🚚'],
                    ['label' => 'Delivered',       'value' => $stats['delivered'],      'color' => 'bg-green-500',  'icon' => '✅'],
                    ['label' => 'Failed',          'value' => $stats['failed'],         'color' => 'bg-red-500',    'icon' => '❌'],
                ] as $stat)
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center gap-3">
                    <div class="w-10 h-10 {{ $stat['color'] }} rounded-xl flex items-center justify-center text-lg">{{ $stat['icon'] }}</div>
                    <div>
                        <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>
                        <p class="font-display font-bold text-xl text-gray-900">{{ $stat['value'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Search & Filter --}}
            <form method="GET" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="🔍 Search by tracking number or recipient..."
                           class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <select name="status" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400 text-gray-600">
                        <option value="">All Statuses</option>
                        <option value="pending"          {{ request('status') === 'pending'          ? 'selected' : '' }}>🕐 Pending</option>
                        <option value="dispatched"       {{ request('status') === 'dispatched'       ? 'selected' : '' }}>📦 Dispatched</option>
                        <option value="out_for_delivery" {{ request('status') === 'out_for_delivery' ? 'selected' : '' }}>🚚 Out for Delivery</option>
                        <option value="delivered"        {{ request('status') === 'delivered'        ? 'selected' : '' }}>✅ Delivered</option>
                        <option value="failed"           {{ request('status') === 'failed'           ? 'selected' : '' }}>❌ Failed</option>
                    </select>
                    <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl">Filter</button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.deliveries.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-5 py-2.5 rounded-xl text-center">Clear</a>
                    @endif
                </div>
            </form>

            {{-- Deliveries Table --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <span class="text-sm text-gray-500">{{ $deliveries->total() }} delivery record(s)</span>
                </div>
                @if($deliveries->isEmpty())
                    <div class="text-center py-16">
                        <div class="text-5xl mb-3">🚚</div>
                        <p class="font-medium text-gray-500">No deliveries found</p>
                        <a href="{{ route('admin.deliveries.create') }}" class="inline-block mt-4 bg-purple-500 text-white text-sm font-semibold px-5 py-2.5 rounded-xl">+ Assign First Delivery</a>
                    </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Tracking #</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Recipient</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Order</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Courier</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Est. Delivery</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($deliveries as $delivery)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 font-mono text-xs text-gray-700 font-semibold">{{ $delivery->tracking_number }}</td>
                                <td class="py-3 px-4">
                                    <p class="font-medium text-gray-900">{{ $delivery->recipient_name }}</p>
                                    @if($delivery->recipient_phone)
                                        <p class="text-xs text-gray-400">{{ $delivery->recipient_phone }}</p>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-gray-500">#{{ $delivery->order_id }}</td>
                                <td class="py-3 px-4 text-gray-500">{{ $delivery->courier_name ?? '—' }}</td>
                                <td class="py-3 px-4 text-gray-500">
                                    {{ $delivery->estimated_delivery_date ? $delivery->estimated_delivery_date->format('M d, Y') : '—' }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2.5 py-1 text-xs rounded-full font-semibold {{ $delivery->status_color }}">
                                        {{ $delivery->status_icon }} {{ $delivery->status_label }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.deliveries.show', $delivery) }}"
                                           class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg font-medium">View</a>
                                        <a href="{{ route('admin.deliveries.edit', $delivery) }}"
                                           class="text-xs bg-purple-100 hover:bg-purple-200 text-purple-700 px-3 py-1.5 rounded-lg font-medium">Update</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($deliveries->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">{{ $deliveries->links() }}</div>
                @endif
                @endif
            </div>
        </main>
    </div>
</div>
</body>
</html>
