@extends('layouts.app')

@push('scripts')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'Arial', 'sans-serif'] },
                    colors: {
                        amz: {
                            dark: '#131921',
                            nav: '#232F3E',
                            navHover: '#37475A',
                            orange: '#FF9900',
                            'orange-light': '#FFA41C',
                            'orange-btn': '#FFD814',
                            'orange-btn-hover': '#F7CA00',
                            'orange-border': '#F3A847',
                            blue: '#007185',
                            'blue-hover': '#C7511F',
                            link: '#0F1111',
                            'link-hover': '#C7511F',
                            star: '#FFA41C',
                            prime: '#007185',
                            badge: '#CC0C39',
                            green: '#067D62',
                            'green-light': '#D4F3EA',
                            page: '#EAEDED',
                            card: '#FFFFFF',
                            border: '#DDD',
                            'border-light': '#E7E7E7',
                            text: '#0F1111',
                            'text-sec': '#565959',
                            'text-tri': '#737373',
                            'text-weak': '#979797',
                            deal: '#CC0C39',
                            'deal-bg': '#FDEEE8',
                            flag: '#232F3E',
                        }
                    }
                }
            }
        }
    </script>
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

        .info-card { transition: box-shadow 200ms ease; border: 1px solid #DDD; border-radius: 8px; }
        .info-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

        .status-badge { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 4px; font-size: 13px; font-weight: 600; }

        .step-indicator { position: relative; display: flex; align-items: center; flex-direction: column; flex: 1; }
        .step-dot { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px; }
        .step-line { position: absolute; top: 20px; left: 50%; width: 100%; height: 3px; }

        .nav-link { transition: all 100ms; border: 1px solid transparent; border-radius: 3px; }
        .nav-link:hover { border-color: white; }

        .product-row { transition: background 200ms ease; }
        .product-row:hover { background: #FAFAFA; }

        .timeline-item { display: flex; gap: 1rem; position: relative; padding-bottom: 2rem; }
        .timeline-dot { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 16px; flex-shrink-0; }
        .timeline-content { flex: 1; }
        .timeline-line { position: absolute; left: 19px; top: 40px; width: 2px; height: 100%; }
        .timeline-item:last-child .timeline-line { display: none; }
    </style>
@endpush()



@section('content')
    <!-- ═══════════ BREADCRUMB ═══════════ -->
    <div class="bg-white border-b border-amz-border">
        <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-2">
            <nav class="flex items-center gap-1 text-[12px]">
                <a href="{{ route('home') }}" class="text-amz-blue hover:text-amz-link-hover hover:underline">{{ config('app.name', 'MyShop') }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
                <a href="{{ route('orders.index') }}" class="text-amz-blue hover:text-amz-link-hover hover:underline">Your Orders</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
                <span class="text-amz-text font-medium">Order Details</span>
            </nav>
        </div>
    </div>

    <!-- ═══════════ MAIN CONTENT ═══════════ -->
    <div class="bg-amz-page min-h-screen">
        <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-6">

            <!-- Back Button -->
            <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-1.5 mb-6 px-4 py-2.5 bg-amz-dark text-white rounded-lg hover:bg-amz-nav transition-colors text-[13px] font-medium">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Orders
            </a>

            <!-- ═══════════ HEADER SECTION ═══════════ -->
            <div class="bg-white rounded-lg border border-amz-border px-6 py-5 mb-6">
                <div class="flex items-start justify-between gap-4 flex-wrap mb-3">
                    <div>
                        <h1 class="text-[28px] font-bold text-amz-text">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>
                        <p class="text-[13px] text-amz-text-sec mt-2">
                            📅 Placed on {{ $order->created_at->format('M d, Y') }} at {{ $order->created_at->format('H:i') }}
                        </p>
                    </div>

                    @php
                        $statusConfig = [
                            'pending' => ['bg' => '#FEE5E5', 'text' => '#C40C0C', 'label' => 'Pending'],
                            'confirmed' => ['bg' => '#E8F4FF', 'text' => '#0066CC', 'label' => 'Confirmed'],
                            'processing' => ['bg' => '#E8F4FF', 'text' => '#0066CC', 'label' => 'Processing'],
                            'shipped' => ['bg' => '#E8F4FF', 'text' => '#0066CC', 'label' => 'Shipped'],
                            'delivered' => ['bg' => '#D4F3EA', 'text' => '#067D62', 'label' => 'Delivered'],
                            'cancelled' => ['bg' => '#F3F3F3', 'text' => '#565959', 'label' => 'Cancelled'],
                        ];
                        $st = $statusConfig[$order->order_status] ?? $statusConfig['pending'];
                    @endphp

                    <span class="px-5 py-2.5 rounded-full text-[13px] font-bold" style="background-color: {{ $st['bg'] }}; color: {{ $st['text'] }};">
                        {{ $st['label'] }}
                    </span>
                </div>
            </div>

            <!-- ═══════════ STATUS PROGRESS ═══════════ -->
            @php
                $statusSteps = [
                    'pending' => 1,
                    'confirmed' => 2,
                    'processing' => 2,
                    'shipped' => 3,
                    'delivered' => 4,
                    'cancelled' => 0,
                ];
                $currentStep = $statusSteps[$order->order_status] ?? 0;
            @endphp

            @if($order->order_status !== 'cancelled')
            <div class="bg-white rounded-lg border border-amz-border px-6 py-6 mb-6">
                <h2 class="text-[16px] font-bold text-amz-text mb-6">Order Progress</h2>
                <div class="flex items-center gap-2 relative">
                    @for($i = 1; $i <= 4; $i++)
                        <div class="step-indicator">
                            <div class="step-dot {{ $i <= $currentStep ? 'bg-amz-orange text-white' : 'bg-amz-border text-amz-text-weak' }}">
                                @if($i <= $currentStep)
                                    ✓
                                @else
                                    {{ $i }}
                                @endif
                            </div>
                            <p class="text-[12px] text-amz-text-sec mt-3 text-center whitespace-nowrap">
                                @switch($i)
                                    @case(1) Order Placed @break
                                    @case(2) Processing @break
                                    @case(3) Shipped @break
                                    @case(4) Delivered @break
                                @endswitch
                            </p>
                        </div>
                        @if($i < 4)
                            <div class="step-line {{ $i < $currentStep ? 'bg-amz-orange' : 'bg-amz-border' }} flex-1 h-1 -top-8"></div>
                        @endif
                    @endfor
                </div>
            </div>
            @endif

            <!-- ═══════════ INFO CARDS GRID ═══════════ -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">

                <!-- Order Summary -->
                <div class="info-card bg-white rounded-lg p-5">
                    <h3 class="text-[14px] font-bold text-amz-text mb-4 uppercase tracking-wide">Order Summary</h3>
                    <div class="space-y-3 text-[13px]">
                        <div class="flex justify-between">
                            <span class="text-amz-text-sec">Subtotal:</span>
                            <span class="text-amz-text font-medium">${{ number_format($order->total_price - ($order->shipping_cost ?? 0) + ($order->discount ?? 0), 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-amz-text-sec">Shipping:</span>
                            <span class="text-amz-text font-medium">${{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                        </div>
                        @if($order->discount && $order->discount > 0)
                        <div class="flex justify-between text-amz-green">
                            <span>Discount:</span>
                            <span class="font-medium">-${{ number_format($order->discount, 2) }}</span>
                        </div>
                        @endif
                        <div class="border-t border-amz-border pt-3 flex justify-between">
                            <span class="text-amz-text font-bold">Total:</span>
                            <span class="text-amz-text font-bold text-[16px]">${{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="info-card bg-white rounded-lg p-5">
                    <h3 class="text-[14px] font-bold text-amz-text mb-4 uppercase tracking-wide">Payment</h3>
                    <div class="space-y-3 text-[13px]">
                        <div>
                            <p class="text-amz-text-sec mb-1">Method</p>
                            <p class="text-amz-text font-medium">
                                @php
                                    $methodLabels = [
                                        'stripe' => '💳 Credit Card (Stripe)',
                                        'paypal' => '🅿️ PayPal',
                                        'cod' => '💰 Cash on Delivery'
                                    ];
                                @endphp
                                {{ $methodLabels[$order->payment_method] ?? ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                            </p>
                        </div>
                        <div class="border-t border-amz-border pt-3">
                            <p class="text-amz-text-sec mb-1">Status</p>
                            <p class="text-[13px] font-bold flex items-center gap-1.5">
                                @if($order->payment_status === 'paid')
                                    <span class="w-2 h-2 bg-amz-green rounded-full"></span>
                                    <span class="text-amz-green">Paid</span>
                                @else
                                    <span class="w-2 h-2 bg-amz-orange rounded-full animate-pulse"></span>
                                    <span class="text-amz-orange">Pending Payment</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Items Summary -->
                <div class="info-card bg-white rounded-lg p-5">
                    <h3 class="text-[14px] font-bold text-amz-text mb-4 uppercase tracking-wide">Items</h3>
                    <div class="space-y-3 text-[13px]">
                        <div>
                            <p class="text-amz-text-sec mb-1">Total Items</p>
                            <p class="text-[24px] font-bold text-amz-orange">{{ $order->items->count() }}</p>
                        </div>
                        <div class="border-t border-amz-border pt-3">
                            <p class="text-amz-text-sec mb-1">Total SKUs</p>
                            <p class="text-amz-text font-medium">{{ $order->items->sum('quantity') }} units</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ═══════════ CUSTOMER & SHIPPING ═══════════ -->
            <div class="grid md:grid-cols-2 gap-4 mb-6">

                <!-- Customer Info -->
                <div class="info-card bg-white rounded-lg p-5">
                    <h3 class="text-[14px] font-bold text-amz-text mb-4 uppercase tracking-wide">Customer</h3>
                    <div class="space-y-2 text-[13px]">
                        <p class="text-amz-text font-bold">{{ $order->user->name }}</p>
                        <p class="text-amz-blue hover:text-amz-link-hover">{{ $order->user->email }}</p>
                        @if($order->user->phone ?? false)
                        <p class="text-amz-text-sec">{{ $order->user->phone }}</p>
                        @endif
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="info-card bg-white rounded-lg p-5">
                    <h3 class="text-[14px] font-bold text-amz-text mb-4 uppercase tracking-wide">Shipping Address</h3>
                    <div class="space-y-1 text-[13px] text-amz-text">
                        <p class="font-medium">{{ $order->address->street ?? 'N/A' }}</p>
                        <p>{{ $order->address->city ?? 'N/A' }}, {{ $order->address->state ?? '' }} {{ $order->address->zip ?? '' }}</p>
                        <p class="text-amz-text-sec">{{ $order->address->country ?? 'N/A' }}</p>
                    </div>
                </div>

            </div>

            <!-- ═══════════ ORDER ITEMS TABLE ═══════════ -->
            <div class="bg-white rounded-lg border border-amz-border mb-6 overflow-hidden">
                <div class="px-6 py-4 border-b border-amz-border bg-amz-page">
                    <h2 class="text-[16px] font-bold text-amz-text">Order Items ({{ $order->items->count() }})</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-[13px]">
                        <thead class="border-b border-amz-border">
                            <tr>
                                <th class="px-6 py-3 text-left font-bold text-amz-text">Product</th>
                                <th class="px-6 py-3 text-center font-bold text-amz-text">Price</th>
                                <th class="px-6 py-3 text-center font-bold text-amz-text">Qty</th>
                                <th class="px-6 py-3 text-right font-bold text-amz-text">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr class="product-row border-b border-amz-border-light">
                                <td class="px-6 py-4">
                                    <div class="flex gap-3 items-start">
                                        <div class="w-14 h-14 bg-amz-page rounded border border-amz-border flex-shrink-0 flex items-center justify-center overflow-hidden">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain p-1">
                                            @else
                                                <i data-lucide="image" class="w-6 h-6 text-amz-text-weak"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-amz-text font-medium line-clamp-2">{{ $item->product->name ?? 'Product Unavailable' }}</p>
                                            @if($item->product && $item->product->category ?? false)
                                            <p class="text-[12px] text-amz-text-weak mt-1">{{ $item->product->category->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-amz-text font-medium">${{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 text-center text-amz-text font-medium">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right">
                                    <p class="text-amz-text font-bold">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ═══════════ TRACKING TIMELINE ═══════════ -->
            @if($order->histories && $order->histories->count() > 0)
            <div class="bg-white rounded-lg border border-amz-border p-6 mb-6">
                <h2 class="text-[16px] font-bold text-amz-text mb-6">Order Timeline</h2>

                <div class="space-y-1 relative">
                    @foreach($order->histories as $index => $history)
                    @php
                        $historyStatusConfig = [
                            'pending' => ['color' => '#FFA41C', 'label' => 'Order Placed'],
                            'confirmed' => ['color' => '#0066CC', 'label' => 'Order Confirmed'],
                            'processing' => ['color' => '#4B5563', 'label' => 'Processing'],
                            'shipped' => ['color' => '#007185', 'label' => 'Shipped'],
                            'delivered' => ['color' => '#067D62', 'label' => 'Delivered'],
                            'cancelled' => ['color' => '#C40C0C', 'label' => 'Cancelled'],
                        ];
                        $hs = $historyStatusConfig[$history->status] ?? ['color' => '#565959', 'label' => ucfirst($history->status)];
                    @endphp

                    <div class="timeline-item">
                        @if($index < $order->histories->count() - 1)
                        <div class="timeline-line" style="background-color: {{ $hs['color'] }};"></div>
                        @endif
                        <div class="timeline-dot" style="background-color: {{ $hs['color'] }};">
                            ✓
                        </div>
                        <div class="timeline-content">
                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                <p class="font-bold text-amz-text">{{ $hs['label'] }}</p>
                                <span class="text-[12px] text-amz-text-weak">{{ $history->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            @if($history->notes ?? false)
                            <p class="text-[13px] text-amz-text-sec mt-1">{{ $history->notes }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- ═══════════ ACTIONS ═══════════ -->
            <div class="flex items-center gap-2 flex-wrap">
                @if($order->order_status === 'shipped')
                <button class="inline-flex items-center gap-1.5 px-6 py-2.5 border border-amz-border rounded-lg text-[13px] font-bold text-amz-text hover:bg-amz-page transition-colors">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                    Track Shipment
                </button>
                @endif

                @if($order->order_status === 'delivered')
                <button class="inline-flex items-center gap-1.5 px-6 py-2.5 border border-amz-border rounded-lg text-[13px] font-bold text-amz-text hover:bg-amz-page transition-colors">
                    <i data-lucide="star" class="w-4 h-4"></i>
                    Write a Review
                </button>

                <button class="inline-flex items-center gap-1.5 px-6 py-2.5 border border-amz-border rounded-lg text-[13px] font-bold text-amz-text hover:bg-amz-page transition-colors">
                    <i data-lucide="redo-2" class="w-4 h-4"></i>
                    Buy Again
                </button>
                @endif

                @if(!in_array($order->order_status, ['delivered', 'cancelled']))
                <button class="inline-flex items-center gap-1.5 px-6 py-2.5 border border-amz-deal rounded-lg text-[13px] font-bold text-amz-deal hover:bg-amz-deal-bg transition-colors">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                    Cancel Order
                </button>
                @endif
            </div>

        </div>
    </div>

   @endsection
   @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @endpsuh()

