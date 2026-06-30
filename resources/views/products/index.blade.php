

@extends('layouts.app')
@section('content')
    <!-- ═══════════ BREADCRUMB ═══════════ -->
    <div class="bg-white border-b border-amz-border">
        <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-2">
            <nav class="flex items-center gap-1 text-[12px]">
                <a href="{{ route('home') }}" class="text-amz-blue hover:text-amz-link-hover hover:underline">{{ config('app.name', 'MyShop') }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
                <span class="text-amz-text font-medium">Products</span>
            </nav>
        </div>
    </div>

    <!-- ═══════════ MAIN CONTENT ═══════════ -->
    <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-4 flex gap-4">

        <!-- ═══════════ LEFT SIDEBAR ═══════════ -->
<aside class="hidden lg:block w-[240px] flex-shrink-0">
    <div class="bg-white rounded-lg border border-amz-border overflow-hidden sticky top-[108px]">

        <!-- Active Filters Display -->
        @if(request()->hasAny(['search', 'category_id', 'price_min', 'price_max', 'availability']))
        <div class="p-4 border-b border-amz-border-light bg-amz-page/50">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-[13px] font-bold text-amz-text">Active Filters</h3>
                <a href="{{ route('products.index') }}" class="text-[12px] text-amz-blue hover:text-amz-link-hover hover:underline">Clear all</a>
            </div>
            <div class="flex flex-wrap gap-1.5">
                @if(request('search'))
                <span class="inline-flex items-center gap-1 bg-white border border-amz-border rounded-full px-2.5 py-1 text-[11px]">
                    "{{ request('search') }}"
                    <a href="{{ route('products.index', array_filter(request()->query(), fn($k) => $k !== 'search')) }}" class="text-amz-text-weak hover:text-amz-deal">&times;</a>
                </span>
                @endif
                @if(request('category_id') && $selectedCategory = \App\Models\Category::find(request('category_id')))
                <span class="inline-flex items-center gap-1 bg-white border border-amz-border rounded-full px-2.5 py-1 text-[11px]">
                    {{ $selectedCategory->name }}
                    <a href="{{ route('products.index', array_filter(request()->query(), fn($k) => $k !== 'category_id')) }}" class="text-amz-text-weak hover:text-amz-deal">&times;</a>
                </span>
                @endif
                @if(request('price_min') || request('price_max'))
                <span class="inline-flex items-center gap-1 bg-white border border-amz-border rounded-full px-2.5 py-1 text-[11px]">
                    ${{ request('price_min') ?? '0' }} - ${{ request('price_max') ?? '∞' }}
                    <a href="{{ route('products.index', array_filter(request()->query(), fn($k) => !in_array($k, ['price_min', 'price_max']))) }}" class="text-amz-text-weak hover:text-amz-deal">&times;</a>
                </span>
                @endif
                @if(request('availability'))
                <span class="inline-flex items-center gap-1 bg-white border border-amz-border rounded-full px-2.5 py-1 text-[11px]">
                    {{ request('availability') === 'in_stock' ? 'In Stock' : 'Out of Stock' }}
                    <a href="{{ route('products.index', array_filter(request()->query(), fn($k) => $k !== 'availability')) }}" class="text-amz-text-weak hover:text-amz-deal">&times;</a>
                </span>
                @endif
            </div>
        </div>
        @endif

        <!-- Department -->
        <div class="sidebar-section p-4">
            <h3 class="text-[15px] font-bold text-amz-text mb-3">Department</h3>
            <div class="space-y-2">
                <label class="flex items-start gap-2 cursor-pointer">
                    <input type="radio" name="category_id" value="" {{ !request('category_id') ? 'checked' : '' }} class="filter-radio mt-0.5" onchange="applyFilters()">
                    <span class="text-[13px] text-amz-text">All Departments</span>
                </label>
                @foreach($categories as $filterCat)
                <label class="flex items-start gap-2 cursor-pointer">
                    <input type="radio" name="category_id" value="{{ $filterCat->id }}" {{ request('category_id') == $filterCat->id ? 'checked' : '' }} class="filter-radio mt-0.5" onchange="applyFilters()">
                    <span class="text-[13px] text-amz-text {{ request('category_id') == $filterCat->id ? 'text-amz-orange font-medium' : '' }}">{{ $filterCat->name }}
                        <span class="text-amz-text-weak">({{ $filterCat->products_count }})</span>
                    </span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Price -->
        <div class="sidebar-section p-4">
            <h3 class="text-[15px] font-bold text-amz-text mb-3">Price</h3>
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer">
                 <input type="checkbox"
       class="filter-check price-check"
       data-min=""
       data-max="25"
       {{ (!request('price_min') && !request('price_max')) ? 'checked' : '' }}
       onchange="applyPriceCheck(this)">
                    <span class="text-[13px] text-amz-text">Under $25</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="filter-check price-check" data-min="25" data-max="50" {{ (request('price_min') == '25' && request('price_max') == '50') ? 'checked' : '' }} onchange="applyPriceCheck(this)">
                    <span class="text-[13px] text-amz-text">$25 to $50</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="filter-check price-check" data-min="50" data-max="100" {{ (request('price_min') == '50' && request('price_max') == '100') ? 'checked' : '' }} onchange="applyPriceCheck(this)">
                    <span class="text-[13px] text-amz-text">$50 to $100</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="filter-check price-check" data-min="100" data-max="" {{ (request('price_min') == '100' && !request('price_max')) ? 'checked' : '' }} onchange="applyPriceCheck(this)">
                    <span class="text-[13px] text-amz-text">$100 & Above</span>
                </label>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <input type="number" id="priceMinInput" placeholder="Min" value="{{ request('price_min') }}" class="amz-input w-full h-8 px-2 text-[13px] rounded" min="0">
                <span class="text-amz-text-weak">—</span>
                <input type="number" id="priceMaxInput" placeholder="Max" value="{{ request('price_max') }}" class="amz-input w-full h-8 px-2 text-[13px] rounded" min="0">
            </div>
            <button onclick="applyCustomPrice()" class="mt-2 w-full amz-btn-cart py-1.5 text-[13px] font-medium">Go</button>
        </div>

        <!-- Availability -->
        <div class="sidebar-section p-4">
            <h3 class="text-[15px] font-bold text-amz-text mb-3">Availability</h3>
            <div class="space-y-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="availability" value="" {{ !request('availability') ? 'checked' : '' }} class="filter-radio" onchange="applyFilters()">
                    <span class="text-[13px] text-amz-text">Any</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="availability" value="in_stock" {{ request('availability') === 'in_stock' ? 'checked' : '' }} class="filter-radio" onchange="applyFilters()">
                    <span class="text-[13px] text-amz-text">In Stock</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="availability" value="out_of_stock" {{ request('availability') === 'out_of_stock' ? 'checked' : '' }} class="filter-radio" onchange="applyFilters()">
                    <span class="text-[13px] text-amz-text">Out of Stock</span>
                </label>
            </div>
        </div>

        <!-- Customer Review (visual only for now) -->
        <div class="sidebar-section p-4">
            <h3 class="text-[15px] font-bold text-amz-text mb-3">Customer Review</h3>
            <div class="space-y-2">
                @for($i = 4; $i >= 1; $i--)
                <div class="flex items-center gap-2">
                    <span class="text-[13px] text-amz-text">{{ $i }}</span>
                    <div class="flex">
                        @for($s = 1; $s <= 4; $s++)
                        <svg class="w-3.5 h-3.5 {{ $s <= $i ? 'star-fill' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                        <svg class="w-3.5 h-3.5 star-empty" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <span class="text-[13px] text-amz-blue">& Up</span>
                </div>
                @endfor
            </div>
        </div>

        @auth
        <div class="p-4">
            <a href="{{ route('products.create') }}" class="flex items-center justify-center gap-2 w-full amz-btn-cart py-2.5 text-[13px] font-bold">
                <i data-lucide="plus" class="w-4 h-4"></i> Add New Product
            </a>
        </div>
        @endauth
    </div>
</aside>

        <!-- ═══════════ PRODUCT GRID ═══════════ -->
        <div class="flex-1 min-w-0">

            <div class="bg-white rounded-lg border border-amz-border px-4 py-3 mb-4 flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <button onclick="toggleMobileSidebar()" class="lg:hidden p-1.5 rounded hover:bg-amz-page text-amz-text-sec">
                        <i data-lucide="sliders-horizontal" class="w-5 h-5"></i>
                    </button>
                    <p class="text-[14px] text-amz-text-sec">
                        <span class="font-bold text-amz-text">{{ $products->total() }}</span> results
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <label class="text-[13px] text-amz-text-sec">Sort by:</label>
                    <select id="amzSort" onchange="handleAmzSort(this.value)" class="amz-input px-3 py-1.5 rounded-md text-[13px] bg-white cursor-pointer">
                        <option value="featured">Featured</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="name-asc">Name: A to Z</option>
                        <option value="name-desc">Name: Z to A</option>
                        <option value="newest">Newest Arrivals</option>
                    </select>
                </div>
            </div>

            @if($products->count() > 0)

            <div id="amzProductGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-3">

                @foreach($products as $product)
                <div class="product-card bg-white rounded-lg border border-amz-border overflow-hidden group"
                     data-category="{{ $product->category_id }}"
                     data-price="{{ $product->price }}"
                     data-name="{{ $product->name }}">

                    {{-- ─── IMAGE: يستخدم image_url accessor ─── --}}
                    <a href="{{ route('products.show', $product->id) }}" class="card-img block relative overflow-hidden bg-white">
                        <div class="aspect-square p-4 flex items-center justify-center">
                            @if($product->image_url && !str_contains($product->image_url, 'default.png'))
                                <img src="{{ $product->image_url }}"
                                     alt="{{ $product->name }}"
                                     class="max-w-full max-h-full object-contain"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex flex-col items-center justify-center text-amz-text-weak\'><svg class=\'w-10 h-10 mb-1\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\' ry=\'2\'></rect><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'></circle><polyline points=\'21 15 16 10 5 21\'></polyline></svg><span class=\'text-[11px]\'>No image</span></div>'">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-amz-text-weak">
                                    <i data-lucide="image" class="w-10 h-10 mb-1"></i>
                                    <span class="text-[11px]">No image</span>
                                </div>
                            @endif
                        </div>

                        @if($product->discount_price && $product->discount_price > $product->price)
                            @php $discPct = round((1 - $product->price / $product->discount_price) * 100); @endphp
                            <span class="absolute top-2 left-2 bg-amz-deal text-white text-[11px] font-bold px-2 py-0.5 rounded">
                                -{{ $discPct }}%
                            </span>
                        @endif
                    </a>

                    <div class="p-3">
                        <a href="{{ route('products.show', $product->id) }}">
                            <h3 class="text-[13px] text-amz-text line-clamp-2 leading-snug hover:text-amz-link-hover transition-colors mb-1">
                                {{ $product->name }}
                            </h3>
                        </a>

                        {{-- Stars --}}
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <div class="flex">
                                @php $stars = $product->relationLoaded('reviews') && $product->reviews->count() > 0 ? round($product->reviews->avg('rating')) : rand(3, 5); @endphp
                                @for($s = 1; $s <= 5; $s++)
                                <svg class="w-3 h-3 {{ $s <= $stars ? 'star-fill' : 'star-empty' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="text-[12px] text-amz-blue">{{ $product->relationLoaded('reviews') ? $product->reviews->count() : rand(50, 2000) }}</span>
                        </div>

                        {{-- Price --}}
                        <div class="mb-2">
                            @if($product->discount_price && $product->discount_price > $product->price)
                                <div class="flex items-baseline gap-1.5 flex-wrap">
                                    <span class="text-amz-deal text-[12px] font-medium">Deal</span>
                                    <span class="text-[18px] font-bold text-amz-text leading-none">${{ number_format($product->price, 2) }}</span>
                                </div>
                                <span class="text-[12px] text-amz-text-sec line-through">List: ${{ number_format($product->discount_price, 2) }}</span>
                            @else
                                <span class="text-[18px] font-bold text-amz-text leading-none">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>

                        {{-- Prime --}}
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="inline-flex items-center gap-0.5 text-[12px] text-amz-blue font-bold">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="#007185"><rect x="5" y="13" width="14" height="5" rx="1" fill="none" stroke="#007185" stroke-width="1.2"/><text x="12" y="17" font-size="5" font-weight="bold" fill="#007185" text-anchor="middle" font-family="Arial">prime</text></svg>
                                prime
                            </span>
                            <span class="text-[12px] text-amz-text-sec">FREE delivery</span>
                        </div>

                        {{-- ─── STOCK: يستخدم available accessor (quantity - reserved) ─── --}}
                        @if($product->available > 10)
                            <p class="text-[12px] text-amz-green font-medium mb-2">In Stock</p>
                        @elseif($product->available > 0)
                            <p class="text-[12px] text-amz-orange font-medium mb-2">Only {{ $product->available }} left in stock</p>
                        @else
                            <p class="text-[12px] text-amz-deal font-medium mb-2">Currently unavailable</p>
                        @endif

                        {{-- ─── ADD TO CART: يستخدم available ─── --}}
                        @if($product->available > 0)
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-2 text-[13px] font-bold text-white bg-orange-500 hover:bg-orange-600 active:bg-orange-700 rounded-md flex items-center justify-center gap-1.5 transition-colors">
                                    <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full py-2 text-[13px] font-medium bg-gray-100 text-amz-text-weak rounded-full border border-amz-border cursor-not-allowed">
                                Out of Stock
                            </button>
                        @endif

                        @auth
                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-amz-border-light">
                            <a href="{{ route('products.edit', $product->id) }}" class="text-[11px] font-medium text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('products.destroy', $product->id) }}" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[11px] font-medium text-red-600 hover:text-red-800 hover:underline transition-colors">Delete</button>
                            </form>
                        </div>
                        @endauth
                    </div>
                </div>
                @endforeach

            </div>

            @else

            <div class="bg-white rounded-lg border border-amz-border p-12 text-center">
                <div class="w-20 h-20 bg-amz-page rounded-full flex items-center justify-center mx-auto mb-5">
                    <i data-lucide="search-x" class="w-10 h-10 text-amz-text-weak"></i>
                </div>
                <h2 class="text-xl font-bold text-amz-text mb-2">No products found</h2>
                <p class="text-[14px] text-amz-text-sec mb-6 max-w-md mx-auto">Try adjusting your search or filters.</p>
                <div class="flex items-center justify-center gap-3">
                    <a href="{{ route('products.index') }}" class="amz-btn-cart px-6 py-2.5 text-[14px] font-medium">Clear filters</a>
                    @auth
                    <a href="{{ route('products.create') }}" class="amz-btn-buy px-6 py-2.5 text-[14px] font-medium text-white">Add a product</a>
                    @endauth
                </div>
            </div>

            @endif

            {{-- Pagination --}}
            @if($products->hasPages())
            <div class="flex items-center justify-center gap-1 mt-8 mb-4">
                @if(!$products->onFirstPage())
                    <a href="{{ $products->previousPageUrl() }}" class="px-3 py-1.5 border border-amz-border rounded text-[13px] text-amz-blue hover:bg-amz-page transition-colors">← Previous</a>
                @endif

                @for($page = 1; $page <= min($products->lastPage(), 7); $page++)
                    @if($page === $products->currentPage())
                        <span class="px-3 py-1.5 bg-amz-orange text-white rounded text-[13px] font-bold">{{ $page }}</span>
                    @else
                        <a href="{{ $products->url($page) }}" class="px-3 py-1.5 border border-amz-border rounded text-[13px] text-amz-blue hover:bg-amz-page transition-colors">{{ $page }}</a>
                    @endif
                @endfor

                @if($products->lastPage() > 7)
                    <span class="px-2 text-amz-text-weak">...</span>
                    <a href="{{ $products->url($products->lastPage()) }}" class="px-3 py-1.5 border border-amz-border rounded text-[13px] text-amz-blue hover:bg-amz-page transition-colors">{{ $products->lastPage() }}</a>
                @endif

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="px-3 py-1.5 border border-amz-border rounded text-[13px] text-amz-blue hover:bg-amz-page transition-colors">Next →</a>
                @endif
            </div>
            <div class="text-center">
                <a href="#" class="text-[13px] text-amz-blue hover:text-amz-link-hover hover:underline">Back to top</a>
            </div>
            @endif

        </div>
    </div>



    <div id="toastContainer" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] flex flex-col items-center gap-2"></div>

<script>
    // ─── BUILD FILTER URL ───
    // يجمع كل الفلاتر الحالية ويبني URL
    function buildFilterUrl(overrides = {}) {
        const params = new URLSearchParams();

        // Search
        const search = document.getElementById('amzSearchInput')?.value.trim();
        if (search) params.set('search', search);

        // Department
        const deptRadio = document.querySelector('input[name="category_id"]:checked');
        if (deptRadio && deptRadio.value) params.set('category_id', deptRadio.value);

        // Price
        const priceMin = document.getElementById('priceMinInput')?.value;
        const priceMax = document.getElementById('priceMaxInput')?.value;
        if (priceMin) params.set('price_min', priceMin);
        if (priceMax) params.set('price_max', priceMax);

        // Availability
        const availRadio = document.querySelector('input[name="availability"]:checked');
        if (availRadio && availRadio.value) params.set('availability', availRadio.value);

        // Sort
        const sortSelect = document.getElementById('amzSort');
        if (sortSelect && sortSelect.value !== 'featured') params.set('sort', sortSelect.value);

        // Overrides (للـ price checkboxes)
        Object.entries(overrides).forEach(([key, val]) => {
            if (val === null || val === undefined || val === '') {
                params.delete(key);
            } else {
                params.set(key, val);
            }
        });

        const queryString = params.toString();
        return '{{ route('products.index') }}' + (queryString ? '?' + queryString : '');
    }

    // ─── APPLY ALL FILTERS ───
    // يُستدعى عند تغيير Department أو Availability
    function applyFilters() {
        // لما يغير الـ price checkbox، نستبدل قيم الـ inputs
        document.getElementById('priceMinInput').value = '';
        document.getElementById('priceMaxInput').value = '';
        document.querySelectorAll('.price-check').forEach(c => c.checked = false);

        window.location.href = buildFilterUrl();
    }

    // ─── PRICE CHECKBOX ───
    // لما يضغط على "Under $25" مثلاً
    function applyPriceCheck(checkbox) {
        // يلغي تحديد كل الـ checkboxes الأخرى
        document.querySelectorAll('.price-check').forEach(c => {
            if (c !== checkbox) c.checked = false;
        });

        if (checkbox.checked) {
            document.getElementById('priceMinInput').value = checkbox.dataset.min;
            document.getElementById('priceMaxInput').value = checkbox.dataset.max;
        } else {
            document.getElementById('priceMinInput').value = '';
            document.getElementById('priceMaxInput').value = '';
        }

        window.location.href = buildFilterUrl();
    }

    // ─── CUSTOM PRICE (Min/Max inputs + Go) ───
    function applyCustomPrice() {
        // يلغي تحديد كل الـ checkboxes
        document.querySelectorAll('.price-check').forEach(c => c.checked = false);

        window.location.href = buildFilterUrl();
    }

    // ─── SEARCH ───
    function doAmzSearch() {
        const q = document.getElementById('amzSearchInput').value.trim();
        if (q) {
            window.location.href = buildFilterUrl({ search: q });
        } else {
            window.location.href = buildFilterUrl({ search: null });
        }
    }

    // ─── SORT ───
    function handleAmzSort(value) {
        window.location.href = buildFilterUrl({ sort: value === 'featured' ? null : value });
    }

    // ─── SEARCH CAT DROPDOWN (زينة — ما يؤثر على الفلتر) ───
    function toggleSearchCat() {
        document.getElementById('searchCatDropdown').classList.toggle('hidden');
    }
    function selectSearchCat(name) {
        document.getElementById('searchCatLabel').textContent = name;
        document.getElementById('searchCatDropdown').classList.add('hidden');
    }
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#searchCatBtn') && !e.target.closest('#searchCatDropdown')) {
            document.getElementById('searchCatDropdown')?.classList.add('hidden');
        }
    });

    // ─── MOBILE SIDEBAR ───
    function toggleMobileSidebar() {
        document.getElementById('mobileSidebar').classList.toggle('open');
        document.getElementById('mobileSidebarOverlay').classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }

    // ─── INIT ───
    lucide.createIcons();


    // ─── NOTIFICATIONS ───
function toggleNotif() {
    const dropdown = document.getElementById('notifDropdown');
    dropdown.classList.toggle('hidden');
}

// Close when clicking outside
document.addEventListener('click', (e) => {
    const wrapper = document.getElementById('notifWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('notifDropdown')?.classList.add('hidden');
    }
});

function markAllRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            'Accept': 'application/json',
        }
    }).finally(() => {
        setTimeout(() => location.reload(), 300);
    });
}
</script>

@endsection

@push('scripts')
<script>
    {{-- انقل هون فقط: buildFilterUrl, applyFilters, applyPriceCheck, applyCustomPrice, handleAmzSort, toggleSearchCat, selectSearchCat --}}
    {{-- احذف: toggleMobileSidebar, toggleNotif, markAllRead, doAmzSearch (هدول انتقلوا للـ navigation) --}}

    function buildFilterUrl(overrides = {}) {
        const params = new URLSearchParams();
        const search = document.getElementById('amzSearchInput')?.value.trim();
        if (search) params.set('search', search);
        const deptRadio = document.querySelector('input[name="category_id"]:checked');
        if (deptRadio?.value) params.set('category_id', deptRadio.value);
        const priceMin = document.getElementById('priceMinInput')?.value;
        const priceMax = document.getElementById('priceMaxInput')?.value;
        if (priceMin) params.set('price_min', priceMin);
        if (priceMax) params.set('price_max', priceMax);
        const availRadio = document.querySelector('input[name="availability"]:checked');
        if (availRadio?.value) params.set('availability', availRadio.value);
        const sortSelect = document.getElementById('amzSort');
        if (sortSelect?.value && sortSelect.value !== 'featured') params.set('sort', sortSelect.value);
        Object.entries(overrides).forEach(([key, val]) => {
            if (val === null || val === undefined || val === '') params.delete(key);
            else params.set(key, val);
        });
        const qs = params.toString();
        return '{{ route('products.index') }}' + (qs ? '?' + qs : '');
    }

    function applyFilters() {
        document.getElementById('priceMinInput').value = '';
        document.getElementById('priceMaxInput').value = '';
        document.querySelectorAll('.price-check').forEach(c => c.checked = false);
        window.location.href = buildFilterUrl();
    }

    function applyPriceCheck(checkbox) {
        document.querySelectorAll('.price-check').forEach(c => { if (c !== checkbox) c.checked = false; });
        document.getElementById('priceMinInput').value = checkbox.checked ? checkbox.dataset.min : '';
        document.getElementById('priceMaxInput').value = checkbox.checked ? checkbox.dataset.max : '';
        window.location.href = buildFilterUrl();
    }

    function applyCustomPrice() {
        document.querySelectorAll('.price-check').forEach(c => c.checked = false);
        window.location.href = buildFilterUrl();
    }

    function handleAmzSort(value) {
        window.location.href = buildFilterUrl({ sort: value === 'featured' ? null : value });
    }

    function addToCart(productId, btn) {
        btn.disabled = true;
        btn.innerHTML = '...';
        fetch(`/cart/${productId}/add`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.cartCount !== undefined) updateCartBadge(data.cartCount);
            btn.innerHTML = '✓ Added!';
            btn.style.background = 'linear-gradient(to bottom, #86efac, #4ade80)';
            setTimeout(() => {
                btn.disabled = false;
                btn.style.background = '';
                btn.innerHTML = '<i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i> Add to Cart';
                lucide.createIcons();
            }, 1500);
        })
        .catch(() => { btn.disabled = false; btn.innerHTML = 'Add to Cart'; });
    }

    lucide.createIcons();
</script>
@endpush
