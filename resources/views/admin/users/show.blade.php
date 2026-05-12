<x-app-layout>
    <x-slot name="title">{{ $user->name }}</x-slot>

    <x-slot name="sidebar">
        @php
            $links = [
                ['href' => route('admin.dashboard'), 'icon' => '📊', 'label' => 'Dashboard'],
                ['href' => route('admin.users.index'), 'icon' => '👥', 'label' => 'Manage Users'],
                ['href' => '#', 'icon' => '📦', 'label' => 'Products'],
                ['href' => route('admin.orders.index'), 'icon' => '🛒', 'label' => 'Orders'],
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
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center text-purple-700 font-bold text-2xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition">
                        ✏️ Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="inline-flex items-center gap-2 rounded-full border border-purple-200 bg-purple-50 px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-100 transition">
                        ← Back to users
                    </a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3 mb-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="rounded-2xl border border-gray-100 p-5 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">User Information</h2>
                        <div class="space-y-3 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Name</span>
                                <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Email</span>
                                <span class="font-semibold text-gray-900">{{ $user->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Role</span>
                                <span class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Joined</span>
                                <span class="font-semibold text-gray-900">{{ $user->created_at->format('M d, Y \a\t g:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Last Updated</span>
                                <span class="font-semibold text-gray-900">{{ $user->updated_at->format('M d, Y \a\t g:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 p-5">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Recent Orders</h2>

                        @if ($user->orders->isEmpty())
                            <p class="text-gray-500">This user has not placed any orders yet.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($user->orders as $order)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-semibold text-gray-900">Order #{{ $order->id }}</p>
                                            <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-orange-600">UGX {{ number_format($order->total) }}</p>
                                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                                @if ($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif ($order->status === 'completed') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($user->orders->count() >= 5)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('admin.orders.index') }}?user={{ $user->id }}"
                                       class="text-purple-600 hover:text-purple-700 font-semibold">View all orders →</a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="rounded-2xl border border-gray-100 p-5 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h2>
                        <div class="space-y-4">
                            <div class="text-center">
                                <p class="text-3xl font-bold text-purple-600">{{ $user->orders->count() }}</p>
                                <p class="text-sm text-gray-500">Total Orders</p>
                            </div>
                            <div class="text-center">
                                <p class="text-3xl font-bold text-green-600">
                                    UGX {{ number_format($user->orders->sum('total')) }}
                                </p>
                                <p class="text-sm text-gray-500">Total Spent</p>
                            </div>
                        </div>
                    </div>

                    @if ($user->id !== auth()->id())
                    <div class="rounded-2xl border border-red-200 p-5 bg-red-50">
                        <h3 class="text-lg font-semibold text-red-900 mb-3">Danger Zone</h3>
                        <p class="text-sm text-red-700 mb-4">Deleting this user will permanently remove their account and all associated data.</p>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded transition">
                                Delete User
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>