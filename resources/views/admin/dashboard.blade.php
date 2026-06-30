@extends('layouts.app')

@push('styles')
<style>
    .nav-link { transition: all 100ms; border: 1px solid transparent; border-radius: 3px; }
    .nav-link:hover { border-color: white; }

    .stat-card { transition: box-shadow 200ms ease, transform 150ms ease; }
    .stat-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.1); transform: translateY(-1px); }

    .dash-table-row { border-bottom: 1px solid #E7E7E7; transition: background 150ms ease; }
    .dash-table-row:last-child { border-bottom: none; }
    .dash-table-row:hover { background-color: #F8F8F8; }

    .chart-card { transition: box-shadow 200ms ease; }
    .chart-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

    .panel-head { border-bottom: 1px solid #E7E7E7; }

    canvas { max-height: 260px; }
</style>
@endpush

@section('custom-navigation')
    <!-- ═══════════ ADMIN SUB NAVIGATION ═══════════ -->
    <nav class="bg-amz-nav sticky top-14 z-40">
        <div class="max-w-[1500px] mx-auto px-3 sm:px-4">
            <div class="flex items-center gap-0.5 h-11 text-white text-[13px]">
                <a href="{{ route('admin.dashboard') }}" class="nav-link px-2.5 py-1.5 font-bold border-b-2 border-amz-orange flex items-center gap-1.5">
                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i> Dashboard
                </a>
                <a href="{{ route('admin.orders.index') }}" class="nav-link px-2.5 py-1.5 flex items-center gap-1.5">
                    <i data-lucide="package" class="w-3.5 h-3.5"></i> Orders
                </a>
                <a href="products" class="nav-link px-2.5 py-1.5 flex items-center gap-1.5">
                    <i data-lucide="box" class="w-3.5 h-3.5"></i> Products
                </a>
                <a href="customers" class="nav-link px-2.5 py-1.5 flex items-center gap-1.5">
                    <i data-lucide="users" class="w-3.5 h-3.5"></i> Customers
                </a>
            </div>
        </div>
    </nav>
@endsection

@section('content')

    <!-- ═══════════ BREADCRUMB ═══════════ -->
    <div class="bg-white border-b border-amz-border">
        <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-2">
            <nav class="flex items-center gap-1 text-[12px]">
                <a href="{{ route('admin.dashboard') }}" class="text-amz-blue hover:text-amz-link-hover hover:underline">Admin</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
                <span class="text-amz-text font-medium">Dashboard</span>
            </nav>
        </div>
    </div>

    <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-6">

        <!-- ═══════════ PAGE HEADER ═══════════ -->
        <div class="bg-white rounded-lg border border-amz-border px-4 py-4 mb-6">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-[28px] font-bold text-amz-text mb-1">Dashboard</h1>
                    <p class="text-[14px] text-amz-text-sec">Store overview &middot; {{ now()->format('M d, Y') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="view-btn px-4 py-2 rounded-md text-[13px] font-semibold text-white bg-amz-nav hover:bg-amz-navHover transition-colors flex items-center gap-1.5">
                        <i data-lucide="package" class="w-3.5 h-3.5"></i> View Orders
                    </a>
                </div>
            </div>
        </div>

        <!-- ═══════════ STAT CARDS ═══════════ -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
            @php
                $statCards = [
                    ['label' => 'Products',  'value' => $totalProducts,                       'icon' => 'box',        'color' => '#232F3E', 'bg' => '#F0F2F2'],
                    ['label' => 'Orders',    'value' => $totalOrders,                          'icon' => 'package',    'color' => '#0066CC', 'bg' => '#E8F4FF'],
                    ['label' => 'Customers', 'value' => $totalCustomers,                       'icon' => 'users',      'color' => '#6B21A8', 'bg' => '#F3E8FF'],
                    ['label' => 'Sales',     'value' => '$' . number_format($totalSales, 2),   'icon' => 'dollar-sign','color' => '#067D62', 'bg' => '#D4F3EA'],
                ];
            @endphp
            @foreach($statCards as $card)
            <div class="stat-card bg-white rounded-lg border border-amz-border p-4" style="border-left: 3px solid {{ $card['color'] }};">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wide" style="color: {{ $card['color'] }}">{{ $card['label'] }}</span>
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background: {{ $card['bg'] }}">
                        <i data-lucide="{{ $card['icon'] }}" class="w-4 h-4" style="color: {{ $card['color'] }}"></i>
                    </div>
                </div>
                <p class="text-[26px] font-bold {{ $card['label'] === 'Sales' ? 'text-amz-green' : 'text-amz-text' }}">{{ $card['value'] }}</p>
            </div>
            @endforeach
        </div>

        <!-- ═══════════ CHARTS ═══════════ -->
        <div class="grid md:grid-cols-2 gap-4 mb-6">
            <div class="chart-card bg-white rounded-lg border border-amz-border p-4">
                <div class="flex items-center justify-between mb-3 pb-3 panel-head">
                    <h2 class="text-[15px] font-bold text-amz-text flex items-center gap-2">
                        <i data-lucide="trending-up" class="w-4 h-4 text-amz-orange"></i> Monthly Sales
                    </h2>
                </div>
                <canvas id="salesChart"></canvas>
            </div>

            <div class="chart-card bg-white rounded-lg border border-amz-border p-4">
                <div class="flex items-center justify-between mb-3 pb-3 panel-head">
                    <h2 class="text-[15px] font-bold text-amz-text flex items-center gap-2">
                        <i data-lucide="pie-chart" class="w-4 h-4 text-amz-blue"></i> Orders Status
                    </h2>
                </div>
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- ═══════════ STOCK TABLES ═══════════ -->
        <div class="grid md:grid-cols-2 gap-4 mb-6">

            <!-- Low Stock -->
            <div class="bg-white rounded-lg border border-amz-border overflow-hidden">
                <div class="px-4 py-3 panel-head flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-amz-deal"></i>
                    <h2 class="text-[15px] font-bold text-amz-deal">Low Stock Products</h2>
                </div>
                <div class="hidden md:grid grid-cols-[2fr_1fr_1fr] gap-4 px-4 py-2.5 bg-amz-page text-[11px] font-bold text-amz-text-sec uppercase tracking-wide">
                    <span>Product</span>
                    <span>Available</span>
                    <span>Reserved</span>
                </div>
                @forelse($lowStockProducts as $product)
                <div class="dash-table-row grid grid-cols-[2fr_1fr_1fr] gap-4 px-4 py-3 items-center">
                    <span class="text-[13px] text-amz-text font-medium truncate">{{ $product->name }}</span>
                    <span class="text-[13px] text-amz-text">{{ $product->quantity }}</span>
                    <span class="text-[13px] font-semibold text-amz-orange">{{ $product->reserved_quantity }}</span>
                </div>
                @empty
                <div class="px-4 py-8 text-center">
                    <i data-lucide="check-circle" class="w-7 h-7 text-amz-green mx-auto mb-2"></i>
                    <p class="text-[13px] text-amz-text-sec">No low stock products</p>
                </div>
                @endforelse
            </div>

            <!-- Reserved -->
            <div class="bg-white rounded-lg border border-amz-border overflow-hidden">
                <div class="px-4 py-3 panel-head flex items-center gap-2">
                    <i data-lucide="lock" class="w-4 h-4 text-amz-blue"></i>
                    <h2 class="text-[15px] font-bold text-amz-text">Reserved Stock</h2>
                </div>
                <div class="hidden md:grid grid-cols-[2fr_1fr] gap-4 px-4 py-2.5 bg-amz-page text-[11px] font-bold text-amz-text-sec uppercase tracking-wide">
                    <span>Product</span>
                    <span>Reserved</span>
                </div>
                @forelse($reservedProducts as $product)
                <div class="dash-table-row grid grid-cols-[2fr_1fr] gap-4 px-4 py-3 items-center">
                    <span class="text-[13px] text-amz-text font-medium truncate">{{ $product->name }}</span>
                    <span class="text-[13px] font-semibold text-amz-blue">{{ $product->reserved_quantity }}</span>
                </div>
                @empty
                <div class="px-4 py-8 text-center">
                    <p class="text-[13px] text-amz-text-sec">No reserved stock</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- ═══════════ TOP PRODUCTS ═══════════ -->
        <div class="bg-white rounded-lg border border-amz-border overflow-hidden">
            <div class="px-4 py-3 panel-head flex items-center gap-2">
                <i data-lucide="award" class="w-4 h-4 text-amz-orange"></i>
                <h2 class="text-[15px] font-bold text-amz-text">Top Selling Products</h2>
            </div>
            <div class="hidden md:grid grid-cols-[40px_2fr_1fr] gap-4 px-4 py-2.5 bg-amz-page text-[11px] font-bold text-amz-text-sec uppercase tracking-wide">
                <span>#</span>
                <span>Product</span>
                <span>Sold</span>
            </div>
            @forelse($topProducts as $i => $item)
            <div class="dash-table-row grid grid-cols-[40px_2fr_1fr] gap-4 px-4 py-3 items-center">
                <span class="text-[13px] font-bold text-amz-text-weak">{{ $i + 1 }}</span>
                <span class="text-[13px] text-amz-text font-medium truncate">{{ $item->product->name ?? '—' }}</span>
                <span class="text-[13px] font-semibold text-amz-green">{{ $item->total_sold }}</span>
            </div>
            @empty
            <div class="px-4 py-8 text-center">
                <p class="text-[13px] text-amz-text-sec">No sales data yet</p>
            </div>
            @endforelse
        </div>

    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ORANGE = '#FF9900';
        const BLUE   = '#007185';
        const NAVY   = '#232F3E';
        const GREEN  = '#067D62';
        const PURPLE = '#6B21A8';
        const RED    = '#C40C0C';
        const GREY   = '#979797';

        // ─── Sales Chart ───
        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: {
                labels: @json(collect($salesData)->pluck('month')),
                datasets: [{
                    label: 'Sales',
                    data: @json(collect($salesData)->pluck('sales')),
                    borderColor: ORANGE,
                    backgroundColor: 'rgba(255,153,0,0.12)',
                    borderWidth: 2,
                    pointBackgroundColor: ORANGE,
                    pointRadius: 3,
                    tension: 0.35,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#F0F2F2' }, ticks: { color: '#565959', font: { size: 11 } } },
                    x: { grid: { display: false }, ticks: { color: '#565959', font: { size: 11 } } }
                }
            }
        });

        // ─── Status Chart ───
        const statusLabels = @json($ordersStatus->pluck('order_status'));
        const statusColors = { pending: RED, processing: BLUE, confirmed: '#16A34A', shipped: PURPLE, delivered: GREEN, cancelled: GREY };

        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: @json($ordersStatus->pluck('total')),
                    backgroundColor: statusLabels.map(s => statusColors[s] || NAVY),
                    borderWidth: 2,
                    borderColor: '#fff',
                }]
            },
            options: {
                responsive: true,
                cutout: '62%',
                plugins: {
                    legend: { position: 'bottom', labels: { color: '#565959', font: { size: 11 }, padding: 12, boxWidth: 10 } }
                }
            }
        });

        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush
