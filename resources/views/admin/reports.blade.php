<x-app-layout>
    <x-slot name="title">Analytics Dashboard</x-slot>

    <x-slot name="sidebar">
        @php
            $links = [
                ['href' => route('admin.dashboard'), 'icon' => '📊', 'label' => 'Dashboard'],
                ['href' => route('admin.users.index'), 'icon' => '👥', 'label' => 'Manage Users'],
                ['href' => '#', 'icon' => '📦', 'label' => 'Products'],
                ['href' => route('admin.orders.index'), 'icon' => '🛒', 'label' => 'Orders'],
                ['href' => '#', 'icon' => '🚚', 'label' => 'Deliveries'],
                ['href' => route('admin.reports'), 'icon' => '📈', 'label' => 'Analytics'],
            ];
        @endphp
        @foreach($links as $link)
            <a href="{{ $link['href'] }}"
               class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-xl transition-colors
                      {{ request()->url() === $link['href'] ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                <span>{{ $link['icon'] }}</span>
                {{ $link['label'] }}
            </a>
        @endforeach
    </x-slot>

    {{-- Header --}}
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="font-display text-3xl font-bold text-gray-900">
                📊 Analytics Dashboard
            </h2>
            <p class="text-gray-500 text-sm mt-1">Comprehensive insights into your business performance</p>
        </div>
        <div class="flex gap-3">
            <button onclick="refreshCharts()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                🔄 Refresh
            </button>
            <a href="{{ route('admin.reports.download') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                📄 Export PDF
            </a>
        </div>
    </div>

    {{-- Key Metrics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Revenue</p>
                    <p class="text-2xl font-bold">UGX {{ number_format($monthlyRevenue->sum('revenue')) }}</p>
                </div>
                <div class="text-4xl">💰</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Orders</p>
                    <p class="text-2xl font-bold">{{ $dailyOrders->sum('count') }}</p>
                </div>
                <div class="text-4xl">📦</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Users</p>
                    <p class="text-2xl font-bold">{{ $userRegistrations->sum('count') }}</p>
                </div>
                <div class="text-4xl">👥</div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Avg Order Value</p>
                    <p class="text-2xl font-bold">UGX {{ $dailyOrders->sum('count') > 0 ? number_format($monthlyRevenue->sum('revenue') / $dailyOrders->sum('count')) : 0 }}</p>
                </div>
                <div class="text-4xl">📈</div>
            </div>
        </div>
    </div>

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Revenue Trend --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="text-blue-600">📈</span>
                </div>
                <div>
                    <h3 class="font-display font-semibold text-gray-900">Revenue Trend</h3>
                    <p class="text-sm text-gray-500">Monthly revenue over the last 12 months</p>
                </div>
            </div>
            <canvas id="revenueChart" height="300"></canvas>
        </div>

        {{-- Daily Orders --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-green-600">📅</span>
                </div>
                <div>
                    <h3 class="font-display font-semibold text-gray-900">Daily Orders</h3>
                    <p class="text-sm text-gray-500">Order volume for the last 30 days</p>
                </div>
            </div>
            <canvas id="dailyOrdersChart" height="300"></canvas>
        </div>

        {{-- User Registrations --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <span class="text-purple-600">👤</span>
                </div>
                <div>
                    <h3 class="font-display font-semibold text-gray-900">User Growth</h3>
                    <p class="text-sm text-gray-500">Monthly user registrations</p>
                </div>
            </div>
            <canvas id="userChart" height="300"></canvas>
        </div>

        {{-- Sales by Category --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                    <span class="text-orange-600">📊</span>
                </div>
                <div>
                    <h3 class="font-display font-semibold text-gray-900">Category Performance</h3>
                    <p class="text-sm text-gray-500">Revenue distribution by category</p>
                </div>
            </div>
            <canvas id="categoryChart" height="300"></canvas>
        </div>
    </div>

    {{-- Bottom Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Top Products --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:col-span-2">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <span class="text-indigo-600">🏆</span>
                </div>
                <div>
                    <h3 class="font-display font-semibold text-gray-900">Top Performing Products</h3>
                    <p class="text-sm text-gray-500">Best-selling products by quantity</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Product</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Units Sold</th>
                            <th class="text-left py-3 px-4 text-xs font-semibold text-gray-500 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 font-medium">{{ $product->name }}</td>
                                <td class="py-3 px-4">{{ $product->total_sold }}</td>
                                <td class="py-3 px-4">UGX {{ number_format($product->total_sold * $product->price) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-gray-500">No sales data available yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Order Status --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <span class="text-red-600">📋</span>
                </div>
                <div>
                    <h3 class="font-display font-semibold text-gray-900">Order Status</h3>
                    <p class="text-sm text-gray-500">Current order distribution</p>
                </div>
            </div>
            <canvas id="statusChart" height="250"></canvas>
            <div class="mt-4 space-y-2">
                @foreach($orderStatuses as $status)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 capitalize">{{ $status->status }}</span>
                        <span class="text-sm font-semibold">{{ $status->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Professional color schemes
        const colors = {
            primary: ['#3B82F6', '#1D4ED8', '#1E40AF', '#1E3A8A'],
            success: ['#10B981', '#059669', '#047857', '#065F46'],
            warning: ['#F59E0B', '#D97706', '#B45309', '#92400E'],
            danger: ['#EF4444', '#DC2626', '#B91C1C', '#991B1B'],
            info: ['#06B6D4', '#0891B2', '#0E7490', '#155E75'],
            purple: ['#8B5CF6', '#7C3AED', '#6D28D9', '#5B21B6'],
            pink: ['#EC4899', '#DB2777', '#BE185D', '#9D174D'],
            indigo: ['#6366F1', '#4F46E5', '#4338CA', '#3730A3']
        };

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = @json($monthlyRevenue);
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.month),
                datasets: [{
                    label: 'Revenue (UGX)',
                    data: revenueData.map(item => item.revenue),
                    borderColor: colors.primary[0],
                    backgroundColor: colors.primary[0] + '20',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary[0],
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'UGX ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Daily Orders Chart
        const dailyOrdersCtx = document.getElementById('dailyOrdersChart').getContext('2d');
        const dailyOrdersData = @json($dailyOrders);
        new Chart(dailyOrdersCtx, {
            type: 'bar',
            data: {
                labels: dailyOrdersData.map(item => item.date),
                datasets: [{
                    label: 'Orders',
                    data: dailyOrdersData.map(item => item.count),
                    backgroundColor: colors.success[0] + '80',
                    borderColor: colors.success[0],
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // User Registrations Chart
        const userCtx = document.getElementById('userChart').getContext('2d');
        const userData = @json($userRegistrations);
        new Chart(userCtx, {
            type: 'bar',
            data: {
                labels: userData.map(item => item.month),
                datasets: [{
                    label: 'Registrations',
                    data: userData.map(item => item.count),
                    backgroundColor: colors.purple.map(color => color + '80'),
                    borderColor: colors.purple,
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // Sales by Category Pie Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryData = @json($salesByCategory);
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => item.name),
                datasets: [{
                    data: categoryData.map(item => item.revenue),
                    backgroundColor: colors.info.concat(colors.warning, colors.danger),
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20 }
                    }
                }
            }
        });

        // Order Status Doughnut Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusData = @json($orderStatuses);
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusData.map(item => item.status),
                datasets: [{
                    data: statusData.map(item => item.count),
                    backgroundColor: [colors.success[0], colors.warning[0], colors.danger[0], colors.info[0]],
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                cutout: '70%'
            }
        });

        // Refresh function
        function refreshCharts() {
            location.reload();
        }
    </script>
</x-app-layout>