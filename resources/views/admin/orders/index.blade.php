<x-app-layout>
    <x-slot name="title">All Orders</x-slot>

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
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">All Orders</h1>
                    <p class="text-sm text-gray-500 mt-1">Review every order placed by customers.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-orange-50 px-3 py-1 text-sm font-semibold text-orange-700">
                    {{ $orders->count() }} total orders
                </span>
            </div>

            @if ($orders->isEmpty())
                <div class="text-center py-16">
                    <p class="text-gray-500 text-lg mb-3">No orders have been placed yet.</p>
                    <p class="text-gray-400">Once customers place orders, they will appear here.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase">Order</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase">Customer</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase">Total</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase">Status</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase">Placed</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-4 font-medium text-gray-900">#{{ $order->id }}</td>
                                    <td class="py-4 px-4 text-gray-600">{{ $order->user?->name ?? 'Guest' }}</td>
                                    <td class="py-4 px-4 font-semibold text-gray-900">UGX {{ number_format($order->total) }}</td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                            @if ($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif ($order->status === 'completed') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="py-4 px-4">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="text-purple-600 hover:text-purple-700 font-semibold">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
