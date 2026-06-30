@extends('layouts.app')

@push('styles')


    <style>
        * { -webkit-font-smoothing: antialiased; }
        ::-webkit-scrollbar { width: 8px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f0f0f0; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }

        /* ── Shared buttons (same as product edit) ── */
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
            color: white;
            transition: background 100ms;
        }
        .amz-btn-buy:hover { background: linear-gradient(to bottom, #FF9900, #E68A00); }

        .amz-input { border: 1px solid #DDD; transition: border-color 100ms, box-shadow 100ms; }
        .amz-input:focus { border-color: #E77600; box-shadow: 0 0 0 3px rgba(228,166,54,0.3); outline: none; }

        .nav-link { transition: all 100ms; border: 1px solid transparent; border-radius: 3px; }
        .nav-link:hover { border-color: white; }

        /* ── Product cards ── */
        .product-card {
            border: 1px solid #DDD;
            border-radius: 8px;
            background: white;
            transition: box-shadow 200ms ease;
            overflow: hidden;
        }
        .product-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.12); }
        .product-card .card-img img { transition: transform 350ms ease; }
        .product-card:hover .card-img img { transform: scale(1.04); }

        /* ── Category card ── */
        .cat-card {
            border: 1px solid #DDD;
            border-radius: 8px;
            background: white;
            transition: box-shadow 200ms, border-color 200ms;
            cursor: pointer;
        }
        .cat-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-color: #FF9900; }

        /* ── Scroll row (deals) ── */
        .scroll-row { overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none; }
        .scroll-row::-webkit-scrollbar { display: none; }

        /* ── Badges ── */
        .badge-deal     { background: #CC0C39; color: white; font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 3px; }
        .badge-new      { background: #067D62; color: white; font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 3px; }
        .badge-hot      { background: #FF9900; color: #0F1111; font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 3px; }
        .badge-pct      { background: #CC0C39; color: white; font-size: 11px; font-weight: 700; padding: 3px 7px; border-radius: 3px; }

        /* ── Stars ── */
        .star-fill  { color: #FFA41C; }
        .star-empty { color: #DDD; }

        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* ── Hero ── */
        .hero-section {
            background: linear-gradient(135deg, #131921 0%, #232F3E 60%, #37475A 100%);
        }

        /* ── Section headers ── */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #0F1111;
        }
        .section-link {
            font-size: 13px;
            color: #007185;
        }
        .section-link:hover { text-decoration: underline; color: #C7511F; }

        /* ── Trust bar ── */
        .trust-item { border-right: 1px solid #E7E7E7; }
        .trust-item:last-child { border-right: none; }

        /* ── Best-seller rank ── */
        .rank-num { font-size: 32px; font-weight: 800; color: #FF9900; line-height: 1; min-width: 36px; }

        /* ── Promo banner ── */
        .promo-banner {
            background: linear-gradient(135deg, #131921 0%, #232F3E 100%);
            border-radius: 8px;
            overflow: hidden;
        }
    </style>
    @endpush


@section('content')

{{-- ═══ MAIN ═══ --}}
<main>

    {{-- ─── HERO ─── --}}
    <section class="hero-section">
        <div class="max-w-[1500px] mx-auto px-4 py-10 md:py-16">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

                {{-- Text --}}
                <div class="text-white">
                    <p class="text-amz-orange text-[12px] font-bold uppercase tracking-widest mb-3 flex items-center gap-2">
                        <span class="w-6 h-0.5 bg-amz-orange inline-block"></span>
                        Today's Best Deals
                    </p>
                    <h1 class="text-[38px] md:text-[52px] font-extrabold leading-[1.1] mb-4">
                        Shop Smart,<br>
                        <span class="text-amz-orange">Save Big</span>
                    </h1>
                    <p class="text-[15px] text-gray-400 mb-8 max-w-md leading-relaxed">
                        Discover thousands of products at unbeatable prices.<br>Free delivery on orders over $25.
                    </p>
                    <div class="flex items-center gap-3 flex-wrap">
                        <a href="{{ route('products.index') }}"
                           class="amz-btn-buy px-8 py-3 text-[14px] font-bold inline-flex items-center gap-2">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i> Shop Now
                        </a>
                        <a href="#deals"
                           class="px-8 py-3 text-[14px] font-semibold text-white border border-white/25 rounded-full hover:bg-white/10 transition-colors inline-flex items-center gap-2">
                            View Deals
                        </a>
                    </div>
                    {{-- Trust badges --}}
                    <div class="flex items-center gap-5 mt-9 flex-wrap">
                        <div class="flex items-center gap-1.5 text-gray-400 text-[12px]">
                            <i data-lucide="truck" class="w-4 h-4 text-amz-orange"></i> Free Delivery
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-400 text-[12px]">
                            <i data-lucide="shield-check" class="w-4 h-4 text-amz-orange"></i> Secure Payments
                        </div>
                        <div class="flex items-center gap-1.5 text-gray-400 text-[12px]">
                            <i data-lucide="rotate-ccw" class="w-4 h-4 text-amz-orange"></i> Easy Returns
                        </div>
                    </div>
                </div>

                {{-- Visual --}}
                <div class="hidden md:flex items-center justify-center">
                    @isset($featuredProduct)
                    <div class="relative w-[300px] h-[300px] bg-white/8 rounded-2xl border border-white/10 backdrop-blur-sm flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-amz-orange/5 to-transparent"></div>
                        @if($featuredProduct->image_url)
                        <img src="{{ $featuredProduct->image_url }}" alt="{{ $featuredProduct->name }}"
                             class="w-52 h-52 object-contain drop-shadow-2xl relative z-10">
                        @endif
                        <div class="absolute bottom-0 left-0 right-0 bg-amz-dark/90 px-4 py-3 z-10">
                            <p class="text-white font-semibold text-[13px] line-clamp-1">{{ $featuredProduct->name }}</p>
                            <p class="text-amz-orange font-extrabold text-[20px]">${{ number_format($featuredProduct->price, 2) }}</p>
                        </div>
                    </div>
                    @else
                    <div class="w-[280px] h-[280px] bg-white/5 rounded-2xl border border-white/10 flex flex-col items-center justify-center gap-3">
                        <i data-lucide="shopping-bag" class="w-20 h-20 text-amz-orange/40"></i>
                        <p class="text-white/40 text-[13px]">Featured Products</p>
                    </div>
                    @endisset
                </div>
            </div>
        </div>
        {{-- Wave --}}
        <div class="h-6 bg-amz-page" style="clip-path: ellipse(55% 100% at 50% 100%)"></div>
    </section>

    {{-- ─── TRUST BAR ─── --}}
    <section class="bg-white border-b border-amz-border">
        <div class="max-w-[1500px] mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4">
                @foreach([
                    ['icon' => 'truck',         'color' => '#FF9900', 'bg' => '#FFF8EC', 'title' => 'Free Delivery',   'sub' => 'On orders over $25'],
                    ['icon' => 'shield-check',  'color' => '#067D62', 'bg' => '#D4F3EA', 'title' => 'Secure Payment',  'sub' => '100% protected'],
                    ['icon' => 'rotate-ccw',    'color' => '#0066CC', 'bg' => '#E8F4FF', 'title' => 'Easy Returns',    'sub' => '30-day policy'],
                    ['icon' => 'headphones',    'color' => '#6B21A8', 'bg' => '#F3E8FF', 'title' => '24/7 Support',    'sub' => 'Here to help'],
                ] as $t)
                <div class="trust-item flex items-center gap-3 px-4 py-4 md:py-5">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                         style="background:{{ $t['bg'] }}">
                        <i data-lucide="{{ $t['icon'] }}" class="w-5 h-5" style="color:{{ $t['color'] }}"></i>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold text-amz-text">{{ $t['title'] }}</p>
                        <p class="text-[11px] text-amz-text-sec">{{ $t['sub'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ─── SHOP BY CATEGORY ─── --}}
    @isset($categories)
    @if($categories->count() > 0)
    <section class="max-w-[1500px] mx-auto px-4 py-8">
        <div class="section-header">
            <h2 class="section-title">Shop by Category</h2>
            <a href="{{ route('products.index') }}" class="section-link">See all →</a>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
            {{-- All --}}
            <a href="{{ route('products.index') }}" class="cat-card p-3 flex flex-col items-center gap-2 text-center">
                <div class="w-12 h-12 bg-amz-page rounded-full flex items-center justify-center border border-amz-border">
                    <i data-lucide="layout-grid" class="w-6 h-6 text-amz-orange"></i>
                </div>
                <span class="text-[12px] font-semibold text-amz-text">All</span>
            </a>
            @foreach($categories->take(15) as $cat)
            <a href="{{ route('products.index', ['category_id' => $cat->id]) }}"
               class="cat-card p-3 flex flex-col items-center gap-2 text-center">
                <div class="w-12 h-12 bg-amz-page rounded-full flex items-center justify-center border border-amz-border overflow-hidden">
                    @if(isset($cat->image) && $cat->image)
                        <img src="{{ $cat->image }}" alt="{{ $cat->name }}" class="w-full h-full object-cover">
                    @else
                        <i data-lucide="tag" class="w-5 h-5 text-amz-text-sec"></i>
                    @endif
                </div>
                <span class="text-[11px] font-semibold text-amz-text line-clamp-2 leading-tight">{{ $cat->name }}</span>
                @if(isset($cat->products_count))
                <span class="text-[10px] text-amz-text-weak">{{ $cat->products_count }}</span>
                @endif
            </a>
            @endforeach
        </div>
    </section>
    @endif
    @endisset

    {{-- ─── TODAY'S DEALS ─── --}}
    @isset($dealProducts)
    @if($dealProducts->count() > 0)
    <section id="deals" class="bg-amz-deal border-y border-amz-border py-6">
        <div class="max-w-[1500px] mx-auto px-4">
            <div class="section-header mb-5">
                <div class="flex items-center gap-3">
                    <h2 class="text-[22px] font-bold text-white">Today's Deals</h2>
                    <span class="badge-deal rounded-full px-3 py-1 text-[11px] animate-pulse">⏰ Limited Time</span>
                </div>
                <a href="{{ route('products.index') }}" class="text-[13px] text-white/80 hover:text-white font-medium">See all →</a>
            </div>
            <div class="scroll-row flex gap-3 pb-2">
                @foreach($dealProducts as $product)
                @php $pct = ($product->discount_price && $product->discount_price > $product->price) ? round((1 - $product->price / $product->discount_price) * 100) : 0; @endphp
                <div class="product-card flex-shrink-0 w-[170px] sm:w-[190px]">
                    <a href="{{ route('products.show', $product->id) }}" class="card-img block relative bg-white">
                        <div class="aspect-square p-3 flex items-center justify-center overflow-hidden">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain" loading="lazy">
                            @else
                                <i data-lucide="image" class="w-10 h-10 text-amz-text-weak"></i>
                            @endif
                        </div>
                        @if($pct > 0)
                        <span class="badge-pct absolute top-2 left-2">-{{ $pct }}%</span>
                        @endif
                    </a>
                    <div class="p-3">
                        <a href="{{ route('products.show', $product->id) }}">
                            <h3 class="text-[12px] text-amz-text line-clamp-2 leading-snug hover:text-amz-blue mb-1.5">{{ $product->name }}</h3>
                        </a>
                        <p class="text-[16px] font-bold text-amz-text">${{ number_format($product->price, 2) }}</p>
                        @if($product->discount_price && $product->discount_price > $product->price)
                        <p class="text-[11px] text-amz-text-sec line-through">${{ number_format($product->discount_price, 2) }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endisset

    {{-- ─── NEW ARRIVALS ─── --}}
    @isset($newProducts)
    @if($newProducts->count() > 0)
    <section class="max-w-[1500px] mx-auto px-4 py-8">
        <div class="section-header">
            <div class="flex items-center gap-2">
                <h2 class="section-title">New Arrivals</h2>
                <span class="badge-new rounded-full px-2.5 py-1">✨ Just In</span>
            </div>
            <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="section-link">See all →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
            @foreach($newProducts->take(12) as $product)
            @php $avail = $product->available ?? ($product->quantity ?? 0); @endphp
            <div class="product-card">
                <a href="{{ route('products.show', $product->id) }}" class="card-img block relative bg-amz-page">
                    <div class="aspect-square p-3 flex items-center justify-center overflow-hidden">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain" loading="lazy">
                        @else
                            <i data-lucide="image" class="w-10 h-10 text-amz-text-weak"></i>
                        @endif
                    </div>
                    <span class="badge-new absolute top-2 left-2 rounded">NEW</span>
                </a>
                <div class="p-3">
                    <a href="{{ route('products.show', $product->id) }}">
                        <h3 class="text-[12px] text-amz-text line-clamp-2 leading-snug hover:text-amz-blue mb-1">{{ $product->name }}</h3>
                    </a>
                    <div class="flex mb-1">
                        @for($s = 1; $s <= 5; $s++)
                        <svg class="w-3 h-3 {{ $s <= 4 ? 'star-fill' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @endfor
                    </div>
                    <p class="text-[15px] font-bold text-amz-text mb-1">${{ number_format($product->price, 2) }}</p>
                    @if($avail > 0)
                        <p class="text-[11px] text-amz-green font-medium">In Stock</p>
                    @else
                        <p class="text-[11px] text-amz-deal font-medium">Out of Stock</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
    @endisset

    {{-- ─── PROMO BANNER ─── --}}
    <section class="max-w-[1500px] mx-auto px-4 py-2 pb-8">
        <div class="promo-banner p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-white text-center md:text-left">
                <p class="text-amz-orange font-bold text-[11px] uppercase tracking-widest mb-2">Limited Offer</p>
                <h2 class="text-[26px] md:text-[34px] font-extrabold mb-2">Get 20% Off Your First Order</h2>
                <p class="text-gray-400 text-[14px]">
                    Use code <span class="text-amz-orange font-bold bg-white/10 px-2 py-0.5 rounded font-mono">WELCOME20</span> at checkout
                </p>
            </div>
            <a href="{{ route('products.index') }}"
               class="amz-btn-cart px-10 py-3.5 text-[15px] font-bold flex-shrink-0 inline-flex items-center gap-2">
                <i data-lucide="tag" class="w-5 h-5"></i>
                Claim Offer
            </a>
        </div>
    </section>

    {{-- ─── BEST SELLERS ─── --}}
    @isset($bestSellers)
    @if($bestSellers->count() > 0)
    <section class="max-w-[1500px] mx-auto px-4 py-2 pb-8">
        <div class="section-header">
            <div class="flex items-center gap-2">
                <h2 class="section-title">Best Sellers</h2>
                <span class="badge-hot rounded-full px-2.5 py-1">🔥 Hot</span>
            </div>
            <a href="{{ route('products.index') }}" class="section-link">See all →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($bestSellers->take(4) as $index => $product)
            <div class="product-card flex gap-3 p-3 items-start">
                <span class="rank-num flex-shrink-0">{{ $index + 1 }}</span>
                <a href="{{ route('products.show', $product->id) }}"
                   class="card-img w-20 h-20 flex-shrink-0 bg-amz-page rounded border border-amz-border flex items-center justify-center overflow-hidden">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-1" loading="lazy">
                    @else
                        <i data-lucide="image" class="w-7 h-7 text-amz-text-weak"></i>
                    @endif
                </a>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('products.show', $product->id) }}">
                        <h3 class="text-[13px] font-semibold text-amz-text line-clamp-2 hover:text-amz-blue mb-1">{{ $product->name }}</h3>
                    </a>
                    <p class="text-[16px] font-bold text-amz-text mb-2">${{ number_format($product->price, 2) }}</p>
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="amz-btn-cart w-full py-1.5 text-[12px] font-medium flex items-center justify-center gap-1">
                            <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i> Add to Cart
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
    @endisset

    {{-- ─── EXPLORE (all products grid) ─── --}}
    @isset($latestProducts)
    @if($latestProducts->count() > 0)
    <section class="bg-white border-t border-amz-border py-8">
        <div class="max-w-[1500px] mx-auto px-4">
            <div class="section-header">
                <h2 class="section-title">Explore Products</h2>
                <a href="{{ route('products.index') }}"
                   class="amz-btn-cart px-5 py-2 text-[13px] font-medium inline-flex items-center gap-1.5">
                    View All <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                @foreach($latestProducts as $product)
                @php
                    $avail = $product->available ?? ($product->quantity ?? 0);
                    $pct   = ($product->discount_price && $product->discount_price > $product->price)
                             ? round((1 - $product->price / $product->discount_price) * 100) : 0;
                @endphp
                <div class="product-card">
                    <a href="{{ route('products.show', $product->id) }}" class="card-img block relative bg-amz-page">
                        <div class="aspect-square p-3 flex items-center justify-center overflow-hidden">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain" loading="lazy">
                            @else
                                <i data-lucide="image" class="w-10 h-10 text-amz-text-weak"></i>
                            @endif
                        </div>
                        @if($pct > 0)
                        <span class="badge-pct absolute top-2 left-2 rounded">-{{ $pct }}%</span>
                        @endif
                    </a>
                    <div class="p-3">
                        <a href="{{ route('products.show', $product->id) }}">
                            <h3 class="text-[12px] text-amz-text line-clamp-2 leading-snug hover:text-amz-blue mb-1">{{ $product->name }}</h3>
                        </a>
                        {{-- Stars --}}
                        <div class="flex gap-0.5 mb-1">
                            @for($s = 1; $s <= 5; $s++)
                            <svg class="w-3 h-3 {{ $s <= 4 ? 'star-fill' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        {{-- Price --}}
                        <div class="mb-2">
                            @if($pct > 0)
                            <div class="flex items-baseline gap-1 flex-wrap">
                                <span class="text-amz-deal text-[11px] font-medium">Deal</span>
                                <span class="text-[16px] font-bold text-amz-text">${{ number_format($product->price, 2) }}</span>
                            </div>
                            <span class="text-[11px] text-amz-text-sec line-through">${{ number_format($product->discount_price, 2) }}</span>
                            @else
                            <span class="text-[16px] font-bold text-amz-text">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        @if($avail > 0)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="amz-btn-cart w-full py-1.5 text-[12px] font-medium flex items-center justify-center gap-1">
                                <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i> Add to Cart
                            </button>
                        </form>
                        @else
                        <button disabled class="w-full py-1.5 text-[12px] font-medium bg-amz-page text-amz-text-weak rounded-full border border-amz-border cursor-not-allowed">
                            Out of Stock
                        </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endisset

</main>
@endsection
@push('scripts')


<script>
    document.addEventListener('DOMContentLoaded', () => { lucide.createIcons(); });
</script>
@endpush
