@php
$notifications = auth()->check()
    ? auth()->user()->unreadNotifications
    : collect();

$nCount = $notifications->count();

$cartCount = 0;

if (auth()->check()) {

    $cart = auth()->user()->cart;

    if ($cart) {
        $cartCount = $cart->items()->sum('quantity');
    }

} else {

    $cartCount = collect(session('cart', []))->sum('quantity');

}
@endphp
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    .amz-nav-link {
        border: 1px solid transparent;
        border-radius: 3px;
        transition: border-color 100ms;
        white-space: nowrap;
    }
    .amz-nav-link:hover { border-color: white; }

    .nav-link { transition: all 100ms; border: 1px solid transparent; border-radius: 3px; }
    .nav-link:hover { border-color: white; }

    .mobile-sidebar { transform: translateX(-100%); transition: transform 300ms ease; }
    .mobile-sidebar.open { transform: translateX(0); }

    .amz-dropdown {
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        border: 1px solid #DDD;
        border-radius: 8px;
        background: white;
    }
    [x-cloak] { display: none !important; }

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.3); opacity: 1; }
}
.pulse-badge { animation: pulse 2s infinite; }
</style>

{{-- TOP PROMO BAR --}}
<div class="bg-gray-900 text-center py-1.5 text-[12px] text-gray-400 hidden sm:block">
    Free delivery on orders over $25 ·
    <a href="{{ route('products.index') }}" class="text-orange-400 hover:underline font-medium">Shop now →</a>
</div>

{{-- MAIN HEADER --}}
<header class="bg-[#131921] sticky top-0 z-50">
    <div class="max-w-[1500px] mx-auto px-3 sm:px-4">
        <div class="flex items-center h-14 gap-2">

            {{-- Hamburger Mobile --}}
            <button onclick="toggleMobileSidebar()"
                    class="lg:hidden text-white p-1.5 hover:bg-white/10 rounded border border-transparent hover:border-white/20 flex-shrink-0">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>

            {{-- LOGO --}}
            <a href="{{ route('home') }}"
               class="flex items-center gap-1 px-2 py-1.5 rounded hover:bg-white/10 border border-transparent hover:border-white/20 flex-shrink-0">
                <i data-lucide="shopping-bag" class="w-6 h-6 text-orange-400"></i>
                <span class="text-white font-bold text-lg hidden sm:block leading-none">
                    {{ config('app.name', 'MyShop') }}
                </span>
            </a>

            {{-- SEARCH --}}
            <div class="flex-1 max-w-3xl flex">
                <div class="relative flex-1">
                    <input type="text" id="amzSearchInput"
                           value="{{ request('search') }}"
                           placeholder="Search {{ config('app.name', 'MyShop') }}"
                           class="w-full h-10 px-4 text-[14px] rounded-l-md border border-gray-300 bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                           onkeydown="if(event.key==='Enter') doAmzSearch()">
                </div>
                <button onclick="doAmzSearch()"
                        class="bg-orange-400 hover:bg-orange-500 px-4 rounded-r-md transition-colors flex-shrink-0 flex items-center text-gray-900">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>
            </div>

            {{-- RIGHT ICONS --}}
            <div class="flex items-center gap-0.5 flex-shrink-0">

                @auth
                {{-- Account --}}
                <div class="relative hidden sm:block" id="accountWrapper">
                    <button onclick="toggleAccount()"
                            class="amz-nav-link flex items-center gap-1 px-2 py-1.5 text-white">
                        <div class="leading-tight text-left">
                            <p class="text-[11px] text-gray-400">Hello, {{ auth()->user()->name }}</p>
                            <p class="text-[13px] font-bold flex items-center gap-0.5">
                                Account <i data-lucide="chevron-down" class="w-3 h-3 text-gray-500"></i>
                            </p>
                        </div>
                    </button>
                    <div id="accountDropdown"
                         class="amz-dropdown hidden absolute right-0 top-full mt-0 w-64 z-50 overflow-hidden">
                        <div class="bg-gray-700 px-4 py-3 border-b border-gray-600 flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full bg-orange-400 flex items-center justify-center text-gray-900 font-bold text-[15px] flex-shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-[13px] font-bold text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-gray-300 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[13px] text-gray-900 hover:bg-gray-100">
                                <i data-lucide="user" class="w-4 h-4"></i> My Profile
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[13px] text-gray-900 hover:bg-gray-100">
                                <i data-lucide="package" class="w-4 h-4"></i> Your Orders
                            </a>
                            <a href="{{ route('addresses.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[13px] text-gray-900 hover:bg-gray-100">
                                <i data-lucide="map-pin" class="w-4 h-4"></i> Your Addresses
                            </a>
                        </div>
                        <div class="border-t border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-[13px] text-red-600 hover:bg-red-50">
                                    <i data-lucide="log-out" class="w-4 h-4"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Returns --}}
                <a href="{{ route('orders.index') }}" class="amz-nav-link hidden sm:flex items-center gap-1 px-2 py-1.5 text-white">
                    <div class="leading-tight">
                        <p class="text-[11px] text-gray-400">Returns</p>
                        <p class="text-[13px] font-bold">& Orders</p>
                    </div>
                </a>

              {{-- NOTIFICATIONS --}}
<div class="relative" id="notifWrapper"
     x-data="{ notifOpen: false }"
     x-init=""
     @click.outside="notifOpen = false">

    <button @click="notifOpen = !notifOpen"
            class="relative flex items-center justify-center w-10 h-10 text-white hover:bg-white/10 rounded border border-transparent hover:border-white/20 transition-colors"
            title="Notifications">
        <i data-lucide="bell" class="w-5 h-5"></i>
        @if($nCount > 0)
        <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] bg-red-600 text-white text-[9px] font-bold rounded-full flex items-center justify-center px-1 pulse-badge">
            {{ $nCount > 9 ? '9+' : $nCount }}
        </span>
        @endif
    </button>

    <div x-show="notifOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="absolute right-0 top-full mt-2 w-80 z-50 rounded-lg overflow-hidden"
         style="box-shadow: 0 8px 32px rgba(0,0,0,0.2); border: 1px solid #DDD; background: white;">

        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-[14px] font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="bell" class="w-4 h-4 text-red-600"></i>
                Notifications
                @if($nCount > 0)
                <span class="bg-red-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $nCount }}</span>
                @endif
            </h3>
            @if($nCount > 0)
            <button onclick="markAllRead()" class="text-[11px] text-blue-600 hover:underline font-semibold">
                Mark all read
            </button>
            @endif
        </div>

        <div class="max-h-64 overflow-y-auto">
            @forelse($notifications->take(5) as $note)
            <div class="flex items-start gap-3 px-4 py-3 border-b border-gray-100 hover:bg-gray-50 {{ !$note->read_at ? 'bg-blue-50' : '' }}">
                <div class="flex-shrink-0 mt-1.5">
                    <span class="block w-2.5 h-2.5 rounded-full {{ !$note->read_at ? 'bg-red-500 pulse-badge' : 'bg-gray-300' }}"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[12px] font-semibold text-gray-900">{{ $note->data['title'] ?? 'Notification' }}</p>
                    <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2">{{ $note->data['message'] ?? '' }}</p>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $note->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="px-4 py-8 text-center">
                <i data-lucide="bell-off" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                <p class="text-[13px] text-gray-400">No new notifications</p>
            </div>
            @endforelse
        </div>

        @if($nCount > 5)
        <div class="px-4 py-2.5 border-t border-gray-200 bg-gray-50 text-center">
            <span class="text-[12px] text-gray-500">+{{ $nCount - 5 }} more</span>
        </div>
        @endif
    </div>
</div>

                @else
                {{-- GUEST --}}
                <a href="{{ route('login') }}" class="amz-nav-link hidden sm:flex items-center gap-1 px-2 py-1.5 text-white">
                    <div class="leading-tight">
                        <p class="text-[11px] text-gray-400">Hello, sign in</p>
                        <p class="text-[13px] font-bold">Account</p>
                    </div>
                </a>
                @endauth

                {{-- CART --}}
                <a href="{{ route('cart.index') }}"
                   class="amz-nav-link flex items-center gap-1 px-2 py-1.5 text-white">
                    <div class="relative">
                        <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                        <span id="cart-badge"
                              class="absolute -top-2 -right-1 min-w-[18px] h-[18px] bg-orange-400 text-gray-900 text-[10px] font-extrabold rounded-full flex items-center justify-center leading-none px-1 {{ $cartCount > 0 ? '' : 'hidden' }}">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    </div>
                    <span class="text-[13px] font-bold hidden sm:block">Cart</span>
                </a>

            </div>
        </div>
    </div>
</header>

{{-- SUB NAVIGATION --}}
@hasSection('custom-navigation')
    @yield('custom-navigation')
@else
<nav class="bg-[#232F3E] overflow-x-auto">
    <div class="max-w-[1500px] mx-auto px-3 sm:px-4">
        <div class="flex items-center gap-0.5 h-11 text-white text-[13px] whitespace-nowrap">
            <a href="{{ route('home') }}" class="nav-link px-2.5 py-1.5 {{ request()->routeIs('home') ? 'border-b-2 border-orange-400' : '' }}">Home</a>
            <a href="{{ route('products.index') }}" class="nav-link px-2.5 py-1.5 {{ request()->routeIs('products.*') ? 'border-b-2 border-orange-400' : '' }}">All Products</a>
            @auth
            <a href="{{ route('orders.index') }}" class="nav-link px-2.5 py-1.5 {{ request()->routeIs('orders.*') ? 'border-b-2 border-orange-400' : '' }}">Your Orders</a>
            @endauth
            <a href="#deals" class="nav-link px-2.5 py-1.5 text-orange-400 font-semibold">🔥 Deals</a>
        </div>
    </div>
</nav>
@endif

{{-- MOBILE SIDEBAR --}}
<div id="mobileSidebarOverlay" class="fixed inset-0 bg-black/50 z-[998] hidden lg:hidden" onclick="toggleMobileSidebar()"></div>
<div id="mobileSidebar" class="mobile-sidebar fixed top-0 left-0 bottom-0 w-[280px] bg-[#131921] z-[999] lg:hidden overflow-y-auto flex flex-col shadow-2xl">
    <div class="flex items-center justify-between px-4 py-3 border-b border-white/10 bg-[#232F3E]">
        @auth
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full bg-orange-400 flex items-center justify-center text-gray-900 font-bold text-[14px]">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="text-[13px] font-bold text-white">{{ auth()->user()->name }}</span>
        </div>
        @else
        <span class="text-white font-bold text-[15px]">{{ config('app.name', 'MyShop') }}</span>
        @endauth
        <button onclick="toggleMobileSidebar()" class="text-gray-400 hover:text-white p-1.5">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    @guest
    <div class="px-4 py-4 border-b border-white/10 space-y-2">
        <a href="{{ route('login') }}" class="block w-full text-center py-2.5 text-[14px] font-bold text-gray-900 bg-yellow-400 hover:bg-yellow-500 rounded-full">Sign In</a>
        @if(Route::has('register'))
        <p class="text-center text-[12px] text-gray-400">New? <a href="{{ route('register') }}" class="text-orange-400 font-semibold hover:underline">Register</a></p>
        @endif
    </div>
    @endguest

    <nav class="flex-1 py-2">
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] text-gray-200 hover:bg-white/10">
            <i data-lucide="home" class="w-4 h-4 text-orange-400"></i> Home
        </a>
        <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] text-gray-200 hover:bg-white/10">
            <i data-lucide="layout-grid" class="w-4 h-4 text-orange-400"></i> All Products
        </a>
        @auth
        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] text-gray-200 hover:bg-white/10">
            <i data-lucide="package" class="w-4 h-4 text-orange-400"></i> Your Orders
        </a>
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-[13px] text-gray-200 hover:bg-white/10">
            <i data-lucide="user" class="w-4 h-4 text-orange-400"></i> Profile
        </a>
        @endauth
    </nav>

    <div class="border-t border-white/10 p-4">
        @auth
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 text-[13px] text-red-400 hover:bg-red-500/10 rounded-lg">
                <i data-lucide="log-out" class="w-4 h-4"></i> Sign Out
            </button>
        </form>
        @endauth
        <p class="text-[10px] text-gray-600 text-center mt-3">© {{ date('Y') }} {{ config('app.name', 'MyShop') }}</p>
    </div>
</div>

<script>
    // ─── NOTIFICATIONS ───
function toggleNotif() {
    const dropdown = document.getElementById('notifDropdown');

    if (!dropdown) {
        console.warn('notifDropdown not found');
        return;
    }

    dropdown.classList.toggle('hidden');

    const account = document.getElementById('accountDropdown');
    if (account) account.classList.add('hidden');
}

    // ─── ACCOUNT ───
    function toggleAccount() {
        document.getElementById('accountDropdown').classList.toggle('hidden');
        document.getElementById('notifDropdown').classList.add('hidden');
    }

    // ─── CLOSE ON OUTSIDE CLICK ───
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#notifWrapper')) {
            document.getElementById('notifDropdown')?.classList.add('hidden');
        }
        if (!e.target.closest('#accountWrapper')) {
            document.getElementById('accountDropdown')?.classList.add('hidden');
        }
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

    // ─── MARK ALL NOTIFICATIONS READ ───
    function markAllRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
            }
        }).finally(() => setTimeout(() => location.reload(), 300));
    }

    // ─── CART BADGE UPDATE (يُستدعى من صفحات أخرى) ───
    function updateCartBadge(count) {
        const badge = document.getElementById('cart-badge');
        if (!badge) return;
        badge.textContent = count > 99 ? '99+' : count;
        badge.classList.toggle('hidden', count <= 0);
    }

    // ─── SEARCH ───
    function doAmzSearch() {
        const q = document.getElementById('amzSearchInput')?.value.trim();
        const base = '{{ route('products.index') }}';
        window.location.href = q ? `${base}?search=${encodeURIComponent(q)}` : base;
    }
</script>
