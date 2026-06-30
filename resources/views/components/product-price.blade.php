{{-- resources/views/partials/product-price.blade.php --}}

@props(['product', 'size' => 'lg'])

@php
    $price = $product->price;
    $discountPrice = $product->discount_price;
    $hasDiscount = $discountPrice && $discountPrice > $price;

    $sizeClasses = [
        'sm' => ['main' => 'text-xl', 'frac' => 'text-xs', 'label' => 'text-xs'],
        'lg' => ['main' => 'text-3xl', 'frac' => 'text-sm', 'label' => 'text-xs'],
    ][$size] ?? ['main' => 'text-3xl', 'frac' => 'text-sm', 'label' => 'text-xs'];
@endphp

<div class="mb-4">
    <div class="flex items-baseline gap-0.5">
        <span class="{{ $sizeClasses['label'] }} text-amz-text-sec">$</span>
        <span class="{{ $sizeClasses['main'] }} font-light text-amz-text leading-none align-top">
            {{ explode('.', number_format($price, 2))[0] }}
        </span>
        <span class="{{ $sizeClasses['frac'] }} text-amz-text align-top mt-1">
            .{{ explode('.', number_format($price, 2))[1] ?? '00' }}
        </span>
    </div>

    @if($hasDiscount)
    <div class="flex items-center gap-2 mt-1">
        <span class="text-[13px] text-amz-text-sec">List Price: </span>
        <span class="text-[13px] text-amz-text-sec line-through">${{ number_format($discountPrice, 2) }}</span>
    </div>
    @endif
</div>
