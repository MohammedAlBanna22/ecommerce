 @extends('layouts.app')




@push('styles')
    <style>
        * { -webkit-font-smoothing: antialiased; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f0f0f0; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #aaa; }

        .amz-input { border: 1px solid #DDD; transition: border-color 100ms, box-shadow 100ms; }
        .amz-input:focus { border-color: #E77600; box-shadow: 0 0 0 3px rgba(228,166,54,0.3); outline: none; }

        .amz-btn-cart {
            background: linear-gradient(to bottom, #FFE696, #FFD814);
            border: 1px solid #F3A847;
            border-radius: 100px;
            color: #0F1111;
            transition: background 100ms;
        }
        .amz-btn-cart:hover { background: linear-gradient(to bottom, #FFD814, #F7CA00); }

        .amz-btn-buy {
            background: linear-gradient(to bottom, #FFB84D, #FF9900);
            border: 1px solid #E47911;
            border-radius: 100px;
            color: #0F1111;
            transition: background 100ms;
        }
        .amz-btn-buy:hover { background: linear-gradient(to bottom, #FF9900, #E68A00); }

        .order-row { transition: background 150ms ease; border-bottom: 1px solid #E7E7E7; }
        .order-row:last-child { border-bottom: none; }
        .order-row:hover { background-color: #F8F8F8; }

        .status-badge { display: inline-flex; align-items: center; gap: 0.35rem; padding: 0.3rem 0.75rem; border-radius: 4px; font-size: 12px; font-weight: 600; white-space: nowrap; }

        .nav-link { transition: all 100ms; border: 1px solid transparent; border-radius: 3px; }
        .nav-link:hover { border-color: white; }

        .stat-card { transition: box-shadow 200ms ease; }
        .stat-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

        .amz-select { border: 1px solid #DDD; border-radius: 6px; background: white; transition: border-color 100ms, box-shadow 100ms; cursor: pointer; }
        .amz-select:focus { border-color: #E77600; box-shadow: 0 0 0 3px rgba(228,166,54,0.3); outline: none; }

        .update-btn {
            background: linear-gradient(to bottom, #FFE696, #FFD814);
            border: 1px solid #F3A847;
            border-radius: 6px;
            color: #0F1111;
            font-weight: 600;
            font-size: 12px;
            transition: background 100ms;
            cursor: pointer;
        }
        .update-btn:hover { background: linear-gradient(to bottom, #FFD814, #F7CA00); }

        .view-btn {
            background: #232F3E;
            border-radius: 6px;
            color: white;
            font-size: 12px;
            font-weight: 600;
            transition: background 100ms;
        }
        .view-btn:hover { background: #37475A; }

        .cancel-btn {
            border: 1px solid #CC0C39;
            border-radius: 6px;
            color: #CC0C39;
            font-size: 12px;
            font-weight: 600;
            transition: background 100ms;
        }
        .cancel-btn:hover { background: #FDEEE8; }

        .filter-tab { transition: all 100ms; border-bottom: 2px solid transparent; }
        .filter-tab.active { border-bottom-color: #FF9900; color: #0F1111; font-weight: 600; }
        .filter-tab:not(.active) { color: #565959; }
        .filter-tab:not(.active):hover { color: #0F1111; border-bottom-color: #DDD; }

        .admin-sidebar { width: 220px; flex-shrink: 0; }
    </style>
    @endpush







@section('custom-navigation')
    <!-- ═══════════ ADMIN SUB NAVIGATION ═══════════ -->
    <nav class="bg-amz-nav sticky top-14 z-40">
        <div class="max-w-[1500px] mx-auto px-3 sm:px-4">
            <div class="flex items-center gap-0.5 h-11 text-white text-[13px]">
                <a href="{{ route('admin.dashboard') }}" class="nav-link px-2.5 py-1.5 flex items-center gap-1.5">
                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i> Dashboard
                </a>
                <a href="{{ route('admin.orders.index') }}" class="nav-link px-2.5 py-1.5 font-bold border-b-2 border-amz-orange flex items-center gap-1.5">
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
                <span class="text-amz-text font-medium">Orders Management</span>
            </nav>
        </div>
    </div>

    <!-- ═══════════ MAIN CONTENT ═══════════ -->
    <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-6">

        <!-- ═══════════ PAGE HEADER ═══════════ -->
        <div class="bg-white rounded-lg border border-amz-border px-4 py-4 mb-6">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-[28px] font-bold text-amz-text mb-1">Orders Management</h1>
                    <p class="text-[14px] text-amz-text-sec">{{ $orders->count() }} total order{{ $orders->count() !== 1 ? 's' : '' }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-[13px] text-amz-text-sec font-medium">Sort by:</label>
                    <select id="sortBy" onchange="handleSort(this.value)" class="amz-select px-3 py-2 text-[13px]">
                        <option value="latest">Latest Orders</option>
                        <option value="oldest">Oldest Orders</option>
                        <option value="highest">Highest Total</option>
                        <option value="lowest">Lowest Total</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- ═══════════ STATS ROW ═══════════ -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
            @php
                $statuses = [
                    'all'        => ['label' => 'All Orders',  'icon' => 'package',      'color' => '#232F3E', 'bg' => '#F0F2F2'],
                    'pending'    => ['label' => 'Pending',     'icon' => 'clock',        'color' => '#C40C0C', 'bg' => '#FEE5E5'],
                    'confirmed'    => ['label' => 'confrimed',     'icon' => 'check-circle',        'color' => '#16A34A', 'bg' => '#DCFCE7'],
                    'processing' => ['label' => 'Processing',  'icon' => 'loader',       'color' => '#0066CC', 'bg' => '#E8F4FF'],
                    'shipped'    => ['label' => 'Shipped',     'icon' => 'truck',        'color' => '#6B21A8', 'bg' => '#F3E8FF'],
                    'delivered'  => ['label' => 'Delivered',   'icon' => 'check-circle', 'color' => '#067D62', 'bg' => '#D4F3EA'],
                ];
                $counts = [
                    'all'        => $orders->count(),
                    'pending'    => $orders->where('order_status', 'pending')->count(),
                    'processing' => $orders->where('order_status', 'processing')->count(),
                    'confirmed' => $orders->where('order_status', 'confirmed')->count(),
                    'shipped'    => $orders->where('order_status', 'shipped')->count(),
                    'delivered'  => $orders->where('order_status', 'delivered')->count(),
                ];
            @endphp
            @foreach($statuses as $key => $stat)
            <div class="stat-card bg-white rounded-lg border border-amz-border p-3 cursor-pointer"
                 onclick="filterOrders('{{ $key }}')"
                 style="border-left: 3px solid {{ $stat['color'] }};">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[11px] font-semibold uppercase tracking-wide" style="color: {{ $stat['color'] }}">{{ $stat['label'] }}</span>
                    <div class="w-7 h-7 rounded-full flex items-center justify-center" style="background: {{ $stat['bg'] }}">
                        <i data-lucide="{{ $stat['icon'] }}" class="w-3.5 h-3.5" style="color: {{ $stat['color'] }}"></i>
                    </div>
                </div>
                <p class="text-[24px] font-bold text-amz-text">{{ $counts[$key] }}</p>
            </div>
            @endforeach
        </div>

        <!-- ═══════════ FILTER TABS ═══════════ -->
        <div class="bg-white rounded-t-lg border border-b-0 border-amz-border px-4 pt-3 flex gap-1 overflow-x-auto">
            <button onclick="filterOrders('all')" class="filter-tab active px-4 py-2.5 text-[13px] whitespace-nowrap" data-status="all">
                All
            </button>
            <button onclick="filterOrders('pending')" class="filter-tab px-4 py-2.5 text-[13px] whitespace-nowrap" data-status="pending">
                Pending
            </button>
            <button onclick="filterOrders('processing')" class="filter-tab px-4 py-2.5 text-[13px] whitespace-nowrap" data-status="processing">
                Processing
            </button>
             <button onclick="filterOrders('confirmed')" class="filter-tab px-4 py-2.5 text-[13px] whitespace-nowrap" data-status="confirmed">
                confirmed
            </button>
            <button onclick="filterOrders('shipped')" class="filter-tab px-4 py-2.5 text-[13px] whitespace-nowrap" data-status="shipped">
                Shipped
            </button>
            <button onclick="filterOrders('delivered')" class="filter-tab px-4 py-2.5 text-[13px] whitespace-nowrap" data-status="delivered">
                Delivered
            </button>
            <button onclick="filterOrders('cancelled')" class="filter-tab px-4 py-2.5 text-[13px] whitespace-nowrap" data-status="cancelled">
                Cancelled
            </button>
        </div>

        <!-- ═══════════ ORDERS TABLE ═══════════ -->
        <div class="bg-white rounded-b-lg border border-amz-border overflow-hidden mb-6">

            <!-- Table Head -->
            <div class="hidden md:grid grid-cols-[2fr_2fr_1.5fr_1fr_1fr_1.5fr_auto] gap-4 px-4 py-3 bg-amz-page border-b border-amz-border text-[12px] font-bold text-amz-text-sec uppercase tracking-wide">
                <span>Order</span>
                <span>Customer</span>
                <span>Items / Total</span>
                <span>Payment</span>
                <span>Status</span>
                <span>Update Status</span>
                <span>Actions</span>
            </div>

            <!-- Empty State -->
            @if($orders->isEmpty())
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-amz-page rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="package-x" class="w-9 h-9 text-amz-text-weak"></i>
                </div>
                <h2 class="text-[18px] font-bold text-amz-text mb-2">No orders found</h2>
                <p class="text-[14px] text-amz-text-sec">Orders will appear here once customers start placing them.</p>
            </div>
            @endif

            <!-- Order Rows -->
            @foreach($orders as $order)
            @php
                $statusConfig = [
                    'pending'    => ['bg' => '#FEE5E5', 'text' => '#C40C0C', 'label' => 'Pending',    'icon' => 'clock'],
                    'processing' => ['bg' => '#E8F4FF', 'text' => '#0066CC', 'label' => 'Processing', 'icon' => 'loader'],
                      'confirmed'  => ['bg' => '#FEF3C7', 'text' => '#D97706', 'label' => 'Confirmed',  'icon' => 'check'],
                    'shipped'    => ['bg' => '#F3E8FF', 'text' => '#6B21A8', 'label' => 'Shipped',    'icon' => 'truck'],
                    'delivered'  => ['bg' => '#D4F3EA', 'text' => '#067D62', 'label' => 'Delivered',  'icon' => 'check-circle'],
                    'cancelled'  => ['bg' => '#F3F3F3', 'text' => '#565959', 'label' => 'Cancelled',  'icon' => 'x-circle'],
                ];
                $st = $statusConfig[$order->order_status] ?? $statusConfig['pending'];
            @endphp

            <div class="order-row px-4 py-4"
                 data-status="{{ $order->order_status }}"
                 data-date="{{ $order->created_at->timestamp }}"
                 data-price="{{ $order->total_price }}"
                 data-search="{{ strtolower($order->user->name . ' ' . $order->id) }}">

                <!-- Mobile layout -->
                <div class="md:hidden space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[14px] font-bold text-amz-text">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-[12px] text-amz-text-sec">{{ $order->created_at->format('M d, Y · h:i A') }}</p>
                        </div>
                        <span class="status-badge" style="background-color:{{ $st['bg'] }}; color:{{ $st['text'] }}">
                            <i data-lucide="{{ $st['icon'] }}" class="w-3.5 h-3.5"></i>
                            {{ $st['label'] }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 text-[13px]">
                        <div class="w-8 h-8 bg-amz-page rounded-full flex items-center justify-center border border-amz-border">
                            <i data-lucide="user" class="w-4 h-4 text-amz-text-sec"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-amz-text">{{ $order->user->name }}</p>
                            <p class="text-amz-text-sec text-[11px]">{{ $order->user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[13px] text-amz-text-sec">{{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }} · <strong class="text-amz-text">${{ number_format($order->total_price, 2) }}</strong></span>
                        @if($order->payment_status === 'paid')
                            <span class="text-[12px] font-bold text-amz-green">✓ Paid</span>
                        @else
                            <span class="text-[12px] font-bold text-amz-orange">⏳ Unpaid</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" class="flex gap-2 flex-1">
                            @csrf @method('PUT')
                            <select name="order_status" class="amz-select flex-1 px-2 py-1.5 text-[12px]">
                            @foreach(['pending','processing','confirmed','shipped','delivered','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->order_status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                            </select>
                            <button type="submit" class="update-btn px-3 py-1.5">Update</button>
                        </form>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="view-btn px-3 py-1.5 flex items-center gap-1">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>

                <!-- Desktop layout -->
                <div class="hidden md:grid grid-cols-[2fr_2fr_1.5fr_1fr_1fr_1.5fr_auto] gap-4 items-center">

                    <!-- Order Info -->
                    <div>
                        <p class="text-[13px] font-bold text-amz-text">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-[11px] text-amz-text-sec mt-0.5">{{ $order->created_at->format('M d, Y') }}</p>
                        <p class="text-[11px] text-amz-text-weak">{{ $order->created_at->format('h:i A') }}</p>
                    </div>

                    <!-- Customer -->
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 bg-amz-page rounded-full flex items-center justify-center border border-amz-border flex-shrink-0">
                            <i data-lucide="user" class="w-4 h-4 text-amz-text-sec"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[13px] font-semibold text-amz-text truncate">{{ $order->user->name }}</p>
                            <p class="text-[11px] text-amz-text-sec truncate">{{ $order->user->email }}</p>
                        </div>
                    </div>

                    <!-- Items / Total -->
                    <div>
                        <p class="text-[13px] font-bold text-amz-text">${{ number_format($order->total_price, 2) }}</p>
                        <p class="text-[11px] text-amz-text-sec mt-0.5">{{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}</p>
                    </div>

                    <!-- Payment -->
                    <div>
                        @if($order->payment_status === 'paid')
                            <span class="text-[12px] font-bold text-amz-green flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Paid
                            </span>
                        @else
                            <span class="text-[12px] font-bold text-amz-orange flex items-center gap-1">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i> Unpaid
                            </span>
                        @endif
                        <p class="text-[11px] text-amz-text-weak mt-0.5">{{ ucfirst($order->payment_method ?? '—') }}</p>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        <span class="status-badge" style="background-color:{{ $st['bg'] }}; color:{{ $st['text'] }}">
                            <i data-lucide="{{ $st['icon'] }}" class="w-3.5 h-3.5"></i>
                            {{ $st['label'] }}
                        </span>
                    </div>

                    <!-- Update Status -->
                    <div>
                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" class="flex gap-1.5">
                            @csrf @method('PUT')
                            <select name="order_status" class="amz-select px-2 py-1.5 text-[12px] flex-1">
                                @foreach(['pending','processing','confirmed','shipped','delivered','cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->order_status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="update-btn px-3 py-1.5 flex items-center gap-1 whitespace-nowrap">
                                <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                Save
                            </button>
                        </form>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="view-btn px-3 py-1.5 flex items-center gap-1">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            <span>View</span>
                        </a>
                        @if(!in_array($order->order_status, ['delivered','cancelled']))
                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" onsubmit="return confirm('Cancel this order?')">
                            @csrf @method('PUT')
                            <input type="hidden" name="order_status" value="cancelled">
                            <button type="submit" class="cancel-btn px-2.5 py-1.5 flex items-center" title="Cancel Order">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

            </div>
            @endforeach

        </div>

        <!-- ═══════════ PAGINATION ═══════════ -->
        @if(method_exists($orders, 'links'))
        <div class="bg-white rounded-lg border border-amz-border px-4 py-3">
            {{ $orders->links() }}
        </div>
        @endif

    </div>

@endsection
@push('scripts')
    <script>
        // ═════════ FILTER BY STATUS ═════════
        function filterOrders(status) {
            // Update tabs
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
                if (tab.dataset.status === status) tab.classList.add('active');
            });

            // Show/hide rows
            document.querySelectorAll('.order-row').forEach(row => {
                const show = status === 'all' || row.dataset.status === status;
                row.style.display = show ? '' : 'none';
            });

            // Update stat cards highlight
            document.querySelectorAll('.stat-card').forEach((card, i) => {
                const keys = ['all','pending','confirmed','processing','shipped','delivered'];
                card.style.opacity = (status === keys[i] || status === 'all') ? '1' : '0.55';
            });
        }

        // ═════════ LIVE SEARCH ═════════
        function liveSearch() {
            const term = document.getElementById('orderSearch').value.toLowerCase().trim();
            document.querySelectorAll('.order-row').forEach(row => {
                const text = row.dataset.search || row.textContent.toLowerCase();
                row.style.display = (!term || text.includes(term)) ? '' : 'none';
            });
        }

        // ═════════ SORT ORDERS ═════════
        function handleSort(value) {
            const rows = Array.from(document.querySelectorAll('.order-row'));
            if (!rows.length) return;
            const parent = rows[0].parentNode;

            rows.sort((a, b) => {
                switch(value) {
                    case 'latest':  return b.dataset.date - a.dataset.date;
                    case 'oldest':  return a.dataset.date - b.dataset.date;
                    case 'highest': return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                    case 'lowest':  return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                    default: return 0;
                }
            });

            // Re-insert in sorted order (after the header row)
            const header = parent.querySelector('.grid.grid-cols-\\[2fr');
            rows.forEach(row => parent.appendChild(row));
        }

        // ═════════ INIT ═════════
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            filterOrders('all');
        });
    </script>
    @endpush


