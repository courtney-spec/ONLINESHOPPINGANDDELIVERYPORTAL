<x-app-layout>
    <x-slot name="title">Settings</x-slot>

    <x-slot name="sidebar">
        @php
            $links = [
                ['href' => route('admin.dashboard'), 'icon' => '📊', 'label' => 'Dashboard'],
                ['href' => route('admin.users.index'), 'icon' => '👥', 'label' => 'Manage Users'],
                ['href' => '#', 'icon' => '📦', 'label' => 'Products'],
                ['href' => route('admin.orders.index'), 'icon' => '🛒', 'label' => 'Orders'],
                ['href' => route('admin.settings.index'), 'icon' => '⚙️', 'label' => 'Settings'],
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
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Settings</h1>
                <p class="text-gray-500">Manage your store configuration and preferences.</p>
            </div>

            <div class="grid gap-6">
                <!-- General Settings -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">General Settings</h2>
                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 font-medium">Store Name</p>
                            <p class="text-lg font-semibold text-gray-900 mt-1">Online Shopping & Delivery Portal</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 font-medium">Database</p>
                            <p class="text-lg font-semibold text-gray-900 mt-1">MySQL</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 font-medium">Environment</p>
                            <p class="text-lg font-semibold text-gray-900 mt-1">{{ app()->environment() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Currency & Localization -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Currency & Localization</h2>
                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 font-medium">Currency</p>
                            <p class="text-lg font-semibold text-gray-900 mt-1">UGX (Ugandan Shilling)</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 font-medium">Timezone</p>
                            <p class="text-lg font-semibold text-gray-900 mt-1">UTC</p>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">System Information</h2>
                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 font-medium">Laravel Version</p>
                            <p class="text-lg font-semibold text-gray-900 mt-1">{{ app()->version() }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-600 font-medium">PHP Version</p>
                            <p class="text-lg font-semibold text-gray-900 mt-1">{{ phpversion() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
