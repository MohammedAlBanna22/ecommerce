@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">

    <div class="max-w-7xl mx-auto">

        <!-- ═══════════════════════════════════════════════════════ -->
        <!-- HEADER SECTION -->
        <!-- ═══════════════════════════════════════════════════════ -->
        <div class="mb-12">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-slate-900 tracking-tight">
                        Dashboard
                    </h1>
                    <p class="text-slate-600 mt-2 text-lg">
                        Welcome back! Here's your store performance overview.
                    </p>
                </div>

                <div class="mt-6 md:mt-0">
                    <p class="text-slate-500 text-sm">
                        Last updated: {{ now()->format('M d, Y \a\t H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════════ -->
        <!-- STATS CARDS -->
        <!-- ═══════════════════════════════════════════════════════ -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

            <!-- Total Products -->
            <div class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-slate-100 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-blue-500 to-blue-600"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-slate-600 text-sm font-semibold uppercase tracking-wide">
                            Products
                        </h3>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8-4m-8 4v10l8-4M9 5l8 4"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl md:text-4xl font-bold text-slate-900">
                        {{ $totalProducts }}
                    </p>
                    <p class="text-slate-500 text-xs mt-2">
                        In catalog
                    </p>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-slate-100 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-purple-500 to-purple-600"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-slate-600 text-sm font-semibold uppercase tracking-wide">
                            Orders
                        </h3>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl md:text-4xl font-bold text-slate-900">
                        {{ $totalOrders }}
                    </p>
                    <p class="text-slate-500 text-xs mt-2">
                        Total transactions
                    </p>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-slate-100 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-emerald-500 to-emerald-600"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-slate-600 text-sm font-semibold uppercase tracking-wide">
                            Customers
                        </h3>
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-200 transition">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM18 20a6 6 0 11-12 0v-2a6 6 0 0112 0v2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl md:text-4xl font-bold text-slate-900">
                        {{ $totalCustomers }}
                    </p>
                    <p class="text-slate-500 text-xs mt-2">
                        Registered users
                    </p>
                </div>
            </div>

            <!-- Total Sales -->
            <div class="group relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-slate-100 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-gradient-to-b from-amber-500 to-amber-600"></div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-slate-600 text-sm font-semibold uppercase tracking-wide">
                            Total Sales
                        </h3>
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-amber-200 transition">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl md:text-4xl font-bold text-slate-900">
                        ${{ number_format($totalSales, 0) }}
                    </p>
                    <p class="text-slate-500 text-xs mt-2">
                        Revenue
                    </p>
                </div>
            </div>

        </div>

        <!-- ═══════════════════════════════════════════════════════ -->
        <!-- CHARTS SECTION -->
        <!-- ═══════════════════════════════════════════════════════ -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">

            <!-- Sales Chart (Large) -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 p-8">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Sales Trend</h2>
                    <p class="text-slate-500 text-sm mt-1">Monthly revenue overview</p>
                </div>
                <div class="relative" style="height: 320px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Status Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900">Order Status</h2>
                    <p class="text-slate-500 text-sm mt-1">Distribution</p>
                </div>
                <div class="relative" style="height: 320px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

        </div>

        <!-- ═══════════════════════════════════════════════════════ -->
        <!-- DATA LISTS SECTION -->
        <!-- ═══════════════════════════════════════════════════════ -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- Low Stock Products -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="bg-gradient-to-r from-red-50 to-red-50 px-6 py-4 border-b border-red-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-red-900">Low Stock</h3>
                    </div>
                </div>
                <div class="p-6 space-y-3 max-h-96 overflow-y-auto">
                    @forelse($lowStockProducts as $product)
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg hover:bg-red-100 transition">
                            <div class="flex-1">
                                <p class="font-medium text-slate-900 text-sm truncate">
                                    {{ $product->name }}
                                </p>
                            </div>
                            <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-200 text-red-800">
                                {{ $product->quantity }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-slate-500 text-sm">All products in stock</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Selling Products -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="bg-gradient-to-r from-emerald-50 to-emerald-50 px-6 py-4 border-b border-emerald-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-emerald-900">Top Selling</h3>
                    </div>
                </div>
                <div class="p-6 space-y-3 max-h-96 overflow-y-auto">
                    @forelse($topProducts as $item)
                        <div class="flex items-center justify-between p-3 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition">
                            <div class="flex-1">
                                <p class="font-medium text-slate-900 text-sm truncate">
                                    {{ $item->product->name }}
                                </p>
                            </div>
                            <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-200 text-emerald-800">
                                {{ $item->total_sold }} sold
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-slate-500 text-sm">No sales yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Order Status -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="bg-gradient-to-r from-blue-50 to-blue-50 px-6 py-4 border-b border-blue-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-blue-900">Orders</h3>
                    </div>
                </div>
                <div class="p-6 space-y-3 max-h-96 overflow-y-auto">
                    @forelse($ordersStatus as $status)
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                            <div class="flex-1">
                                <p class="font-medium text-slate-900 text-sm">
                                    {{ ucfirst($status->order_status) }}
                                </p>
                            </div>
                            <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-200 text-blue-800">
                                {{ $status->total }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-slate-500 text-sm">No orders</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Chart Colors
    const colors = {
        primary: 'rgb(59, 130, 246)',      // blue
        secondary: 'rgb(139, 92, 246)',    // purple
        success: 'rgb(16, 185, 129)',      // emerald
        warning: 'rgb(245, 158, 11)',      // amber
        danger: 'rgb(239, 68, 68)',        // red
    };

    // Sales Chart
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: @json(collect($salesData)->pluck('month')),
                datasets: [{
                    label: 'Monthly Sales',
                    data: @json(collect($salesData)->pluck('sales')),
                    borderColor: colors.primary,
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: '#64748b',
                            font: { size: 13, weight: '600' },
                            padding: 20,
                            usePointStyle: true,
                        }
                    },
                    grid: {
                        display: true,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#94a3b8', font: { size: 12 } },
                        grid: { color: 'rgba(148, 163, 184, 0.1)' }
                    },
                    x: {
                        ticks: { color: '#94a3b8', font: { size: 12 } },
                        grid: { color: 'rgba(148, 163, 184, 0.1)' }
                    }
                }
            }
        });
    }

    // Status Chart (Doughnut)
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: @json($statusData->pluck('order_status')),
                datasets: [{
                    data: @json($statusData->pluck('total')),
                    backgroundColor: [
                        colors.primary,
                        colors.success,
                        colors.warning,
                        colors.danger,
                    ],
                    borderColor: '#fff',
                    borderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#64748b',
                            font: { size: 12, weight: '600' },
                            padding: 15,
                            usePointStyle: true,
                        }
                    }
                }
            }
        });
    }
</script>

@endsection
