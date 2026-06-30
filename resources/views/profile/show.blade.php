@extends('layouts.app')

@section('content')

{{-- ═══════════ BREADCRUMB ═══════════ --}}
<div class="bg-white border-b border-amz-border">
    <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-2">
        <nav class="flex items-center gap-1 text-[12px]">
            <a href="{{ route('home') }}" class="text-amz-blue hover:text-amz-link-hover hover:underline">{{ config('app.name', 'MyShop') }}</a>
            <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
            <a href="{{ route('profile.show') }}" class="text-amz-blue hover:text-amz-link-hover hover:underline">Your Account</a>
            <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
            <span class="text-amz-text font-medium">Profile</span>
        </nav>
    </div>
</div>

{{-- ═══════════ MAIN CONTENT ═══════════ --}}
<div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-6">

    {{-- Page Title --}}
    <h1 class="text-[28px] font-normal text-amz-text mb-6">Your Account</h1>

    {{-- ═══════════ QUICK ACCESS CARDS ═══════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

        {{-- Orders Card --}}
        <a href="{{ route('orders.index') }}"
           class="bg-white rounded-lg border border-amz-border p-5 flex items-start gap-4 hover:border-amz-orange hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-amz-orange/10 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-amz-orange/20 transition-colors">
                <i data-lucide="package" class="w-6 h-6 text-amz-orange"></i>
            </div>
            <div>
                <h3 class="text-[16px] font-bold text-amz-text mb-1">Your Orders</h3>
                <p class="text-[13px] text-amz-text-sec">Track, return, or buy again</p>
            </div>
        </a>

        {{-- Security Card --}}
        <a href="{{ route('profile.security') }}"
           class="bg-white rounded-lg border border-amz-border p-5 flex items-start gap-4 hover:border-amz-orange hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-amz-blue/10 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-amz-blue/20 transition-colors">
                <i data-lucide="shield" class="w-6 h-6 text-amz-blue"></i>
            </div>
            <div>
                <h3 class="text-[16px] font-bold text-amz-text mb-1">Login & Security</h3>
                <p class="text-[13px] text-amz-text-sec">Edit name, email, and password</p>
            </div>
        </a>

        {{-- Addresses Card --}}
        <a href="{{ route('addresses.index') }}"
           class="bg-white rounded-lg border border-amz-border p-5 flex items-start gap-4 hover:border-amz-orange hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-amz-green/10 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-amz-green/20 transition-colors">
                <i data-lucide="map-pin" class="w-6 h-6 text-amz-green"></i>
            </div>
            <div>
                <h3 class="text-[16px] font-bold text-amz-text mb-1">Your Addresses</h3>
                <p class="text-[13px] text-amz-text-sec">Edit, remove, or set default addresses</p>
            </div>
        </a>

        {{-- Payment Methods Card --}}
        <a href="{{ route('payment-methods.index') }}"
           class="bg-white rounded-lg border border-amz-border p-5 flex items-start gap-4 hover:border-amz-orange hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-purple-200 transition-colors">
                <i data-lucide="credit-card" class="w-6 h-6 text-purple-600"></i>
            </div>
            <div>
                <h3 class="text-[16px] font-bold text-amz-text mb-1">Payment Methods</h3>
                <p class="text-[13px] text-amz-text-sec">Manage payment options and gift cards</p>
            </div>
        </a>

        {{-- Reviews Card --}}
        <a href="{{ route('reviews.index') }}"
           class="bg-white rounded-lg border border-amz-border p-5 flex items-start gap-4 hover:border-amz-orange hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-yellow-100 transition-colors">
                <i data-lucide="star" class="w-6 h-6 text-amz-star"></i>
            </div>
            <div>
                <h3 class="text-[16px] font-bold text-amz-text mb-1">Your Reviews</h3>
                <p class="text-[13px] text-amz-text-sec">View and manage your product reviews</p>
            </div>
        </a>

        {{-- Notifications Card --}}
        <a href="{{ route('profile.notifications') }}"
           class="bg-white rounded-lg border border-amz-border p-5 flex items-start gap-4 hover:border-amz-orange hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center flex-shrink-0 group-hover:bg-red-100 transition-colors">
                <i data-lucide="bell" class="w-6 h-6 text-amz-deal"></i>
            </div>
            <div>
                <h3 class="text-[16px] font-bold text-amz-text mb-1">Notifications</h3>
                <p class="text-[13px] text-amz-text-sec">Manage your notification preferences</p>
            </div>
        </a>

    </div>

    {{-- ═══════════ TWO-COLUMN LAYOUT ═══════════ --}}
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- ═══ LEFT: PROFILE INFO ═══ --}}
        <div class="flex-1 min-w-0 space-y-5">

            {{-- Personal Information --}}
            <div class="bg-white rounded-lg border border-amz-border overflow-hidden">
                <div class="px-5 py-4 border-b border-amz-border-light flex items-center justify-between">
                    <h2 class="text-[18px] font-bold text-amz-text">Personal Information</h2>
                    <a href="{{ route('profile.edit') }}" class="text-[13px] text-amz-blue hover:text-amz-link-hover hover:underline">Edit</a>
                </div>
                <div class="p-5">

                    {{-- Avatar + Name row --}}
                    <div class="flex items-center gap-5 mb-6 pb-5 border-b border-amz-border-light">
                        <div class="relative">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-20 h-20 rounded-full object-cover border-2 border-amz-border">
                            @else
                                <div class="w-20 h-20 rounded-full bg-amz-orange/20 flex items-center justify-center border-2 border-amz-border">
                                    <span class="text-[28px] font-bold text-amz-orange">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <a href="{{ route('profile.avatar') }}"
                               class="absolute bottom-0 right-0 w-7 h-7 bg-white border border-amz-border rounded-full flex items-center justify-center hover:bg-amz-page shadow-sm transition-colors"
                               title="Change photo">
                                <i data-lucide="camera" class="w-3.5 h-3.5 text-amz-text-sec"></i>
                            </a>
                        </div>
                        <div>
                            <p class="text-[20px] font-bold text-amz-text">{{ auth()->user()->name }}</p>
                            <p class="text-[13px] text-amz-text-sec mt-0.5">Member since {{ auth()->user()->created_at->format('F Y') }}</p>
                            <span class="inline-flex items-center gap-1 mt-1.5 bg-amz-orange/10 text-amz-orange text-[11px] font-bold px-2.5 py-0.5 rounded-full">
                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                Verified Account
                            </span>
                        </div>
                    </div>

                    {{-- Info fields --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div>
                            <p class="text-[12px] font-medium text-amz-text-sec mb-1 uppercase tracking-wide">Full Name</p>
                            <p class="text-[14px] text-amz-text font-medium">{{ auth()->user()->name }}</p>
                        </div>

                        <div>
                            <p class="text-[12px] font-medium text-amz-text-sec mb-1 uppercase tracking-wide">Email Address</p>
                            <p class="text-[14px] text-amz-text font-medium">{{ auth()->user()->email }}</p>
                        </div>

                        <div>
                            <p class="text-[12px] font-medium text-amz-text-sec mb-1 uppercase tracking-wide">Phone Number</p>
                            <p class="text-[14px] text-amz-text font-medium">
                                {{ auth()->user()->phone ?? '—' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[12px] font-medium text-amz-text-sec mb-1 uppercase tracking-wide">Date of Birth</p>
                            <p class="text-[14px] text-amz-text font-medium">
                                {{ auth()->user()->birth_date ? auth()->user()->birth_date->format('M d, Y') : '—' }}
                            </p>
                        </div>

                    </div>

                </div>
            </div>

            {{-- Edit Profile Form --}}
            <div class="bg-white rounded-lg border border-amz-border overflow-hidden">
                <div class="px-5 py-4 border-b border-amz-border-light">
                    <h2 class="text-[18px] font-bold text-amz-text">Edit Profile</h2>
                </div>
                <div class="p-5">

                    @if($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                            <li class="text-[13px] text-red-700 flex items-start gap-2">
                                <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                                {{ $error }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-[13px] font-medium text-amz-text mb-1.5" for="name">Full Name</label>
                                <input type="text" id="name" name="name"
                                       value="{{ old('name', auth()->user()->name) }}"
                                       class="amz-input w-full h-9 px-3 text-[13px] rounded @error('name') border-red-400 @enderror"
                                       required>
                            </div>

                            <div>
                                <label class="block text-[13px] font-medium text-amz-text mb-1.5" for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone"
                                       value="{{ old('phone', auth()->user()->phone) }}"
                                       placeholder="+1 (555) 000-0000"
                                       class="amz-input w-full h-9 px-3 text-[13px] rounded @error('phone') border-red-400 @enderror">
                            </div>

                            <div>
                                <label class="block text-[13px] font-medium text-amz-text mb-1.5" for="email">Email Address</label>
                                <input type="email" id="email" name="email"
                                       value="{{ old('email', auth()->user()->email) }}"
                                       class="amz-input w-full h-9 px-3 text-[13px] rounded @error('email') border-red-400 @enderror"
                                       required>
                            </div>

                            <div>
                                <label class="block text-[13px] font-medium text-amz-text mb-1.5" for="birth_date">Date of Birth</label>
                                <input type="date" id="birth_date" name="birth_date"
                                       value="{{ old('birth_date', auth()->user()->birth_date?->format('Y-m-d')) }}"
                                       class="amz-input w-full h-9 px-3 text-[13px] rounded @error('birth_date') border-red-400 @enderror">
                            </div>

                        </div>

                        <div class="pt-2 flex items-center gap-3">
                            <button type="submit"
                                    class="px-6 py-2 text-[13px] font-bold text-white bg-amz-orange hover:bg-amz-orange-light rounded-full transition-colors">
                                Save Changes
                            </button>
                            <button type="reset"
                                    class="px-6 py-2 text-[13px] font-medium text-amz-text bg-white border border-amz-border hover:bg-amz-page rounded-full transition-colors">
                                Cancel
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="bg-white rounded-lg border border-amz-border overflow-hidden">
                <button onclick="toggleSection('passwordSection', this)"
                        class="w-full px-5 py-4 flex items-center justify-between hover:bg-amz-page/50 transition-colors">
                    <h2 class="text-[18px] font-bold text-amz-text">Change Password</h2>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-amz-text-sec transition-transform" id="passwordChevron"></i>
                </button>
                <div id="passwordSection" class="hidden border-t border-amz-border-light">
                    <div class="p-5">
                        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-[13px] font-medium text-amz-text mb-1.5" for="current_password">Current Password</label>
                                <input type="password" id="current_password" name="current_password"
                                       class="amz-input w-full h-9 px-3 text-[13px] rounded max-w-md @error('current_password') border-red-400 @enderror"
                                       required>
                            </div>

                            <div>
                                <label class="block text-[13px] font-medium text-amz-text mb-1.5" for="password">New Password</label>
                                <input type="password" id="password" name="password"
                                       class="amz-input w-full h-9 px-3 text-[13px] rounded max-w-md @error('password') border-red-400 @enderror"
                                       required>
                            </div>

                            <div>
                                <label class="block text-[13px] font-medium text-amz-text mb-1.5" for="password_confirmation">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="amz-input w-full h-9 px-3 text-[13px] rounded max-w-md"
                                       required>
                            </div>

                            <div>
                                <button type="submit"
                                        class="px-6 py-2 text-[13px] font-bold text-white bg-amz-orange hover:bg-amz-orange-light rounded-full transition-colors">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        {{-- ═══ RIGHT SIDEBAR ═══ --}}
        <div class="lg:w-[320px] flex-shrink-0 space-y-5">

            {{-- Account Summary --}}
            <div class="bg-white rounded-lg border border-amz-border overflow-hidden">
                <div class="px-5 py-4 border-b border-amz-border-light">
                    <h2 class="text-[16px] font-bold text-amz-text">Account Summary</h2>
                </div>
                <div class="p-5 space-y-4">

                    <div class="flex items-center justify-between py-2.5 border-b border-amz-border-light">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="shopping-bag" class="w-4 h-4 text-amz-text-sec"></i>
                            <span class="text-[13px] text-amz-text">Total Orders</span>
                        </div>
                        <span class="text-[15px] font-bold text-amz-text">{{ auth()->user()->orders_count ?? auth()->user()->orders()->count() }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2.5 border-b border-amz-border-light">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="clock" class="w-4 h-4 text-amz-text-sec"></i>
                            <span class="text-[13px] text-amz-text">Pending Orders</span>
                        </div>
                        <span class="text-[15px] font-bold text-amz-orange">{{ auth()->user()->orders()->where('order_status', '!=', 'delivered')->where('order_status', '!=', 'cancelled')->count() }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2.5 border-b border-amz-border-light">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="star" class="w-4 h-4 text-amz-text-sec"></i>
                            <span class="text-[13px] text-amz-text">Reviews Written</span>
                        </div>
                        <span class="text-[15px] font-bold text-amz-text">{{ auth()->user()->reviews_count ?? 0 }}</span>
                    </div>

                    <div class="flex items-center justify-between py-2.5">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-amz-text-sec"></i>
                            <span class="text-[13px] text-amz-text">Saved Addresses</span>
                        </div>
                        <span class="text-[15px] font-bold text-amz-text">{{ auth()->user()->addresses()->count() }}</span>
                    </div>

                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white rounded-lg border border-amz-border overflow-hidden">
                <div class="px-5 py-4 border-b border-amz-border-light flex items-center justify-between">
                    <h2 class="text-[16px] font-bold text-amz-text">Recent Orders</h2>
                    <a href="{{ route('orders.index') }}" class="text-[12px] text-amz-blue hover:text-amz-link-hover hover:underline">View all</a>
                </div>
                <div class="divide-y divide-amz-border-light">
                    @forelse(auth()->user()->orders()->latest()->take(3)->get() as $order)
                    <a href="{{ route('orders.show', $order->id) }}"
                       class="flex items-start gap-3 p-4 hover:bg-amz-page/50 transition-colors">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0
                            @if($order->order_status === 'delivered') bg-amz-green/10 @elseif($order->order_status === 'cancelled') bg-red-50 @else bg-amz-orange/10 @endif">
                            <i data-lucide="{{ $order->order_status === 'delivered' ? 'check-circle' : ($order->order_status === 'cancelled' ? 'x-circle' : 'clock') }}"
                               class="w-4 h-4 @if($order->order_status === 'delivered') text-amz-green @elseif($order->order_status === 'cancelled') text-red-500 @else text-amz-orange @endif"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[13px] font-medium text-amz-text truncate">Order #{{ $order->id }}</p>
                            <p class="text-[12px] text-amz-text-sec">{{ $order->created_at->format('M d, Y') }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[12px] font-bold text-amz-text">${{ number_format($order->total_price, 2) }}</span>
                                <span class="text-[11px] px-1.5 py-0.5 rounded-full font-medium
                                    @if($order->order_status === 'delivered') bg-amz-green/10 text-amz-green
                                    @elseif($order->order_status === 'cancelled') bg-red-50 text-red-600
                                    @elseif($order->order_status === 'processing') bg-blue-50 text-blue-600
                                    @else bg-amz-orange/10 text-amz-orange @endif">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="p-6 text-center">
                        <i data-lucide="package" class="w-10 h-10 text-amz-text-weak mx-auto mb-2"></i>
                        <p class="text-[13px] text-amz-text-sec">No orders yet</p>
                        <a href="{{ route('products.index') }}" class="text-[13px] text-amz-blue hover:underline mt-1 inline-block">Start shopping</a>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Default Address --}}
            <div class="bg-white rounded-lg border border-amz-border overflow-hidden">
                <div class="px-5 py-4 border-b border-amz-border-light flex items-center justify-between">
                    <h2 class="text-[16px] font-bold text-amz-text">Default Address</h2>
                    <a href="{{ route('addresses.index') }}" class="text-[12px] text-amz-blue hover:text-amz-link-hover hover:underline">Manage</a>
                </div>
                <div class="p-5">
                    @php $defaultAddress = auth()->user()->addresses()->where('is_default', true)->first(); @endphp
                    @if($defaultAddress)
                    <address class="not-italic">
                        <p class="text-[14px] font-bold text-amz-text">{{ $defaultAddress->full_name ?? auth()->user()->name }}</p>
                        <p class="text-[13px] text-amz-text-sec mt-1">{{ $defaultAddress->street }}</p>
                        @if($defaultAddress->area)
                        <p class="text-[13px] text-amz-text-sec">{{ $defaultAddress->area }}</p>
                        @endif
                        <p class="text-[13px] text-amz-text-sec">{{ $defaultAddress->city }}</p>
                        @if($defaultAddress->details)
                        <p class="text-[13px] text-amz-text-sec">{{ $defaultAddress->details }}</p>
                        @endif
                        @if($defaultAddress->phone)
                        <p class="text-[13px] text-amz-text-sec mt-1">{{ $defaultAddress->phone }}</p>
                        @endif
                    </address>
                    @else
                    <div class="text-center py-2">
                        <i data-lucide="map-pin" class="w-8 h-8 text-amz-text-weak mx-auto mb-2"></i>
                        <p class="text-[13px] text-amz-text-sec mb-2">No default address set</p>
                        <a href="{{ route('addresses.create') }}"
                           class="inline-flex items-center gap-1.5 text-[13px] font-medium text-white bg-amz-orange hover:bg-amz-orange-light px-4 py-1.5 rounded-full transition-colors">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            Add Address
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="bg-white rounded-lg border border-red-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-red-100">
                    <h2 class="text-[16px] font-bold text-red-700">Danger Zone</h2>
                </div>
                <div class="p-5 space-y-3">
                    <p class="text-[13px] text-amz-text-sec">Once you delete your account, there is no going back. All your data will be permanently removed.</p>
                    <button onclick="document.getElementById('deleteModal').classList.remove('hidden')"
                            class="w-full py-2 text-[13px] font-medium text-red-600 border border-red-300 hover:bg-red-50 rounded-full transition-colors">
                        Delete Account
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ═══════════ DELETE ACCOUNT MODAL ═══════════ --}}
<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" onclick="document.getElementById('deleteModal').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-xl shadow-2xl max-w-md w-full p-6 z-10">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
            </div>
            <h3 class="text-[18px] font-bold text-amz-text">Delete Account?</h3>
        </div>
        <p class="text-[14px] text-amz-text-sec mb-5">
            This action cannot be undone. All your orders, addresses, and personal data will be permanently deleted.
        </p>
        <div class="mb-4">
            <label class="block text-[13px] font-medium text-amz-text mb-1.5">Type your password to confirm</label>
            <input type="password" id="deleteConfirmPassword"
                   class="amz-input w-full h-9 px-3 text-[13px] rounded"
                   placeholder="Enter your password">
        </div>
        <div class="flex gap-3">
            <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                    class="flex-1 py-2.5 text-[13px] font-medium border border-amz-border rounded-full hover:bg-amz-page transition-colors">
                Cancel
            </button>
            <form action="{{ route('profile.destroy') }}" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <input type="hidden" name="password" id="deletePasswordField">
                <button type="submit"
                        onclick="document.getElementById('deletePasswordField').value = document.getElementById('deleteConfirmPassword').value"
                        class="w-full py-2.5 text-[13px] font-bold text-white bg-red-600 hover:bg-red-700 rounded-full transition-colors">
                    Delete My Account
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleSection(sectionId, btn) {
    const section = document.getElementById(sectionId);
    const chevron = document.getElementById(sectionId.replace('Section', 'Chevron'));
    section.classList.toggle('hidden');
    if (chevron) chevron.classList.toggle('rotate-180');
}
lucide.createIcons();
</script>

@endsection
