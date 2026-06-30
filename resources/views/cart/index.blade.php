@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">

    <!-- Header -->
    <div class="bg-gray-800 text-white py-4">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-2xl font-bold">🛒 Shopping Cart</h1>
            <p class="text-sm text-gray-300 mt-1">
                @if($cart && $cart->items->count())
                    {{ $cart->items->count() }} item(s) in your cart
                @else
                    Your cart is empty
                @endif
            </p>
        </div>
    </div>

    @if($cart && $cart->items->count())
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- Left Side: Cart Items (2/3) -->
            <div class="flex-1 lg:w-2/3">
                <div class="bg-white rounded-lg shadow border border-gray-200">

                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="font-bold text-gray-800">Shopping Cart Items</h3>
                    </div>

                    <!-- Items List -->
                    @foreach($cart->items as $item)
                    <div class="p-6 border-b border-gray-200 hover:bg-orange-50 transition-colors">
                        <div class="flex gap-4">

                            <!-- ✅ Product Image - المصحح هنا! -->
                            <img src="{{ $item->product->image_url }}"
                            alt="{{ $item->product->name }}"
                            class="w-24 h-24 object-cover rounded-lg border shadow-sm"
                            onerror="this.src='{{ asset('images/default.png') }}'">

                            <!-- Product Info -->
                            <div class="flex-1">
                                <a href="{{ route('products.show', $item->product->id) }}"
                                   class="text-blue-600 hover:text-orange-600 font-semibold hover:underline">
                                    {{ $item->product->name }}
                                </a>

                                <div class="mt-2 text-sm space-y-1">
                                    <p class="text-green-600 font-medium">✓ In Stock</p>
                                    <p class="text-gray-500">Eligible for FREE Shipping</p>
                                    <div class="flex items-center gap-1 text-yellow-500">
                                        ★★★★★ <span class="text-xs text-gray-500">(128)</span>
                                    </div>
                                </div>

                                <!-- Price Display -->
                                @if($item->product->discount_price && $item->product->discount_price < $item->price)
                                <div class="mt-2 flex items-center gap-2">
                                    <span class="text-lg font-bold text-gray-900">
                                        ${{ number_format($item->product->discount_price, 2) }}
                                    </span>
                                    <span class="text-sm text-red-500 line-through">
                                        ${{ number_format($item->product->price, 2) }}
                                    </span>
                                    <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full font-semibold">
                                        {{ round((1 - ($item->product->discount_price / $item->product->price)) * 100) }}% OFF
                                    </span>
                                </div>
                                @else
                                <p class="mt-2 text-lg font-bold text-gray-900">
                                    ${{ number_format($item->product->price, 2) }}
                                </p>
                                @endif

                                <label class="flex items-center gap-2 mt-3 text-sm text-gray-600 cursor-pointer">
                                    <input type="checkbox" class="rounded border-gray-300">
                                    This is a gift 🎁
                                </label>
                            </div>

                            <!-- Quantity & Actions -->
                            <div class="flex flex-col items-end gap-3">
                                <!-- Quantity Form -->
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')

                                    <select name="quantity" onchange="this.form.submit()"
                                            class="border border-gray-300 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white">
                                        <?php for($i = 1; $i <= min(10, $item->product->quantity); $i++): ?>
                                            <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        <?php endfor; ?>
                                        @if($item->product->quantity > 10)
                                        <option value="{{ $item->product->quantity }}">{{ $item->product->quantity }} (Max)</option>
                                        @endif
                                    </select>
                                </form>

                                <!-- Subtotal Price -->
                                <p class="text-lg font-bold text-gray-900">
                                    ${{ number_format($item->price * $item->quantity, 2) }}
                                </p>

                                <!-- Delete Button -->
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-700 hover:bg-red-50 px-3 py-1 rounded text-sm font-medium transition-colors"
                                            onclick="return confirm('Are you sure you want to remove this item?')">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Footer Actions -->
                    <div class="px-6 py-4 bg-gray-50 flex justify-between items-center rounded-b-lg">
                        <a href="{{ route('products.index') }}"
                           class="text-blue-600 hover:text-orange-600 font-medium hover:underline inline-flex items-center gap-1">
                            ← Continue Shopping
                        </a>

                        <div class="flex gap-3">
                            <button onclick="location.reload()"
                                    class="px-4 py-2 border border-gray-300 rounded text-sm hover:bg-gray-100 transition-colors">
                                🔄 Update Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Summary (1/3) -->
            <div class="lg:w-1/3">
                <div class="sticky top-4 bg-white rounded-lg shadow border border-gray-200 p-6">

                    <h2 class="text-xl font-bold mb-4 pb-3 border-b border-gray-200">Order Summary</h2>

                    <!-- Free Shipping Banner -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">🚚</span>
                            <div>
                                <p class="text-green-800 font-bold text-sm">FREE delivery</p>
                                <p class="text-xs text-green-700 mt-1">Order within 2 hrs 30 mins</p>
                                <a href="#" class="text-xs text-blue-600 hover:underline mt-1 block">Details</a>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Breakdown -->
                    <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">
                                Subtotal ({{ $cart->items->count() }} item{{ $cart->items->count() > 1 ? 's' : '' }}):
                            </span>
                            <span class="font-bold text-gray-900">${{ number_format($total, 2) }}</span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping:</span>
                            <span class="font-bold text-green-600">FREE</span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Estimated Tax:</span>
                            <span class="font-bold text-gray-900">${{ number_format($total * 0.15, 2) }}</span>
                        </div>

                        {{-- Promo Code Section --}}
                        <div class="pt-3">
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">
                                Apply Promo Code
                            </label>
                            <div class="flex gap-2">
                                <input type="text"
                                       placeholder="Enter code"
                                       class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                <button class="px-4 py-2 bg-gray-800 text-white text-sm font-bold rounded-md hover:bg-gray-700 transition-colors">
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="mb-5 pt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-bold text-gray-900">Estimated Total:</span>
                            <span class="text-2xl font-bold text-gray-900">
                                ${{ number_format($total * 1.15, 2) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 text-right mt-1">(Including estimated tax)</p>
                    </div>

                    <!-- Checkout Button -->
                    <a href="{{ route('checkout.index') }}"
                       class="block w-full bg-gradient-to-r from-yellow-400 via-orange-400 to-orange-500 text-center text-gray-900 font-bold py-4 rounded-full hover:from-yellow-500 hover:via-orange-500 hover:to-orange-600 transition-all duration-200 transform hover:scale-[1.02] shadow-lg hover:shadow-xl text-lg mb-4">
                        Proceed to Checkout →
                    </a>

                    <!-- Payment Options -->
                    <div class="text-center mb-4">
                        <p class="text-xs text-gray-500 mb-2">Or checkout with</p>
                        <div class="flex justify-center gap-2">
                            <button class="flex-1 py-2 border border-gray-300 rounded-md text-xs font-semibold hover:bg-gray-50 transition-colors">
                                💳 Visa
                            </button>
                            <button class="flex-1 py-2 border border-gray-300 rounded-md text-xs font-semibold hover:bg-gray-50 transition-colors">
                                💳 Mastercard
                            </button>
                            <button class="flex-1 py-2 border border-gray-300 rounded-md text-xs font-semibold hover:bg-gray-50 transition-colors">
                                🅿️ PayPal
                            </button>
                        </div>
                    </div>

                    <!-- Security Badge -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <div class="inline-flex items-center justify-center gap-2 text-xs text-gray-500 bg-gray-50 px-4 py-2 rounded-full">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Secure 256-bit SSL Encryption</span>
                        </div>
                    </div>
                </div>

                <!-- Additional Info Cards -->
                <div class="mt-4 space-y-3">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-3">
                            <span class="text-xl">🔄</span>
                            <div>
                                <p class="font-bold text-blue-900 text-sm">Easy Returns</p>
                                <p class="text-xs text-blue-700 mt-1">30-day return policy. No questions asked.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-3">
                            <span class="text-xl">🎁</span>
                            <div>
                                <p class="font-bold text-purple-900 text-sm">Gift Options</p>
                                <p class="text-xs text-purple-700 mt-1">Add gift wrapping & personalized note</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-3">
                            <span class="text-xl">⚡</span>
                            <div>
                                <p class="font-bold text-yellow-900 text-sm">Fast Delivery</p>
                                <p class="text-xs text-yellow-700 mt-1">Express shipping available at checkout</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Empty Cart State -->
    <div class="max-w-4xl mx-auto px-4 py-20">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-12 text-center">
            <div class="mb-8">
                <span class="text-8xl">🛒</span>
            </div>

            <h2 class="text-3xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
            <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
                Looks like you haven't added anything to your cart yet. Explore our products and find something you love!
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-full hover:from-yellow-500 hover:to-orange-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <span class="mr-2">🛍️</span>
                    Continue Shopping
                </a>

                <a href="{{ route('home') }}"
                   class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-300 text-gray-700 font-bold rounded-full hover:border-gray-400 hover:bg-gray-50 transition-all duration-200">
                    ← Back to Home
                </a>
            </div>

            <!-- Featured Categories -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <p class="text-sm font-bold text-gray-700 mb-4">Popular Categories:</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="#" class="px-4 py-2 bg-gray-100 rounded-full text-sm text-gray-700 hover:bg-gray-200 transition-colors">
                        Electronics
                    </a>
                    <a href="#" class="px-4 py-2 bg-gray-100 rounded-full text-sm text-gray-700 hover:bg-gray-200 transition-colors">
                        Fashion
                    </a>
                    <a href="#" class="px-4 py-2 bg-gray-100 rounded-full text-sm text-gray-700 hover:bg-gray-200 transition-colors">
                        Home & Garden
                    </a>
                    <a href="#" class="px-4 py-2 bg-gray-100 rounded-full text-sm text-gray-700 hover:bg-gray-200 transition-colors">
                        Sports
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- Custom Styles --}}





@push('styles')

<style>
/* Smooth transitions */
* {
    transition-property: background-color, border-color, color, box-shadow, transform;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Custom Select Dropdown */
select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.25rem center;
    background-repeat: no-repeat;
    background-size: 1em;
    padding-right: 1.5rem;
}
</style>
@endpush

@endsection
