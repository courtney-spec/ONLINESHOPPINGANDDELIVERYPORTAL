<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Analytics Report - {{ now()->format('F Y') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #3B82F6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #1e40af;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            color: #6b7280;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        .metrics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        .metric-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }
        .metric-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .metric-card .value {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .section {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        .section h2 {
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: left;
        }
        th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        tbody tr:hover {
            background: #f9fafb;
        }
        .chart-placeholder {
            background: #f3f4f6;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            color: #6b7280;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
        .status-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 20px;
        }
        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: #f9fafb;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Analytics Dashboard Report</h1>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <div class="metrics">
        <div class="metric-card">
            <h3>Total Revenue</h3>
            <div class="value">UGX {{ number_format($monthlyRevenue->sum('revenue')) }}</div>
        </div>
        <div class="metric-card">
            <h3>Total Orders</h3>
            <div class="value">{{ $dailyOrders->sum('count') }}</div>
        </div>
        <div class="metric-card">
            <h3>Total Users</h3>
            <div class="value">{{ $userRegistrations->sum('count') }}</div>
        </div>
        <div class="metric-card">
            <h3>Avg Order Value</h3>
            <div class="value">UGX {{ $dailyOrders->sum('count') > 0 ? number_format($monthlyRevenue->sum('revenue') / $dailyOrders->sum('count')) : 0 }}</div>
        </div>
    </div>

    <div class="section">
        <h2>📈 Revenue Trend</h2>
        <div class="chart-placeholder">
            <strong>Monthly Revenue Chart</strong><br>
            (Interactive chart available in web dashboard)
        </div>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Revenue (UGX)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monthlyRevenue as $item)
                    <tr>
                        <td>{{ $item['month'] }}</td>
                        <td>{{ number_format($item['revenue']) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" style="text-align: center;">No data available</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>📅 Daily Orders</h2>
        <div class="chart-placeholder">
            <strong>Daily Orders Chart</strong><br>
            (Interactive chart available in web dashboard)
        </div>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Orders</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dailyOrders as $item)
                    <tr>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->count }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" style="text-align: center;">No data available</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>👤 User Growth</h2>
        <div class="chart-placeholder">
            <strong>User Registrations Chart</strong><br>
            (Interactive chart available in web dashboard)
        </div>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Registrations</th>
                </tr>
            </thead>
            <tbody>
                @forelse($userRegistrations as $item)
                    <tr>
                        <td>{{ $item['month'] }}</td>
                        <td>{{ $item['count'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" style="text-align: center;">No data available</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>📊 Category Performance</h2>
        <div class="chart-placeholder">
            <strong>Sales by Category Chart</strong><br>
            (Interactive chart available in web dashboard)
        </div>
        <table>
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Revenue (UGX)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salesByCategory as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ number_format($item->revenue) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" style="text-align: center;">No data available</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>🏆 Top Performing Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Units Sold</th>
                    <th>Revenue (UGX)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->total_sold }}</td>
                        <td>{{ number_format($product->total_sold * $product->price) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" style="text-align: center;">No data available</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>📋 Order Status Distribution</h2>
        <div class="chart-placeholder">
            <strong>Order Status Chart</strong><br>
            (Interactive chart available in web dashboard)
        </div>
        <div class="status-list">
            @forelse($orderStatuses as $status)
                <div class="status-item">
                    <span style="text-transform: capitalize;">{{ $status->status }}</span>
                    <strong>{{ $status->count }}</strong>
                </div>
            @empty
                <div class="status-item">
                    <span>No data available</span>
                    <strong>0</strong>
                </div>
            @endforelse
        </div>
    </div>

    <div class="footer">
        <p>Report generated by Online Shopping & Delivery Portal Analytics System</p>
        <p>© {{ now()->format('Y') }} All rights reserved</p>
    </div>
</body>
</html>