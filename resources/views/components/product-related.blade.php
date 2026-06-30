{{-- resources/views/partials/product-related.blade.php --}}

@props(['product'])

@php
    $related = \App\Models\Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('status', 'available')
        ->with('mainImage')
        ->inRandomOrder()
        ->take(5)
        ->get();
@endphp

@if($related->count() > 0)
<div class="bg-white rounded-lg border border-amz-border mt-3 p-5">
    <h2 class="text-[20px] font-bold text-amz-text mb-4">Products related to this item</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
        @foreach($related as $rel)
        <a href="{{ route('products.show', $rel->id) }}" class="group p-3 rounded-lg border border-transparent hover:border-amz-border hover:shadow-md transition-all">
            <div class="aspect-square bg-white rounded-lg overflow-hidden mb-2 flex items-center justify-center">
                @if($rel->mainImage)
                    <img src="{{ $rel->mainImage->image_url ?? asset('storage/'.$rel->mainImage->path) }}"
                         alt="{{ $rel->name }}"
                         class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                @else
                    <i data-lucide="image" class="w-10 h-10 text-amz-text-weak"></i>
                @endif
            </div>
            <h3 class="text-[12px] text-amz-text line-clamp-2 leading-snug group-hover:text-amz-link-hover transition-colors">
                {{ $rel->name }}
            </h3>
            <div class="flex items-center gap-1 mt-1">
                <span class="text-[13px] font-bold text-amz-text">${{ number_format($rel->price, 2) }}</span>
                @if($rel->discount_price && $rel->discount_price > $rel->price)
                <span class="text-[11px] text-amz-text-sec line-through">${{ number_format($rel->discount_price, 2) }}</span>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif
