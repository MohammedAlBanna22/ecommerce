{{-- resources/views/partials/product-details-panel.blade.php --}}

@props(['product'])

@php
    $available = $product->quantity - $product->reserved_quantity;
    $hasDiscount = $product->discount_price && $product->discount_price > $product->price;
    $discPct = $hasDiscount ? round((1 - $product->price / $product->discount_price) * 100) : 0;
@endphp

<div class="p-4 sm:p-6 lg:p-8">

    <!-- Title -->
    <h1 class="text-[22px] sm:text-[24px] font-normal text-amz-text leading-tight mb-2">
        {{ $product->name }}
    </h1>

    <!-- Brand / Category -->
    <div class="flex items-center gap-2 mb-3">
        @if($product->category)
        <a href="#" class="text-[13px] text-amz-blue hover:text-amz-link-hover hover:underline">
            Visit the {{ $product->category->name }} Store
        </a>
        @endif
    </div>

    <!-- Rating Row -->
    <div class="flex items-center gap-2 flex-wrap mb-3 pb-3 border-b border-amz-border-light">
        @php $rating = rand(35, 50) / 10; @endphp
        <x-product-stars :rating="$rating" size="md" :count="rand(50, 3000)" />
        <span class="text-[14px] text-amz-blue hover:text-amz-link-hover cursor-pointer">{{ number_format($rating, 1) }}</span>
    </div>

    <!-- Deal Badge -->
    @if($hasDiscount)
    <div class="inline-flex items-center gap-2 bg-amz-deal-bg border border-amz-deal/20 rounded-lg px-4 py-2.5 mb-4">
        <span class="bg-amz-deal text-white text-[12px] font-bold px-2.5 py-1 rounded">{{ $discPct }}% off</span>
        <span class="text-[13px] text-amz-deal font-medium">Limited time deal</span>
    </div>
    @endif

    <!-- Price -->
    <x-product-price :product="$product" />

    <!-- Prime + Delivery -->
    <div class="space-y-2 mb-4 pb-4 border-b border-amz-border-light">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-0.5 text-[13px] text-amz-blue font-bold">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="#007185"><rect x="5" y="13" width="14" height="5" rx="1" fill="none" stroke="#007185" stroke-width="1.2"/></svg>
                prime
            </span>
            <span class="text-[14px] text-amz-text">FREE delivery</span>
        </div>
        <div class="flex items-center gap-3 text-[13px]">
            <span class="text-amz-text font-medium">FREE delivery <strong>{{ \Carbon\Carbon::now()->addDays(rand(2,5))->format('l, F j') }}</strong></span>
        </div>
        <div class="flex items-center gap-3 text-[13px]">
            <span class="text-amz-text-sec">Or fastest delivery</span>
            <span class="text-amz-text font-medium">Tomorrow, {{ \Carbon\Carbon::tomorrow()->format('M j') }}</span>
        </div>
    </div>

    <!-- Stock -->
    <div class="mb-4">
        @if($available > 10)
            <span class="text-[18px] text-amz-green font-medium">In Stock</span>
        @elseif($available > 0)
            <div class="flex items-center gap-2">
                <span class="text-[18px] text-amz-orange font-medium">Only {{ $available }} left in stock</span>
                <span class="text-[13px] text-amz-orange">— order soon</span>
            </div>
        @else
            <span class="text-[18px] text-amz-deal font-medium">Currently unavailable</span>
        @endif
    </div>

    <!-- Quantity -->
    @if($available > 0)
    <div class="flex items-center gap-3 mb-4">
        <label class="text-[14px] text-amz-text-sec">Qty:</label>
        <select id="qtySelect" class="amz-input px-3 py-1.5 rounded-lg text-[13px] bg-white cursor-pointer border-amz-border shadow-sm">
            @for($q = 1; $q <= min($available, 30); $q++)
            <option value="{{ $q }}" {{ $q === 1 ? 'selected' : '' }}>{{ $q }}</option>
            @endfor
            @if($available > 30)<option value="30+">30+</option>@endif
        </select>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="space-y-3 mb-5 pb-5 border-b border-amz-border-light">
        @if($available > 0)
        <form action="{{ route('cart.add', $product->id) }}" method="POST" id="addToCartForm">
            @csrf
            <input type="hidden" name="quantity" id="cartQtyInput" value="1">
            <button type="submit" class="amz-btn-cart w-full py-2.5 text-[15px] font-medium flex items-center justify-center gap-2">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i> Add to Cart
            </button>
        </form>
        <button onclick="buyNow()" class="amz-btn-buy w-full py-2.5 text-[15px] font-medium flex items-center justify-center gap-2">
            <i data-lucide="zap" class="w-5 h-5"></i> Buy Now
        </button>
        @else
        <button disabled class="w-full py-2.5 text-[15px] font-medium bg-gray-200 text-amz-text-weak rounded-full border border-amz-border cursor-not-allowed">Currently Unavailable</button>
        @endif
        <button class="amz-btn-wish w-full py-2.5 text-[14px] font-medium flex items-center justify-center gap-2">
            <i data-lucide="heart" class="w-4 h-4"></i> Add to Wish List
        </button>
    </div>

    <!-- Secure Transaction -->
    <div class="flex items-start gap-2 mb-4 pb-4 border-b border-amz-border-light">
        <i data-lucide="shield-check" class="w-5 h-5 text-amz-green flex-shrink-0 mt-0.5"></i>
        <div class="text-[13px] text-amz-text-sec leading-relaxed">
            <span class="font-medium text-amz-text">Secure transaction</span><br>
            Ships from and sold by {{ config('app.name', 'MyShop') }}.
        </div>
    </div>

    <!-- Mini Info -->
    <div class="space-y-2.5 text-[13px]">
        <div class="flex gap-3">
            <span class="text-amz-text-sec w-28 flex-shrink-0">Ships from</span>
            <span class="text-amz-text">{{ config('app.name', 'MyShop') }}</span>
        </div>
        <div class="flex gap-3">
            <span class="text-amz-text-sec w-28 flex-shrink-0">Sold by</span>
            <span class="text-amz-blue hover:text-amz-link-hover cursor-pointer">{{ config('app.name', 'MyShop') }}</span>
        </div>
        <div class="flex gap-3">
            <span class="text-amz-text-sec w-28 flex-shrink-0">Returns</span>
            <span class="text-amz-blue hover:text-amz-link-hover cursor-pointer">30-day refund/replacement</span>
        </div>
        <div class="flex gap-3">
            <span class="text-amz-text-sec w-28 flex-shrink-0">Payment</span>
            <span class="text-amz-text">Secure transaction</span>
        </div>
    </div>

</div>
