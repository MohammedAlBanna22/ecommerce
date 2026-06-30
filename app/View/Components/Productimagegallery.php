{{-- resources/views/partials/product-image-gallery.blade.php --}}

@props(['product'])

<div class="relative">
    <div class="main-img-container rounded-lg bg-white border border-amz-border-light overflow-hidden"
         id="mainImgContainer"
         onmousemove="handleZoom(event)"
         onmouseleave="hideZoom()">
        <div class="img-zoom-lens" id="zoomLens"></div>

        @if($product->mainImage)
            <img id="mainImage" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full aspect-square object-contain p-2">
        @elseif($product->media->first())
            <img id="mainImage" src="{{ asset('storage/'.$product->media->first()->path) }}" alt="{{ $product->name }}" class="w-full aspect-square object-contain p-2">
        @else
            <div id="mainImage" class="w-full aspect-square flex flex-col items-center justify-center text-amz-text-weak bg-amz-page">
                <i data-lucide="image" class="w-16 h-16 mb-2"></i>
                <span class="text-[14px]">No image available</span>
            </div>
        @endif
    </div>

    <div class="zoom-result rounded-lg" id="zoomResult">
        @if($product->mainImage)
            <img id="zoomImg" src="{{ $product->image_url }}" alt="">
        @elseif($product->media->first())
            <img id="zoomImg" src="{{ asset('storage/'.$product->media->first()->path) }}" alt="">
        @endif
    </div>
</div>

@if($product->media->count() > 1)
<div class="mt-3">
    <div class="flex gap-2 overflow-x-auto pb-1" id="thumbStrip">
        @foreach($product->media->sortBy('sort_order') as $index => $image)
        <button onclick="changeImage('{{ asset('storage/'.$image->path) }}', {{ $loop->index }})"
                class="thumb-item flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-lg border-2 overflow-hidden bg-white {{ $loop->first ? 'thumb-active' : 'border-amz-border' }}"
                data-index="{{ $loop->index }}">
            <img src="{{ asset('storage/'.$image->path) }}" alt="" class="w-full h-full object-contain p-0.5">
        </button>
        @endforeach
    </div>
    <p class="text-[11px] text-amz-text-weak mt-1.5">Hover over image to zoom · {{ $product->media->count() }} images</p>
</div>
@endif

<div class="flex items-center gap-3 mt-4 pt-4 border-t border-amz-border-light">
    <button onclick="shareProduct()" class="flex items-center gap-1.5 text-[13px] text-amz-blue hover:text-amz-link-hover">
        <i data-lucide="share-2" class="w-4 h-4"></i> Share
    </button>
    <button onclick="toggleWishlist(this)" class="flex items-center gap-1.5 text-[13px] text-amz-blue hover:text-amz-link-hover">
        <i data-lucide="heart" class="w-4 h-4"></i> Add to List
    </button>
</div>
