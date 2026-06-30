{{-- resources/views/partials/product-tabs.blade.php --}}

@props(['product'])

@php
    $available = $product->quantity - $product->reserved_quantity;
    $rating = rand(35, 50) / 10;
    $fullStars = floor($rating);
@endphp

<div class="bg-white rounded-lg border border-amz-border mt-3 overflow-hidden">
    <div class="flex border-b border-amz-border overflow-x-auto">
        <button onclick="switchTab('description')" class="tab-btn active px-6 py-3.5 text-[14px] text-amz-text whitespace-nowrap">Description</button>
        <button onclick="switchTab('specifications')" class="tab-btn px-6 py-3.5 text-[14px] text-amz-text-sec whitespace-nowrap">Specifications</button>
        <button onclick="switchTab('reviews')" class="tab-btn px-6 py-3.5 text-[14px] text-amz-text-sec whitespace-nowrap">Customer Reviews</button>
    </div>

    <!-- Description Tab -->
    <div id="tab-description" class="tab-panel active p-6 sm:p-8">
        @if($product->description)
            <p class="text-[15px] text-amz-text leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
        @else
            <p class="text-[15px] text-amz-text-sec">No description available for this product.</p>
        @endif
    </div>

    <!-- Specifications Tab -->
    <div id="tab-specifications" class="tab-panel p-6 sm:p-8">
        <table class="w-full max-w-2xl">
            <tbody>
                <tr class="spec-row border border-amz-border-light">
                    <td class="px-4 py-3 text-[13px] text-amz-text-sec bg-amz-page w-40 font-medium">Product Name</td>
                    <td class="px-4 py-3 text-[13px] text-amz-text">{{ $product->name }}</td>
                </tr>
                @if($product->category)
                <tr class="spec-row border border-amz-border-light">
                    <td class="px-4 py-3 text-[13px] text-amz-text-sec bg-amz-page w-40 font-medium">Category</td>
                    <td class="px-4 py-3 text-[13px] text-amz-text">{{ $product->category->name }}</td>
                </tr>
                @endif
                <tr class="spec-row border border-amz-border-light">
                    <td class="px-4 py-3 text-[13px] text-amz-text-sec bg-amz-page w-40 font-medium">Price</td>
                    <td class="px-4 py-3 text-[13px] text-amz-text">${{ number_format($product->price, 2) }}</td>
                </tr>
                @if($product->discount_price)
                <tr class="spec-row border border-amz-border-light">
                    <td class="px-4 py-3 text-[13px] text-amz-text-sec bg-amz-page w-40 font-medium">List Price</td>
                    <td class="px-4 py-3 text-[13px] text-amz-text line-through">${{ number_format($product->discount_price, 2) }}</td>
                </tr>
                @endif
                <tr class="spec-row border border-amz-border-light">
                    <td class="px-4 py-3 text-[13px] text-amz-text-sec bg-amz-page w-40 font-medium">Availability</td>
                    <td class="px-4 py-3 text-[13px]">
                        <span class="{{ $available > 0 ? 'text-amz-green' : 'text-amz-deal' }} font-medium">
                            {{ $available > 0 ? 'In Stock ('.$available.' available)' : 'Out of Stock' }}
                        </span>
                    </td>
                </tr>
                <tr class="spec-row border border-amz-border-light">
                    <td class="px-4 py-3 text-[13px] text-amz-text-sec bg-amz-page w-40 font-medium">Status</td>
                    <td class="px-4 py-3 text-[13px]">
                        <span class="px-2 py-0.5 rounded text-[11px] font-medium {{ $product->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </td>
                </tr>
                @if($product->sku)
                <tr class="spec-row border border-amz-border-light">
                    <td class="px-4 py-3 text-[13px] text-amz-text-sec bg-amz-page w-40 font-medium">SKU</td>
                    <td class="px-4 py-3 text-[13px] text-amz-text font-mono">{{ $product->sku }}</td>
                </tr>
                @endif
                <tr class="spec-row border border-amz-border-light">
                    <td class="px-4 py-3 text-[13px] text-amz-text-sec bg-amz-page w-40 font-medium">Date Added</td>
                    <td class="px-4 py-3 text-[13px] text-amz-text">{{ $product->created_at->format('F j, Y') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Reviews Tab -->
    <div id="tab-reviews" class="tab-panel p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row gap-8">
            <div class="flex-shrink-0 text-center sm:text-left">
                <div class="text-[60px] font-light text-amz-text leading-none">{{ number_format($rating, 1) }}</div>
                <div class="flex justify-center sm:justify-start mt-1">
                    <x-product-stars :rating="$rating" size="md" />
                </div>
                <p class="text-[14px] text-amz-text-sec mt-1">{{ rand(50, 3000) }} global ratings</p>
                <div class="mt-4 space-y-1.5 w-48">
                    @for($bar = 5; $bar >= 1; $bar--)
                    @php $barPct = $bar === 5 ? rand(40,65) : ($bar === 4 ? rand(15,30) : ($bar === 3 ? rand(5,15) : ($bar === 2 ? rand(1,8) : rand(0,5)))); @endphp
                    <div class="flex items-center gap-2">
                        <span class="text-[12px] text-amz-text-sec w-6">{{ $bar }}</span>
                        <svg class="w-3.5 h-3.5 text-amz-star flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <div class="flex-1 h-3 bg-amz-page rounded-full overflow-hidden">
                            <div class="h-full bg-amz-orange rounded-full" style="width: {{ $barPct }}%"></div>
                        </div>
                        <span class="text-[11px] text-amz-text-weak w-8 text-right">{{ $barPct }}%</span>
                    </div>
                    @endfor
                </div>
            </div>
            <div class="flex-1 border-l border-amz-border-light pl-8">
                <button class="amz-btn-cart px-6 py-2 text-[14px] font-medium">Write a customer review</button>
                <p class="text-[13px] text-amz-text-sec mt-4">Review system coming soon.</p>
            </div>
        </div>
    </div>
</div>
