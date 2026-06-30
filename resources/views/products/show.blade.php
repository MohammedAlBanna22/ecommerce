@extends('layouts.app')

@section('content')
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
                            page: '#EAEDED',
                            border: '#DDD',
                            'border-light': '#E7E7E7',
                            text: '#0F1111',
                            'text-sec': '#565959',
                            'text-tri': '#737373',
                            'text-weak': '#979797',
                            deal: '#CC0C39',
                            'deal-bg': '#FDEEE8',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { -webkit-font-smoothing: antialiased; }
        ::-webkit-scrollbar { width: 8px; }
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
            box-shadow: 0 2px 5px rgba(213,166,60,0.4);
        }
        .amz-btn-cart:hover { background: linear-gradient(to bottom, #FFD814, #F7CA00); }
        .amz-btn-cart:active { background: linear-gradient(to bottom, #F7CA00, #E5B800); }

        .amz-btn-buy {
            background: linear-gradient(to bottom, #FFB84D, #FF9900);
            border: 1px solid #E47911;
            border-radius: 100px;
            color: #0F1111;
            transition: background 100ms;
            box-shadow: 0 2px 5px rgba(213,130,20,0.4);
        }
        .amz-btn-buy:hover { background: linear-gradient(to bottom, #FF9900, #E68A00); }

        .amz-btn-wish {
            border: 1px solid #DDD;
            border-radius: 100px;
            color: #0F1111;
            background: linear-gradient(to bottom, #F7FAFA, #EAF0F0);
            transition: all 100ms;
        }
        .amz-btn-wish:hover { background: linear-gradient(to bottom, #EAF0F0, #D5DBDB); }

        .star-fill { color: #FFA41C; }
        .star-empty { color: #DDD; }

        .thumb-active { border-color: #E77600 !important; box-shadow: 0 0 0 1px #E77600; }
        .thumb-item { transition: border-color 100ms, box-shadow 100ms; }
        .thumb-item:hover { border-color: #E77600; }

        .main-img-container { position: relative; overflow: hidden; cursor: crosshair; }
        .main-img-container img { transition: transform 300ms ease; }
        .img-zoom-lens {
            display: none; position: absolute; width: 160px; height: 160px;
            border: 2px solid #E77600; border-radius: 4px;
            background: rgba(228,166,54,0.08); pointer-events: none; z-index: 5;
        }
        .main-img-container:hover .img-zoom-lens { display: block; }

        .zoom-result {
            display: none; position: absolute; top: 0; left: 100%;
            width: 100%; height: 100%; margin-left: 12px;
            border: 1px solid #DDD; border-radius: 8px; overflow: hidden;
            background: white; z-index: 10; box-shadow: 0 4px 20px rgba(0,0,0,0.12);
        }
        .zoom-result img { position: absolute; }

        .tab-btn { transition: all 100ms; border-bottom: 3px solid transparent; }
        .tab-btn.active { border-bottom-color: #E77600; color: #E77600; font-weight: 600; }
        .tab-btn:hover:not(.active) { color: #E77600; background: #FEF7E4; }

        .tab-panel { display: none; animation: tabFade 200ms ease; }
        .tab-panel.active { display: block; }
        @keyframes tabFade { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }

        .spec-row:nth-child(even) { background: #F6F6F6; }

        .toast { animation: toastIn 300ms ease, toastOut 300ms ease 2.5s forwards; }
        @keyframes toastIn { from { transform: translateY(20px) scale(0.95); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }
        @keyframes toastOut { to { transform: translateY(20px) scale(0.95); opacity: 0; } }

        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        @media (max-width: 1024px) {
            .zoom-result { display: none !important; }
        }
    </style>
</head>



    <!-- ═══════════ BREADCRUMB ═══════════ -->
    <div class="bg-white border-b border-amz-border">
        <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-2">
            <nav class="flex items-center gap-1 text-[12px] flex-wrap">
                <a href="{{ route('home') }}" class="text-amz-blue hover:text-amz-link-hover hover:underline">{{ config('app.name', 'MyShop') }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak flex-shrink-0"></i>
                <a href="{{ route('products.index') }}" class="text-amz-blue hover:text-amz-link-hover hover:underline">Products</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak flex-shrink-0"></i>
                @if($product->category)
                <a href="#" class="text-amz-blue hover:text-amz-link-hover hover:underline">{{ $product->category->name }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak flex-shrink-0"></i>
                @endif
                <span class="text-amz-text font-medium line-clamp-1">{{ $product->name }}</span>
            </nav>
        </div>
    </div>


    <!-- ═══════════════════════════════════════════════════ -->
    <!-- ═══════════ MAIN PRODUCT SECTION ═══════════ -->
    <!-- ═══════════════════════════════════════════════════ -->
    <main class="flex-1">
        <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-4">

            <div class="bg-white rounded-lg border border-amz-border overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">

                    <!-- ═══════════ LEFT: IMAGE GALLERY ═══════════ -->
                    <div class="lg:col-span-5 xl:col-span-5 p-4 sm:p-6 border-r-0 lg:border-r border-amz-border-light">

                        <div class="relative">
                            <div class="main-img-container rounded-lg bg-white border border-amz-border-light overflow-hidden" id="mainImgContainer"
                                 onmousemove="handleZoom(event)" onmouseleave="hideZoom()">
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
                    </div>


                    <!-- ═══════════ RIGHT: PRODUCT INFO + ADMIN ═══════════ -->
                    <div class="lg:col-span-7 xl:col-span-7">
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
                                <div class="flex items-center gap-1">
                                    @php $rating = rand(35, 50) / 10; $fullStars = floor($rating); $hasHalf = ($rating - $fullStars) >= 0.3; @endphp
                                    @for($s = 1; $s <= 5; $s++)
                                        @if($s <= $fullStars)
                                        <svg class="w-5 h-5 star-fill" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @elseif($s == $fullStars + 1 && $hasHalf)
                                        <svg class="w-5 h-5" viewBox="0 0 20 20"><defs><linearGradient id="half"><stop offset="50%" stop-color="#FFA41C"/><stop offset="50%" stop-color="#DDD"/></linearGradient></defs><path fill="url(#half)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @else
                                        <svg class="w-5 h-5 star-empty" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-[14px] text-amz-blue hover:text-amz-link-hover cursor-pointer">{{ number_format($rating, 1) }}</span>
                                <span class="text-[14px] text-amz-text-sec">{{ rand(50, 3000) }} ratings</span>
                            </div>

                            <!-- Deal Badge -->
                            @if($product->discount_price && $product->discount_price > $product->price)
                                @php $discPct = round((1 - $product->price / $product->discount_price) * 100); @endphp
                                <div class="inline-flex items-center gap-2 bg-amz-deal-bg border border-amz-deal/20 rounded-lg px-4 py-2.5 mb-4">
                                    <span class="bg-amz-deal text-white text-[12px] font-bold px-2.5 py-1 rounded">{{ $discPct }}% off</span>
                                    <span class="text-[13px] text-amz-deal font-medium">Limited time deal</span>
                                </div>
                            @endif

                            <!-- Price -->
                            <div class="mb-4">
                                @if($product->discount_price && $product->discount_price > $product->price)
                                    <div class="flex items-baseline gap-2 flex-wrap">
                                        <span class="text-[12px] text-amz-text-sec -mr-1">$</span>
                                        <span class="text-[30px] font-light text-amz-text leading-none align-top">{{ explode('.', number_format($product->price, 2))[0] }}</span>
                                        <span class="text-[14px] text-amz-text align-top mt-1">.{{ explode('.', number_format($product->price, 2))[1] ?? '00' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[13px] text-amz-text-sec">List Price: </span>
                                        <span class="text-[13px] text-amz-text-sec line-through">${{ number_format($product->discount_price, 2) }}</span>
                                    </div>
                                @else
                                    <div class="flex items-baseline gap-0.5">
                                        <span class="text-[12px] text-amz-text-sec">$</span>
                                        <span class="text-[30px] font-light text-amz-text leading-none align-top">{{ explode('.', number_format($product->price, 2))[0] }}</span>
                                        <span class="text-[14px] text-amz-text align-top mt-1">.{{ explode('.', number_format($product->price, 2))[1] ?? '00' }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Prime + Delivery -->
                            <div class="space-y-2 mb-4 pb-4 border-b border-amz-border-light">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-0.5 text-[13px] text-amz-blue font-bold">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="#007185"><rect x="5" y="13" width="14" height="5" rx="1" fill="none" stroke="#007185" stroke-width="1.2"/><text x="12" y="17" font-size="5" font-weight="bold" fill="#007185" text-anchor="middle" font-family="Arial">prime</text></svg>
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
                            @php $available = $product->quantity - $product->reserved_quantity; @endphp
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
                    </div>
                </div>
            </div>


            <!-- ═══════════ TABS ═══════════ -->
            <div class="bg-white rounded-lg border border-amz-border mt-3 overflow-hidden">
                <div class="flex border-b border-amz-border overflow-x-auto">
                    <button onclick="switchTab('description')" class="tab-btn active px-6 py-3.5 text-[14px] text-amz-text whitespace-nowrap">Description</button>
                    <button onclick="switchTab('specifications')" class="tab-btn px-6 py-3.5 text-[14px] text-amz-text-sec whitespace-nowrap">Specifications</button>
                    <button onclick="switchTab('reviews')" class="tab-btn px-6 py-3.5 text-[14px] text-amz-text-sec whitespace-nowrap">Customer Reviews</button>
                </div>

                <div id="tab-description" class="tab-panel active p-6 sm:p-8">
                    @if($product->description)
                    <p class="text-[15px] text-amz-text leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                    @else
                    <p class="text-[15px] text-amz-text-sec">No description available for this product.</p>
                    @endif
                </div>

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

                <div id="tab-reviews" class="tab-panel p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row gap-8">
                        <div class="flex-shrink-0 text-center sm:text-left">
                            <div class="text-[60px] font-light text-amz-text leading-none">{{ number_format($rating, 1) }}</div>
                            <div class="flex justify-center sm:justify-start mt-1">
                                @for($s = 1; $s <= 5; $s++)
                                @if($s <= $fullStars)
                                <svg class="w-5 h-5 star-fill" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @else
                                <svg class="w-5 h-5 star-empty" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endif
                                @endfor
                            </div>
                            <p class="text-[14px] text-amz-text-sec mt-1">{{ rand(50, 3000) }} global ratings</p>
                            <div class="mt-4 space-y-1.5 w-48">
                                @for($bar = 5; $bar >= 1; $bar--)
                                @php $barPct = $bar === 5 ? rand(40,65) : ($bar === 4 ? rand(15,30) : ($bar === 3 ? rand(5,15) : ($bar === 2 ? rand(1,8) : rand(0,5)))); @endphp
                                <div class="flex items-center gap-2">
                                    <span class="text-[12px] text-amz-text-sec w-6">{{ $bar }}</span>
                                    <svg class="w-3.5 h-3.5 star-fill flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
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


            <!-- ═══════════ RELATED PRODUCTS ═══════════ -->
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
                                <img src="{{ $rel->mainImage->image_url ?? asset('storage/'.$rel->mainImage->path) }}" alt="{{ $rel->name }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300">
                            @else
                                <i data-lucide="image" class="w-10 h-10 text-amz-text-weak"></i>
                            @endif
                        </div>
                        <h3 class="text-[12px] text-amz-text line-clamp-2 leading-snug group-hover:text-amz-link-hover transition-colors">{{ $rel->name }}</h3>
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

            <!-- Back -->
            <div class="text-center mt-6 mb-4">
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 text-[14px] text-amz-blue hover:text-amz-link-hover hover:underline">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Products
                </a>
            </div>

        </div>
    </main>




    <!-- Toast -->
    <div id="toastContainer" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] flex flex-col items-center gap-2"></div>

@endsection
    <script>
        function changeImage(src, index) {
            const mainImg = document.getElementById('mainImage');
            const zoomImg = document.getElementById('zoomImg');
            if (mainImg.tagName === 'IMG') { mainImg.src = src; }
            else { mainImg.outerHTML = `<img id="mainImage" src="${src}" class="w-full aspect-square object-contain p-2">`; }
            if (zoomImg) zoomImg.src = src;
            document.querySelectorAll('.thumb-item').forEach(t => { t.classList.remove('thumb-active'); t.classList.add('border-amz-border'); });
            const active = document.querySelector(`.thumb-item[data-index="${index}"]`);
            if (active) { active.classList.add('thumb-active'); active.classList.remove('border-amz-border'); }
        }

        function handleZoom(e) {
            if (window.innerWidth < 1024) return;
            const container = document.getElementById('mainImgContainer');
            const lens = document.getElementById('zoomLens');
            const result = document.getElementById('zoomResult');
            const img = document.getElementById('zoomImg');
            if (!img || !lens || !result) return;
            result.style.display = 'block';
            const rect = container.getBoundingClientRect();
            let x = e.clientX - rect.left, y = e.clientY - rect.top;
            const lw = lens.offsetWidth, lh = lens.offsetHeight;
            x = Math.max(0, Math.min(x - lw/2, rect.width - lw));
            y = Math.max(0, Math.min(y - lh/2, rect.height - lh));
            lens.style.left = x+'px'; lens.style.top = y+'px';
            const rx = x/(rect.width-lw), ry = y/(rect.height-lh);
            const z = 2.5;
            img.style.width = (rect.width*z)+'px'; img.style.height = (rect.height*z)+'px';
            img.style.left = -(rx*(rect.width*z - result.offsetWidth))+'px';
            img.style.top = -(ry*(rect.height*z - result.offsetHeight))+'px';
        }

        function hideZoom() { const r = document.getElementById('zoomResult'); if(r) r.style.display='none'; }

        document.getElementById('qtySelect')?.addEventListener('change', function() {
            document.getElementById('cartQtyInput').value = this.value;
        });

        function buyNow() {
            const qty = document.getElementById('qtySelect')?.value || 1;
            const form = document.getElementById('addToCartForm');
            if(form) { document.getElementById('cartQtyInput').value = qty; form.submit(); }
        }

        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(b => { b.classList.remove('active'); b.classList.add('text-amz-text-sec'); b.classList.remove('text-amz-text'); });
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            event.currentTarget.classList.add('active'); event.currentTarget.classList.remove('text-amz-text-sec'); event.currentTarget.classList.add('text-amz-text');
            document.getElementById('tab-'+tab).classList.add('active');
        }

        function shareProduct() {
            if(navigator.share) { navigator.share({title:'{{ addslashes($product->name) }}',url:window.location.href}); }
            else { navigator.clipboard.writeText(window.location.href); showToast('Link copied to clipboard!'); }
        }

        function toggleWishlist(btn) {
            if(btn.dataset.active==='true') { btn.dataset.active='false'; btn.innerHTML='<i data-lucide="heart" class="w-4 h-4"></i> Add to List'; btn.classList.remove('text-amz-deal'); showToast('Removed from wishlist'); }
            else { btn.dataset.active='true'; btn.innerHTML='<i data-lucide="heart" class="w-4 h-4 fill-current"></i> Added to List'; btn.classList.add('text-amz-deal'); showToast('Added to wishlist!'); }
            lucide.createIcons();
        }

        function showToast(msg) {
            const c = document.getElementById('toastContainer');
            const t = document.createElement('div');
            t.className='toast flex items-center gap-2 bg-amz-dark text-white px-5 py-3 rounded-lg shadow-xl min-w-[280px]';
            t.innerHTML=`<svg class="w-5 h-5 text-amz-orange flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-[13px] font-medium">${msg}</span>`;
            c.appendChild(t); setTimeout(()=>t.remove(),3000);
        }

        lucide.createIcons();
    </script>
</body>
</html>
