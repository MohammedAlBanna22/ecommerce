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

        .sidebar-section { border-bottom: 1px solid #E7E7E7; }
        .sidebar-section:last-child { border-bottom: none; }

        .filter-radio { appearance: none; width: 16px; height: 16px; border: 2px solid #888; border-radius: 50%; cursor: pointer; transition: all 100ms; position: relative; }
        .filter-radio:checked { border-color: #E77600; }
        .filter-radio:checked::after { content: ''; position: absolute; top: 3px; left: 3px; width: 6px; height: 6px; background: #E77600; border-radius: 50%; }

        .order-card { transition: box-shadow 200ms ease; border: 1px solid #DDD; border-radius: 8px; }
        .order-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

        .status-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 4px; font-size: 13px; font-weight: 600; }

        .order-item-image { transition: transform 300ms ease; }
        .order-item-image:hover { transform: scale(1.03); }

        .step-indicator { position: relative; display: flex; align-items: center; flex-direction: column; flex: 1; }
        .step-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; }
        .step-line { position: absolute; top: 16px; left: 50%; width: 100%; height: 2px; transform: translateY(-50%); }

        .mobile-sidebar { transform: translateX(-100%); transition: transform 300ms ease; }
        .mobile-sidebar.open { transform: translateX(0); }

        .nav-link { transition: all 100ms; border: 1px solid transparent; border-radius: 3px; }
        .nav-link:hover { border-color: white; }

        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
    @endpush




@section('content')


    <!-- ═══════════ BREADCRUMB ═══════════ -->
    <div class="bg-white border-b border-amz-border">
        <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-2">
            <nav class="flex items-center gap-1 text-[12px]">
                <a href="{{ route('home') }}" class="text-amz-blue hover:text-amz-link-hover hover:underline">{{ config('app.name', 'MyShop') }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
                <span class="text-amz-text font-medium">Your Orders</span>
            </nav>
        </div>
    </div>

    <!-- ═══════════ MAIN CONTENT ═══════════ -->
    <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-6">

        <!-- ═══════════ HEADER SECTION ═══════════ -->
        <div class="bg-white rounded-lg border border-amz-border px-4 py-4 mb-6">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-[28px] font-bold text-amz-text mb-1">Your Orders</h1>
                    <p class="text-[14px] text-amz-text-sec">{{ $orders->count() }} order{{ $orders->count() !== 1 ? 's' : '' }} total</p>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-[13px] text-amz-text-sec font-medium">Sort by:</label>
                    <select id="sortBy" onchange="handleSort(this.value)" class="amz-input px-3 py-2 rounded text-[13px] bg-white cursor-pointer">
                        <option value="latest">Latest Orders</option>
                        <option value="oldest">Oldest Orders</option>
                        <option value="highest">Highest Price</option>
                        <option value="lowest">Lowest Price</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- ═══════════ FILTER TABS ═══════════ -->
        <div class="bg-white rounded-lg border border-amz-border px-4 py-2 mb-6 flex gap-1 overflow-x-auto">
            <button onclick="filterOrders('all')" class="status-filter px-4 py-2 text-[13px] font-medium whitespace-nowrap rounded hover:bg-amz-page transition-colors" data-status="all">
                All Orders
            </button>
            <button onclick="filterOrders('pending')" class="status-filter px-4 py-2 text-[13px] font-medium whitespace-nowrap rounded hover:bg-amz-page transition-colors" data-status="pending">
                Pending
            </button>
            <button onclick="filterOrders('processing')" class="status-filter px-4 py-2 text-[13px] font-medium whitespace-nowrap rounded hover:bg-amz-page transition-colors" data-status="processing">
                Processing
            </button>
            <button onclick="filterOrders('shipped')" class="status-filter px-4 py-2 text-[13px] font-medium whitespace-nowrap rounded hover:bg-amz-page transition-colors" data-status="shipped">
                Shipped
            </button>
            <button onclick="filterOrders('delivered')" class="status-filter px-4 py-2 text-[13px] font-medium whitespace-nowrap rounded hover:bg-amz-page transition-colors" data-status="delivered">
                Delivered
            </button>
        </div>

        <!-- ═══════════ ORDERS LIST ═══════════ -->
        @forelse($orders as $order)
        <div class="order-card bg-amz-card mb-4 p-4" data-status="{{ $order->order_status }}" data-date="{{ $order->created_at->timestamp }}" data-price="{{ $order->total_price }}">

            <!-- Order Header -->
            <div class="mb-4 pb-4 border-b border-amz-border">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-[16px] font-bold text-amz-text">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h3>
                            @php
                                $statusConfig = [
                                    'pending' => ['bg' => '#FEE5E5', 'text' => '#C40C0C', 'label' => 'Pending', 'icon' => 'clock'],
                                    'processing' => ['bg' => '#E8F4FF', 'text' => '#0066CC', 'label' => 'Processing', 'icon' => 'loader'],
                                    'shipped' => ['bg' => '#E8F4FF', 'text' => '#0066CC', 'label' => 'Shipped', 'icon' => 'truck'],
                                    'delivered' => ['bg' => '#D4F3EA', 'text' => '#067D62', 'label' => 'Delivered', 'icon' => 'check-circle'],
                                    'cancelled' => ['bg' => '#F3F3F3', 'text' => '#565959', 'label' => 'Cancelled', 'icon' => 'x-circle'],
                                ];
                                $st = $statusConfig[$order->order_status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="status-badge" style="background-color: {{ $st['bg'] }}; color: {{ $st['text'] }};">
                                <i data-lucide="{{ $st['icon'] }}" class="w-4 h-4"></i>
                                {{ $st['label'] }}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 flex-wrap text-[13px] text-amz-text-sec">
                            <span>📅 Placed {{ $order->created_at->format('M d, Y') }}</span>
                            <span>💵 Total: <strong class="text-amz-text">${{ number_format($order->total_price, 2) }}</strong></span>
                            <span>📍 {{ $order->address?->city ?? 'Default Address' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('orders.show', $order) }}" class="amz-btn-cart px-6 py-2.5 text-[13px] font-bold whitespace-nowrap flex items-center gap-1.5">
                        View Details
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>

            <!-- Status Progress -->
            @php
                $statusSteps = [
                    'pending' => 1,
                    'processing' => 2,
                    'shipped' => 3,
                    'delivered' => 4,
                    'cancelled' => 0,
                ];
                $currentStep = $statusSteps[$order->order_status] ?? 0;
            @endphp

            @if($order->order_status !== 'cancelled')
            <div class="mb-4 pb-4 border-b border-amz-border">
                <p class="text-[12px] text-amz-text-sec font-bold mb-3 uppercase">Order Progress</p>
                <div class="flex items-center gap-2 relative">
                    @for($i = 1; $i <= 4; $i++)
                        <div class="step-indicator">
                            <div class="step-dot {{ $i <= $currentStep ? 'bg-amz-orange text-white' : 'bg-amz-border text-amz-text-weak' }}">
                                @if($i <= $currentStep)
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                @else
                                    {{ $i }}
                                @endif
                            </div>
                            <p class="text-[12px] text-amz-text-sec mt-2 text-center">
                                @switch($i)
                                    @case(1) Order Placed @break
                                    @case(2) Processing @break
                                    @case(3) Shipped @break
                                    @case(4) Delivered @break
                                @endswitch
                            </p>
                        </div>
                        @if($i < 4)
                            <div class="step-line {{ $i < $currentStep ? 'bg-amz-orange' : 'bg-amz-border' }} h-0.5 mx-1 flex-1 -top-4"></div>
                        @endif
                    @endfor
                </div>
            </div>
            @endif

            <!-- Order Items -->
            <div class="mb-4 pb-4 border-b border-amz-border">
                <p class="text-[13px] font-bold text-amz-text mb-3">{{ $order->items->count() }} Item{{ $order->items->count() !== 1 ? 's' : '' }}</p>
                <div class="space-y-3">
                    @foreach($order->items->take(2) as $item)
                    <div class="flex gap-3 items-start">
                        <!-- Product Image -->
                        <div class="w-[80px] h-[80px] bg-amz-page rounded border border-amz-border flex-shrink-0 flex items-center justify-center overflow-hidden">
                            @if($item->product && $item->product->image_url)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="order-item-image w-full h-full object-contain p-2">
                            @else
                                <div class="flex flex-col items-center justify-center text-amz-text-weak">
                                    <i data-lucide="image" class="w-6 h-6 mb-1"></i>
                                    <span class="text-[10px]">No image</span>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[13px] font-medium text-amz-text line-clamp-2 mb-1">
                                {{ $item->product->name ?? 'Product Unavailable' }}
                            </h4>
                            <div class="flex items-center gap-2 text-[12px] text-amz-text-sec mb-1">
                                <span>Qty: <strong>{{ $item->quantity }}</strong></span>
                                <span class="text-amz-text-weak">|</span>
                                <span class="font-bold text-amz-text">${{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                            @if($order->order_status === 'delivered')
                            <button class="text-[12px] text-amz-blue hover:text-amz-link-hover hover:underline font-medium">✍️ Write a review</button>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    @if($order->items->count() > 2)
                    <div class="pt-2 border-t border-amz-border">
                        <a href="{{ route('orders.show', $order) }}" class="text-[12px] text-amz-blue hover:text-amz-link-hover hover:underline font-medium">
                            + {{ $order->items->count() - 2 }} more item{{ $order->items->count() - 2 !== 1 ? 's' : '' }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Footer Info & Actions -->
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-4 text-[12px] text-amz-text-sec">
                    <div class="flex items-center gap-1">
                        @php
                            $methodIcons = ['stripe' => '💳', 'cod' => '💰', 'paypal' => '🅿️'];
                        @endphp
                        <span>{{ $methodIcons[$order->payment_method] ?? '💳' }}</span>
                        <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    </div>
                    <span class="text-amz-text-weak">|</span>
                    <div class="flex items-center gap-1">
                        @if($order->payment_status === 'paid')
                            <span class="text-amz-green font-bold">✓ Paid</span>
                        @else
                            <span class="text-amz-orange font-bold">⏳ Unpaid</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if($order->order_status === 'shipped')
                    <button class="border border-amz-border hover:border-amz-text rounded-full px-4 py-1.5 text-[12px] font-medium text-amz-text hover:bg-amz-page transition-colors flex items-center gap-1">
                        <i data-lucide="truck" class="w-3.5 h-3.5"></i> Track
                    </button>
                    @endif

                    @if($order->order_status === 'delivered')
                    <button class="border border-amz-border hover:border-amz-text rounded-full px-4 py-1.5 text-[12px] font-medium text-amz-text hover:bg-amz-page transition-colors flex items-center gap-1">
                        <i data-lucide="redo-2" class="w-3.5 h-3.5"></i> Buy Again
                    </button>
                    @endif

                    @if(!in_array($order->order_status, ['delivered', 'cancelled']))
                    <button class="border border-amz-deal hover:bg-amz-deal-bg rounded-full px-4 py-1.5 text-[12px] font-medium text-amz-deal hover:text-amz-deal transition-colors flex items-center gap-1">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i> Cancel
                    </button>
                    @endif
                </div>
            </div>

        </div>
        @empty

        <!-- Empty State -->
        <div class="bg-white rounded-lg border border-amz-border p-12 text-center">
            <div class="w-24 h-24 bg-amz-page rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="package-x" class="w-10 h-10 text-amz-text-weak"></i>
            </div>
            <h2 class="text-xl font-bold text-amz-text mb-2">No orders yet</h2>
            <p class="text-[14px] text-amz-text-sec mb-6 max-w-md mx-auto">You haven't placed any orders. Start shopping now to discover amazing products!</p>
            <a href="{{ route('products.index') }}" class="amz-btn-cart inline-flex items-center gap-1.5 px-8 py-3 text-[14px] font-bold">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i> Start Shopping
            </a>
        </div>

        @endforelse

    </div>

 @endsection
 @push('scripts')



    <script>
        // ═════════ FILTER BY STATUS ═════════
        function filterOrders(status) {
            document.querySelectorAll('.status-filter').forEach(btn => {
                btn.classList.remove('bg-amz-dark', 'text-white');
                btn.classList.add('text-amz-text');
            });

            const activeBtn = document.querySelector(`[data-status="${status}"]`);
            activeBtn.classList.add('bg-amz-dark', 'text-white');
            activeBtn.classList.remove('text-amz-text');

            document.querySelectorAll('.order-card').forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // ═════════ SEARCH ORDERS ═════════
        function searchOrders() {
            const searchTerm = document.getElementById('orderSearch').value.toLowerCase();
            document.querySelectorAll('.order-card').forEach(card => {
                const orderNum = card.textContent.toLowerCase();
                card.style.display = orderNum.includes(searchTerm) ? '' : 'none';
            });
        }

        // ═════════ SORT ORDERS ═════════
        function handleSort(value) {
            const container = document.querySelector('[class*="max-w"]');
            const cards = Array.from(document.querySelectorAll('.order-card'));

            cards.sort((a, b) => {
                switch(value) {
                    case 'latest':
                        return b.dataset.date - a.dataset.date;
                    case 'oldest':
                        return a.dataset.date - b.dataset.date;
                    case 'highest':
                        return b.dataset.price - a.dataset.price;
                    case 'lowest':
                        return a.dataset.price - b.dataset.price;
                    default:
                        return 0;
                }
            });

            cards.forEach(card => {
                card.parentNode.insertBefore(card, card.parentNode.children[cards.indexOf(card) + 2]);
            });
        }

        // ═════════ INIT ═════════
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            // Set first filter button as active
            const firstBtn = document.querySelector('[data-status="all"]');
            if (firstBtn) {
                firstBtn.classList.add('bg-amz-dark', 'text-white');
                firstBtn.classList.remove('text-amz-text');
            }
        });
    </script>
 @endpush

