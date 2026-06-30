

   @extends('layouts.app')

@push('styles')


    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-font-smoothing: antialiased; }

        /* ── TOKENS (same as the site) ── */
        :root {
            --dark:        #131921;
            --nav:         #232F3E;
            --orange:      #FF9900;
            --orange-btn:  #FFD814;
            --orange-bdr:  #F3A847;
            --blue:        #007185;
            --green:       #067D62;
            --green-light: #D4F3EA;
            --page:        #EAEDED;
            --border:      #DDD;
            --text:        #0F1111;
            --text-sec:    #565959;
            --text-weak:   #979797;
            --deal:        #CC0C39;
            --deal-bg:     #FDEEE8;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: var(--page);
            color: var(--text);
            padding: 32px 16px 64px;
        }

        /* ── WRAPPER ── */
        .invoice-wrap {
            max-width: 820px;
            margin: 0 auto;
        }

        /* ── TOOLBAR (print / back) ── */
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .toolbar-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--blue);
            text-decoration: none;
            padding: 6px 14px;
            border: 1px solid var(--border);
            border-radius: 100px;
            background: white;
            transition: border-color 100ms;
        }
        .toolbar-back:hover { border-color: var(--text); }
        .toolbar-print {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            padding: 6px 20px;
            border-radius: 100px;
            border: 1px solid var(--orange-bdr);
            background: linear-gradient(to bottom, #FFE696, var(--orange-btn));
            cursor: pointer;
            transition: background 100ms;
        }
        .toolbar-print:hover { background: linear-gradient(to bottom, var(--orange-btn), #F7CA00); }

        /* ── INVOICE CARD ── */
        .invoice-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }

        /* ── TOP STRIPE ── */
        .invoice-stripe {
            background: var(--dark);
            padding: 24px 28px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .invoice-brand {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .invoice-brand-icon {
            width: 36px;
            height: 36px;
            background: var(--orange);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .invoice-brand-icon svg { width: 20px; height: 20px; color: white; fill: none; stroke: white; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .invoice-brand-name { font-size: 20px; font-weight: 700; color: white; }

        .invoice-meta { text-align: right; }
        .invoice-meta-label { font-size: 11px; color: #9CA3AF; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; }
        .invoice-meta-id {
            font-size: 22px;
            font-weight: 700;
            color: white;
            margin: 4px 0 6px;
        }
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-pill svg { width: 13px; height: 13px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        /* ── INFO STRIP ── */
        .invoice-info-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0;
            border-bottom: 1px solid var(--border);
        }
        .invoice-info-cell {
            padding: 18px 28px;
            border-right: 1px solid var(--border);
        }
        .invoice-info-cell:last-child { border-right: none; }
        .info-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-weak);
            margin-bottom: 5px;
        }
        .info-value { font-size: 13px; font-weight: 600; color: var(--text); }
        .info-sub   { font-size: 12px; color: var(--text-sec); margin-top: 2px; }

        /* ── TABLE ── */
        .items-section { padding: 0; }
        .items-header {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr;
            gap: 12px;
            padding: 10px 28px;
            background: var(--page);
            border-bottom: 1px solid var(--border);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-sec);
        }
        .items-header span:not(:first-child) { text-align: right; }

        .item-row {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr;
            gap: 12px;
            padding: 14px 28px;
            border-bottom: 1px solid #F0F0F0;
            align-items: center;
            transition: background 120ms;
        }
        .item-row:last-child { border-bottom: none; }
        .item-row:hover { background: #FAFAFA; }

        .item-name { font-size: 13px; font-weight: 600; color: var(--text); }
        .item-sku  { font-size: 11px; color: var(--text-weak); margin-top: 2px; }
        .item-num  { font-size: 13px; color: var(--text-sec); text-align: right; }
        .item-bold { font-size: 13px; font-weight: 700; color: var(--text); text-align: right; }

        /* ── TOTALS ── */
        .totals-section {
            padding: 16px 28px 20px;
            border-top: 1px solid var(--border);
            background: var(--page);
        }
        .totals-row {
            display: flex;
            justify-content: flex-end;
            gap: 40px;
            font-size: 13px;
            color: var(--text-sec);
            margin-bottom: 8px;
        }
        .totals-row span:last-child { min-width: 90px; text-align: right; font-weight: 500; color: var(--text); }
        .totals-row.discount span:last-child { color: var(--deal); }
        .totals-row.free span:last-child     { color: var(--green); }

        .totals-final {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 40px;
            padding-top: 12px;
            border-top: 1px solid var(--border);
            margin-top: 4px;
        }
        .totals-final-label { font-size: 15px; font-weight: 700; color: var(--text); }
        .totals-final-val   { font-size: 24px; font-weight: 700; color: var(--text); min-width: 90px; text-align: right; }

        /* ── PAID BADGE SECTION ── */
        .paid-row {
            padding: 12px 28px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }
        .paid-note { font-size: 12px; color: var(--text-sec); }
        .paid-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 14px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
        }
        .paid-badge svg { width: 13px; height: 13px; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; }
        .badge-paid     { background: var(--green-light); color: var(--green); }
        .badge-unpaid   { background: #FFF3E0; color: #E47911; }

        /* ── FOOTER ── */
        .invoice-footer {
            padding: 18px 28px;
            border-top: 2px solid var(--orange);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .footer-thanks { font-size: 13px; font-weight: 600; color: var(--text); }
        .footer-sub    { font-size: 11px; color: var(--text-sec); margin-top: 2px; }
        .footer-copy   { font-size: 11px; color: var(--text-weak); text-align: right; }

        /* ── PRINT ── */
        @media print {
            body { background: white; padding: 0; }
            .toolbar { display: none; }
            .invoice-card { border: none; box-shadow: none; border-radius: 0; }
            .item-row:hover { background: transparent; }
        }

        @media (max-width: 600px) {
            .invoice-stripe { flex-direction: column; }
            .invoice-meta   { text-align: left; }
            .items-header, .item-row { grid-template-columns: 2fr 1fr 1fr; }
            .items-header span:nth-child(2),
            .item-row .item-num:nth-child(2) { display: none; }
            .invoice-info-strip { grid-template-columns: 1fr 1fr; }
            .invoice-info-cell  { border-right: none; border-bottom: 1px solid var(--border); }
        }
    </style>
    @endpush




@section('content')

<div class="invoice-wrap">

    <!-- Toolbar -->
    <div class="toolbar">
        <a href="{{ url()->previous() }}" class="toolbar-back">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Back to Order
        </a>
        <button class="toolbar-print" onclick="window.print()">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v8H6z"/></svg>
            Print / Save PDF
        </button>
    </div>

    <!-- Invoice Card -->
    <div class="invoice-card">

        <!-- ── Top Stripe ── -->
        <div class="invoice-stripe">
            <div class="invoice-brand">
                <div class="invoice-brand-icon">
                    <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/></svg>
                </div>
                <span class="invoice-brand-name">{{ config('app.name', 'MyShop') }}</span>
            </div>

            <div class="invoice-meta">
                <p class="invoice-meta-label">Invoice</p>
                <p class="invoice-meta-id">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                @php
                    $statusConfig = [
                        'pending'    => ['bg' => '#FEE5E5', 'text' => '#C40C0C', 'label' => 'Pending',    'icon' => '<path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 6v6l4 2"/>'],
                        'processing' => ['bg' => '#E8F4FF', 'text' => '#0066CC', 'label' => 'Processing', 'icon' => '<path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>'],
                        'shipped'    => ['bg' => '#F3E8FF', 'text' => '#6B21A8', 'label' => 'Shipped',    'icon' => '<rect x="1" y="3" width="15" height="13"/><path d="M16 8h4l3 3v5h-7V8zM5.5 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM18.5 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>'],
                        'delivered'  => ['bg' => '#D4F3EA', 'text' => '#067D62', 'label' => 'Delivered',  'icon' => '<path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/>'],
                        'cancelled'  => ['bg' => '#F3F3F3', 'text' => '#565959', 'label' => 'Cancelled',  'icon' => '<circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/>'],
                    ];
                    $st = $statusConfig[$order->order_status] ?? $statusConfig['pending'];
                @endphp
                <span class="status-pill" style="background:{{ $st['bg'] }};color:{{ $st['text'] }}">
                    <svg viewBox="0 0 24 24">{!! $st['icon'] !!}</svg>
                    {{ $st['label'] }}
                </span>
            </div>
        </div>

        <!-- ── Info Strip ── -->
        <div class="invoice-info-strip">
            <div class="invoice-info-cell">
                <p class="info-label">Bill To</p>
                <p class="info-value">{{ $order->user->name }}</p>
                <p class="info-sub">{{ $order->user->email }}</p>
            </div>
            @if($order->address)
            <div class="invoice-info-cell">
                <p class="info-label">Ship To</p>
                <p class="info-value">{{ $order->address->city ?? '—' }}</p>
                <p class="info-sub">{{ $order->address->street ?? '' }}{{ $order->address->country ? ', ' . $order->address->country : '' }}</p>
            </div>
            @endif
            <div class="invoice-info-cell">
                <p class="info-label">Invoice Date</p>
                <p class="info-value">{{ $order->created_at->format('M d, Y') }}</p>
                <p class="info-sub">{{ $order->created_at->format('h:i A') }}</p>
            </div>
            <div class="invoice-info-cell">
                <p class="info-label">Payment Method</p>
                <p class="info-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? '—')) }}</p>
                <p class="info-sub">{{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}</p>
            </div>
        </div>

        <!-- ── Items Table ── -->
        <div class="items-section">
            <div class="items-header">
                <span>Product</span>
                <span style="text-align:right">Unit Price</span>
                <span style="text-align:right">Qty</span>
                <span style="text-align:right">Total</span>
            </div>

            @foreach($order->items as $item)
            <div class="item-row">
                <div>
                    <p class="item-name">{{ $item->product->name ?? 'Product Unavailable' }}</p>
                    @if($item->product && $item->product->sku)
                    <p class="item-sku">SKU: {{ $item->product->sku }}</p>
                    @endif
                </div>
                <p class="item-num">${{ number_format($item->price, 2) }}</p>
                <p class="item-num">× {{ $item->quantity }}</p>
                <p class="item-bold">${{ number_format($item->price * $item->quantity, 2) }}</p>
            </div>
            @endforeach
        </div>

        <!-- ── Totals ── -->
        <div class="totals-section">
            <div class="totals-row">
                <span>Subtotal</span>
                <span>${{ number_format($order->total_price, 2) }}</span>
            </div>
            @if(isset($order->discount) && $order->discount > 0)
            <div class="totals-row discount">
                <span>Discount</span>
                <span>-${{ number_format($order->discount, 2) }}</span>
            </div>
            @endif
            <div class="totals-row free">
                <span>Shipping</span>
                <span>Free</span>
            </div>
            <div class="totals-final">
                <span class="totals-final-label">Total</span>
                <span class="totals-final-val">${{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>

        <!-- ── Payment Status ── -->
        <div class="paid-row">
            <p class="paid-note">
                Payment processed on {{ $order->updated_at->format('M d, Y') }}
            </p>
            @if($order->payment_status === 'paid')
            <span class="paid-badge badge-paid">
                <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                Payment Confirmed
            </span>
            @else
            <span class="paid-badge badge-unpaid">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Payment Pending
            </span>
            @endif
        </div>

        <!-- ── Footer ── -->
        <div class="invoice-footer">
            <div>
                <p class="footer-thanks">Thank you for your purchase!</p>
                <p class="footer-sub">Questions? Contact us at support@{{ strtolower(str_replace(' ', '', config('app.name', 'myshop'))) }}.com</p>
            </div>
            <div class="footer-copy">
                <p>{{ config('app.name', 'MyShop') }}</p>
                <p>© {{ date('Y') }} · Invoice #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

    </div><!-- /invoice-card -->

</div><!-- /invoice-wrap -->

@endsection
