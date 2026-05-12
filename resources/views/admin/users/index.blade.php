<x-app-layout>
    <x-slot name="title">Manage Users</x-slot>

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
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manage Users</h1>
                    <p class="text-sm text-gray-500 mt-1">View and manage all registered users.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-purple-50 px-3 py-1 text-sm font-semibold text-purple-700">
                    {{ $users->count() }} total users
                </span>
            </div>

            @if ($users->isEmpty())
                <div class="text-center py-16">
                    <p class="text-gray-500 text-lg mb-3">No users registered yet.</p>
                    <p class="text-gray-400">Users will appear here once they register.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase">User</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase">Role</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase">Orders</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase">Joined</th>
                                <th class="py-3 px-4 font-semibold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-700 font-semibold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                            @if ($user->role === 'admin') bg-purple-100 text-purple-800
                                            @elseif ($user->role === 'product_manager') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 font-semibold text-gray-900">{{ $user->orders_count }}</td>
                                    <td class="py-4 px-4 text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="text-purple-600 hover:text-purple-700 font-semibold">View</a>
                                            <span class="text-gray-300">|</span>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="text-blue-600 hover:text-blue-700 font-semibold">Edit</a>
                                            @if ($user->id !== auth()->id())
                                                <span class="text-gray-300">|</span>
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                      onsubmit="return confirm('Are you sure you want to delete this user?')"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-700 font-semibold">Delete</button>
                                                </form>
                                            @endif
                                        </div>
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