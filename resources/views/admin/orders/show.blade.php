 @extends('layouts.app')




@push('styles')
    <style>
        * { -webkit-font-smoothing: antialiased; }
        ::-webkit-scrollbar { width: 8px; } ::-webkit-scrollbar-track { background: #f0f0f0; } ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }

        .amz-input  { border: 1px solid #DDD; border-radius: 6px; padding: 8px 12px; font-size: 13px; width: 100%; transition: border-color 100ms, box-shadow 100ms; font-family: 'Inter', sans-serif; color: #0F1111; }
        .amz-input:focus  { border-color: #E77600; box-shadow: 0 0 0 3px rgba(228,166,54,0.3); outline: none; }
        .amz-select { border: 1px solid #DDD; border-radius: 6px; background: white; padding: 8px 12px; font-size: 13px; transition: border-color 100ms, box-shadow 100ms; cursor: pointer; font-family: 'Inter', sans-serif; color: #0F1111; }
        .amz-select:focus { border-color: #E77600; box-shadow: 0 0 0 3px rgba(228,166,54,0.3); outline: none; }

        .update-btn { background: linear-gradient(to bottom, #FFE696, #FFD814); border: 1px solid #F3A847; border-radius: 6px; color: #0F1111; font-weight: 600; font-size: 13px; transition: background 100ms; cursor: pointer; }
        .update-btn:hover { background: linear-gradient(to bottom, #FFD814, #F7CA00); }
        .view-btn   { background: #232F3E; border-radius: 6px; color: white; font-size: 13px; font-weight: 600; transition: background 100ms; display: inline-flex; align-items: center; }
        .view-btn:hover { background: #37475A; }

        .nav-link { transition: all 100ms; border: 1px solid transparent; border-radius: 3px; }
        .nav-link:hover { border-color: white; }

        .status-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 1rem; border-radius: 4px; font-size: 13px; font-weight: 600; }
        .info-card    { border: 1px solid #DDD; border-radius: 8px; background: white; }
        .item-row     { border-bottom: 1px solid #E7E7E7; transition: background 150ms; }
        .item-row:last-child { border-bottom: none; }
        .item-row:hover { background: #F8F8F8; }
        .step-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 13px; flex-shrink: 0; }

        /* action buttons inside middle card */
        .action-btn {
            width: 100%;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            cursor: pointer;
            transition: filter 100ms;
            border: 1px solid transparent;
        }
        .action-btn:hover { filter: brightness(0.95); }

        .section-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #979797;
            margin-bottom: 8px;
        }

        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            padding: 5px 0;
        }
        .meta-row span:first-child { color: #565959; }
        .meta-row span:last-child  { font-weight: 600; color: #0F1111; }
    </style>
    @endpush

@section('custom-navigation')
<!-- ═══ SUB NAV ═══ -->
<nav class="bg-amz-nav sticky top-14 z-40">
    <div class="max-w-[1500px] mx-auto px-3 sm:px-4">
        <div class="flex items-center gap-0.5 h-11 text-white text-[13px]">
            <a href="{{ route('admin.dashboard') }}" class="nav-link px-2.5 py-1.5 flex items-center gap-1.5">
                <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i> Dashboard
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link px-2.5 py-1.5 font-bold border-b-2 border-amz-orange flex items-center gap-1.5">
                <i data-lucide="package" class="w-3.5 h-3.5"></i> Orders
            </a>
            <a href="{{ route('products.index') }}" class="nav-link px-2.5 py-1.5 flex items-center gap-1.5">
                <i data-lucide="box" class="w-3.5 h-3.5"></i> Products
            </a>
            <a href="{{ route('profile.update') }}" class="nav-link px-2.5 py-1.5 flex items-center gap-1.5">
                <i data-lucide="users" class="w-3.5 h-3.5"></i> Customers
            </a>
        </div>
    </div>
</nav>
@endsection

   @section('content')
<!-- ═══ BREADCRUMB ═══ -->
<div class="bg-white border-b border-amz-border">
    <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-2">
        <nav class="flex items-center gap-1 text-[12px]">
            <a href="{{ route('admin.dashboard') }}" class="text-amz-blue hover:underline">Admin</a>
            <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
            <a href="{{ route('admin.orders.index') }}" class="text-amz-blue hover:underline">Orders</a>
            <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
            <span class="text-amz-text font-medium">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
        </nav>
    </div>
</div>

<!-- ═══ MAIN ═══ -->
<div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-6">

    <!-- Page Header -->
    <div class="bg-white rounded-lg border border-amz-border px-4 py-4 mb-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-[26px] font-bold text-amz-text mb-1">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-[13px] text-amz-text-sec">Placed {{ $order->created_at->format('M d, Y · h:i A') }}</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
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
                <span class="status-badge" style="background-color:{{ $st['bg'] }}; color:{{ $st['text'] }}">
                    <i data-lucide="{{ $st['icon'] }}" class="w-4 h-4"></i>
                    {{ $st['label'] }}
                </span>
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center gap-1.5 border border-amz-border hover:border-amz-text rounded-full px-4 py-2 text-[13px] font-medium text-amz-text hover:bg-amz-page transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>

    <!-- Order Progress -->
    @if($order->order_status !== 'cancelled')
    @php
        $steps       = ['pending' => 1, 'processing' => 2, 'confirmed' => 3, 'shipped' => 4, 'delivered' => 5];
        $currentStep = $steps[$order->order_status] ?? 1;
        $stepLabels  = ['Placed', 'Processing', 'Confirmed', 'Shipped', 'Delivered'];
        $stepIcons   = ['shopping-bag', 'loader', 'check', 'truck', 'check-circle'];
    @endphp
    <div class="bg-white rounded-lg border border-amz-border px-6 py-5 mb-6">
        <p class="section-label mb-4">Order Progress</p>
        <div class="flex items-start">
            @for($i = 1; $i <= 5; $i++)
            <div class="flex-1 flex flex-col items-center relative">
                {{-- left line --}}
                @if($i > 1)
                <div class="absolute top-4 right-1/2 left-0 h-0.5 {{ $i <= $currentStep ? 'bg-amz-orange' : 'bg-amz-border' }}"></div>
                @endif
                {{-- right line --}}
                @if($i < 5)
                <div class="absolute top-4 left-1/2 right-0 h-0.5 {{ $i < $currentStep ? 'bg-amz-orange' : 'bg-amz-border' }}"></div>
                @endif
                <div class="step-dot z-10 {{ $i <= $currentStep ? 'bg-amz-orange text-white' : 'bg-amz-border text-amz-text-weak' }}">
                    @if($i <= $currentStep)
                        <i data-lucide="{{ $stepIcons[$i-1] }}" class="w-4 h-4"></i>
                    @else
                        {{ $i }}
                    @endif
                </div>
                <p class="text-[11px] text-amz-text-sec mt-2 text-center font-medium leading-tight">{{ $stepLabels[$i-1] }}</p>
            </div>
            @endfor
        </div>
    </div>
    @endif

    <!-- ═══ 3-COLUMN GRID ═══ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

        {{-- ─── CARD 1 : Customer ─── --}}
        <div class="info-card p-4">
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-amz-border">
                <div class="w-8 h-8 bg-amz-page rounded-full flex items-center justify-center border border-amz-border">
                    <i data-lucide="user" class="w-4 h-4 text-amz-text-sec"></i>
                </div>
                <h2 class="text-[15px] font-bold text-amz-text">Customer</h2>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="section-label">Name</p>
                    <p class="text-[13px] font-semibold text-amz-text">{{ $order->user->name }}</p>
                </div>
                <div>
                    <p class="section-label">Email</p>
                    <p class="text-[13px] text-amz-text">{{ $order->user->email }}</p>
                </div>
                @if($order->address)
                <div>
                    <p class="section-label">Shipping Address</p>
                    <p class="text-[13px] text-amz-text">{{ $order->address->street ?? '' }}, {{ $order->address->city ?? '' }}</p>
                    @if($order->address->state)
                    <p class="text-[12px] text-amz-text-sec">{{ $order->address->state }}</p>
                    @endif
                    <p class="text-[12px] text-amz-text-sec">{{ $order->address->country ?? '' }}</p>
                    @if($order->address->phone)
                    <p class="text-[12px] text-amz-text mt-1 flex items-center gap-1">
                        <i data-lucide="phone" class="w-3 h-3 text-amz-text-weak"></i>
                        {{ $order->address->phone }}
                    </p>
                    @endif
                </div>
                @endif
                <div class="pt-1">
                    <a href="{{ route('profile.update', $order->user->id) }}"
                       class="text-[12px] text-amz-blue hover:underline flex items-center gap-1">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i> View customer profile
                    </a>
                </div>
            </div>
        </div>

        {{-- ─── CARD 2 : Order Actions ─── --}}
        <div class="info-card p-4 flex flex-col gap-0">

            {{-- Header --}}
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-amz-border">
                <div class="w-8 h-8 bg-amz-page rounded-full flex items-center justify-center border border-amz-border">
                    <i data-lucide="settings-2" class="w-4 h-4 text-amz-text-sec"></i>
                </div>
                <h2 class="text-[15px] font-bold text-amz-text">Order Actions</h2>
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-2.5 mb-4">

                {{-- CONFIRM --}}
                @if($order->order_status === 'processing')
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="order_status" value="confirmed">
                    <button type="submit" class="action-btn" style="background:#FEF3C7; color:#D97706; border-color:#FCD34D;">
                        <i data-lucide="check" class="w-4 h-4"></i> Confirm Order
                    </button>
                </form>
                @endif

                {{-- SHIP --}}
                @if($order->order_status === 'confirmed')
                <form method="POST" action="{{ route('admin.orders.ship', $order->id) }}" class="space-y-2">
                    @csrf @method('PUT')
                    <div>
                        <p class="section-label">Carrier</p>
                        <select name="shipping_carrier" class="amz-select w-full" required>
                            <option value="">Select carrier...</option>
                            @foreach(['Aramex','DHL','FedEx','UPS','SMSA','Other'] as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <p class="section-label">Tracking Number</p>
                        <input type="text" name="tracking_number" placeholder="e.g. 1Z999AA10123456784" class="amz-input" required>
                    </div>
                    <button type="submit" class="action-btn mt-1" style="background:#F3E8FF; color:#6B21A8; border-color:#C4B5FD;">
                        <i data-lucide="truck" class="w-4 h-4"></i> Mark as Shipped
                    </button>
                </form>
                @endif

                {{-- DELIVER --}}
                @if($order->order_status === 'shipped')
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="order_status" value="delivered">
                    <button type="submit" class="action-btn" style="background:#D4F3EA; color:#067D62; border-color:#6EE7B7;">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Mark as Delivered
                    </button>
                </form>
                @endif

                {{-- COMPLETED --}}
                @if($order->order_status === 'delivered')
                <div class="action-btn" style="background:#D4F3EA; color:#067D62; border:1px solid #6EE7B7; pointer-events:none;">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Order Completed
                </div>
                @endif

                {{-- CANCEL --}}
                @if(!in_array($order->order_status, ['delivered','cancelled']))
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}"
                      onsubmit="return confirm('Cancel this order?')">
                    @csrf @method('PUT')
                    <input type="hidden" name="order_status" value="cancelled">
                    <button type="submit" class="action-btn" style="background:#FEE5E5; color:#C40C0C; border-color:#FCA5A5;">
                        <i data-lucide="x-circle" class="w-4 h-4"></i> Cancel Order
                    </button>
                </form>
                @endif

                {{-- CANCELLED STATE --}}
                @if($order->order_status === 'cancelled')
                <div class="action-btn" style="background:#F3F3F3; color:#565959; border:1px solid #DDD; pointer-events:none;">
                    <i data-lucide="x-circle" class="w-4 h-4"></i> Order Cancelled
                </div>
                @endif

            </div>

            {{-- Tracking Info (shown after shipping) --}}
            @if($order->tracking_number)
            <div class="pt-3 border-t border-amz-border">
                <p class="section-label">Shipping Info</p>
                <div class="bg-amz-page rounded-md px-3 py-2.5 space-y-1.5">
                    <div class="meta-row">
                        <span>Carrier</span>
                        <span>{{ $order->shipping_carrier }}</span>
                    </div>
                    <div class="meta-row">
                        <span>Tracking #</span>
                        <span class="font-mono text-amz-blue">{{ $order->tracking_number }}</span>
                    </div>
                    @if($order->shipped_at)
                    <div class="meta-row">
                        <span>Shipped</span>
                        <span>{{ $order->shipped_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Payment Info --}}
            <div class="pt-3 mt-auto border-t border-amz-border">
                <p class="section-label">Payment</p>
                <div class="bg-amz-page rounded-md px-3 py-2.5 space-y-1.5">
                    <div class="meta-row">
                        <span>Status</span>
                        @if($order->payment_status === 'paid')
                            <span class="flex items-center gap-1 text-amz-green">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Paid
                            </span>
                        @else
                            <span class="flex items-center gap-1 text-amz-orange">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i> Unpaid
                            </span>
                        @endif
                    </div>
                    <div class="meta-row">
                        <span>Method</span>
                        <span>{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? '—')) }}</span>
                    </div>
                </div>
            </div>

        </div>{{-- end CARD 2 --}}

        {{-- ─── CARD 3 : Order Summary ─── --}}
        <div class="info-card p-4 flex flex-col">
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-amz-border">
                <div class="w-8 h-8 bg-amz-page rounded-full flex items-center justify-center border border-amz-border">
                    <i data-lucide="receipt" class="w-4 h-4 text-amz-text-sec"></i>
                </div>
                <h2 class="text-[15px] font-bold text-amz-text">Order Summary</h2>
            </div>

            <div class="space-y-2.5 flex-1">
                <div class="meta-row">
                    <span class="text-amz-text-sec">Items ({{ $order->items->count() }})</span>
                    <span>${{ number_format($order->total_price, 2) }}</span>
                </div>
                @if(isset($order->discount) && $order->discount > 0)
                <div class="meta-row">
                    <span class="text-amz-text-sec">Discount</span>
                    <span class="text-amz-deal">-${{ number_format($order->discount, 2) }}</span>
                </div>
                @endif
                <div class="meta-row">
                    <span class="text-amz-text-sec">Shipping</span>
                    <span class="text-amz-green font-semibold">Free</span>
                </div>

                <div class="pt-3 border-t border-amz-border flex justify-between items-center">
                    <span class="text-[15px] font-bold text-amz-text">Order Total</span>
                    <span class="text-[22px] font-bold text-amz-text">${{ number_format($order->total_price, 2) }}</span>
                </div>
            </div>

            {{-- Invoice --}}
            <div class="mt-4 pt-4 border-t border-amz-border">
                <a href="{{ route('orders.invoice', $order->id) }}"
                   class="view-btn w-full py-2.5 justify-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Download Invoice
                </a>
            </div>
        </div>{{-- end CARD 3 --}}

    </div>{{-- end 3-col grid --}}

    <!-- ═══ ORDER HISTORY ═══ -->
    @if($order->histories && $order->histories->count())
    <div class="bg-white rounded-lg border border-amz-border overflow-hidden mb-6">
        <div class="px-4 py-4 border-b border-amz-border">
            <h2 class="text-[16px] font-bold text-amz-text flex items-center gap-2">
                <i data-lucide="clock" class="w-5 h-5 text-amz-text-sec"></i>
                Order History
            </h2>
        </div>
        <div class="px-4 py-4 space-y-3">
            @php
                $hConfig = [
                    'pending'    => ['color' => '#C40C0C', 'bg' => '#FEE5E5', 'icon' => 'clock'],
                    'processing' => ['color' => '#0066CC', 'bg' => '#E8F4FF', 'icon' => 'loader'],
                    'confirmed'  => ['color' => '#D97706', 'bg' => '#FEF3C7', 'icon' => 'check'],
                    'shipped'    => ['color' => '#6B21A8', 'bg' => '#F3E8FF', 'icon' => 'truck'],
                    'delivered'  => ['color' => '#067D62', 'bg' => '#D4F3EA', 'icon' => 'check-circle'],
                    'cancelled'  => ['color' => '#565959', 'bg' => '#F3F3F3', 'icon' => 'x-circle'],
                ];
            @endphp
            @foreach($order->histories->sortByDesc('created_at') as $history)
            @php $hc = $hConfig[$history->status] ?? $hConfig['pending']; @endphp
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                     style="background:{{ $hc['bg'] }}; color:{{ $hc['color'] }}">
                    <i data-lucide="{{ $hc['icon'] }}" class="w-4 h-4"></i>
                </div>
                <div class="flex-1 flex items-center justify-between gap-2 flex-wrap">
                    <span class="text-[13px] font-semibold" style="color:{{ $hc['color'] }}">
                        {{ ucfirst($history->status) }}
                    </span>
                    <span class="text-[11px] text-amz-text-weak">
                        {{ $history->created_at->format('M d, Y · h:i A') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- ═══ ORDER ITEMS ═══ -->
    <div class="bg-white rounded-lg border border-amz-border overflow-hidden">

        <div class="px-4 py-4 border-b border-amz-border">
            <h2 class="text-[16px] font-bold text-amz-text flex items-center gap-2">
                <i data-lucide="package" class="w-5 h-5 text-amz-text-sec"></i>
                Order Items
                <span class="text-[13px] font-normal text-amz-text-sec">
                    ({{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }})
                </span>
            </h2>
        </div>

        <!-- Column Headers -->
        <div class="hidden md:grid grid-cols-[3fr_1fr_1fr_1fr_auto] gap-4 px-4 py-2.5 bg-amz-page border-b border-amz-border text-[11px] font-bold text-amz-text-sec uppercase tracking-wide">
            <span>Product</span>
            <span class="text-center">Unit Price</span>
            <span class="text-center">Qty</span>
            <span class="text-center">Total</span>
            <span class="text-center">Action</span>
        </div>

        @foreach($order->items as $item)
        <div class="item-row px-4 py-4">

            {{-- Mobile --}}
            <div class="md:hidden flex gap-3">
                <div class="w-16 h-16 bg-amz-page rounded border border-amz-border flex-shrink-0 flex items-center justify-center overflow-hidden">
                    @if($item->product && $item->product->image_url)
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain p-1">
                    @else
                        <i data-lucide="image" class="w-6 h-6 text-amz-text-weak"></i>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="text-[13px] font-semibold text-amz-text mb-1">{{ $item->product->name ?? 'Product Unavailable' }}</p>
                    <div class="flex items-center gap-3 text-[12px] text-amz-text-sec">
                        <span>${{ number_format($item->price, 2) }} × {{ $item->quantity }}</span>
                        <span class="font-bold text-amz-text">${{ number_format($item->price * $item->quantity, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Desktop --}}
            <div class="hidden md:grid grid-cols-[3fr_1fr_1fr_1fr_auto] gap-4 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 bg-amz-page rounded border border-amz-border flex-shrink-0 flex items-center justify-center overflow-hidden">
                        @if($item->product && $item->product->image_url)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain p-1">
                        @else
                            <i data-lucide="image" class="w-5 h-5 text-amz-text-weak"></i>
                        @endif
                    </div>
                    <div>
                        <p class="text-[13px] font-semibold text-amz-text">{{ $item->product->name ?? 'Product Unavailable' }}</p>
                        @if($item->product)
                        <a href="{{ route('products.show', $item->product->id) }}"
                           class="text-[11px] text-amz-blue hover:underline flex items-center gap-0.5 mt-0.5">
                            <i data-lucide="external-link" class="w-3 h-3"></i> View product
                        </a>
                        @endif
                    </div>
                </div>
                <p class="text-[13px] text-amz-text-sec text-center">${{ number_format($item->price, 2) }}</p>
                <p class="text-[13px] font-semibold text-amz-text text-center">{{ $item->quantity }}</p>
                <p class="text-[14px] font-bold text-amz-text text-center">${{ number_format($item->price * $item->quantity, 2) }}</p>
                <div class="flex justify-center">
                    <a href="{{ route('orders.invoice', $order->id) }}"
                       class="view-btn px-3 py-1.5 gap-1.5 text-[12px]">
                        <i data-lucide="download" class="w-3.5 h-3.5"></i> Invoice
                    </a>
                </div>
            </div>

        </div>
        @endforeach

        <!-- Footer Total -->
        <div class="px-4 py-4 bg-amz-page border-t border-amz-border flex items-center justify-end gap-6">
            <span class="text-[14px] text-amz-text-sec font-medium">Order Total</span>
            <span class="text-[20px] font-bold text-amz-text">${{ number_format($order->total_price, 2) }}</span>
        </div>

    </div>

</div>{{-- end main --}}

@endsection
@push('scripts')


<script>
    document.addEventListener('DOMContentLoaded', () => { lucide.createIcons(); });
</script>
@endpush

