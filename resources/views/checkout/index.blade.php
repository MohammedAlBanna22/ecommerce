@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">

    <!-- Header -->
    <div class="bg-gray-800 text-white py-4">
        <div class="max-w-6xl mx-auto px-4">
            <h1 class="text-2xl font-bold">Checkout</h1>
            <p class="text-sm text-gray-300 mt-1">Complete your order</p>
        </div>
    </div>

    @if(session('success'))
    <div class="max-w-6xl mx-auto px-4 mt-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="max-w-6xl mx-auto px-4 mt-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <strong>Please fix errors:</strong><br>
            <ul class="list-disc ml-5 mt-1 text-sm">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @if(!$cart || $cart->items->count() == 0)

    <!-- Empty Cart -->
    <div class="max-w-4xl mx-auto px-4 py-20 text-center bg-white mt-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-2">Your cart is empty</h2>
        <a href="{{ route('products.index') }}" class="text-blue-600 underline">Continue Shopping</a>
    </div>

    @else

    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT SIDE (2/3) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- ========== SECTION 1: SHIPPING ADDRESS ========== -->
                <div class="bg-white rounded-lg shadow border p-5">
                    <h2 class="text-xl font-bold mb-4 pb-3 border-b flex items-center gap-2">
                        <span class="w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                        Shipping Address
                    </h2>

                    {{-- SAVED ADDRESSES --}}
                    @if($addresses && $addresses->count() > 0)

                    <div class="mb-5">
                        <p class="font-semibold mb-3 text-gray-700">Select a saved address:</p>

                        <div class="space-y-3" id="savedAddressesList">
                            @foreach($addresses as $addr)
                            <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ old('address_id') == $addr->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                <input type="radio" name="address_id" value="{{ $addr->id }}"
                                       {{ old('address_id') == $addr->id ? 'checked' : '' }}
                                       class="mt-1 w-5 h-5 text-blue-600"
                                       onclick="hideNewAddrForm()">

                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <strong>{{ $addr->full_name }}</strong>
                                        @if($addr->is_default)
                                        <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full font-semibold">Default</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ $addr->phone }}</p>
                                    <p class="text-sm text-gray-600">{{ $addr->street }}, {{ $addr->area }}</p>
                                    <p class="text-sm text-gray-600">{{ $addr->city }}</p>
                                    @if($addr->details)
                                    <p class="text-xs text-gray-400 italic mt-1">{{ $addr->details }}</p>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>

                        <!-- ADD NEW BUTTON -->
                        <button type="button"
                                onclick="showNewAddrForm()"
                                class="w-full mt-4 py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 font-semibold hover:border-blue-400 hover:text-blue-600 hover:bg-blue-50 transition-all"
                                id="addNewBtn">
                            + Add a new address
                        </button>
                    </div>

                    @else

                    <p class="text-gray-600 mb-4 text-sm">No saved addresses. Please enter your address below:</p>

                    @endif

                    {{-- ✅ NEW ADDRESS CONTAINER (DIV فقط - بدون فورم داخلي!) --}}
                    <div id="newAddrForm" class="<?php echo ($addresses && $addresses->count() > 0) ? 'hidden' : ''; ?> mt-5 pt-5 border-t-2 border-dashed">

                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-lg text-gray-800">New Address</h3>

                            @if($addresses && $addresses->count() > 0)
                            <button type="button" onclick="hideNewAddrForm()" class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
                            @endif
                        </div>

                        {{-- الحقول المباشرة --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Full Name -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name *</label>
                                <input type="text" id="addr_full_name" required
                                       value="{{ old('full_name', auth()->user()->name ?? '') }}"
                                       placeholder="John Doe"
                                       class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Phone -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Phone *</label>
                                <input type="tel" id="addr_phone" required
                                       value="{{ old('phone') }}"
                                       placeholder="+962 7X XXX XXXX"
                                       class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- City -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">City *</label>
                                <input type="text" id="addr_city" required
                                       value="{{ old('city') }}"
                                       placeholder="Amman"
                                       class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Area -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Area *</label>
                                <input type="text" id="addr_area" required
                                       value="{{ old('area') }}"
                                       placeholder="Abdoun"
                                       class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Street -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Street Address *</label>
                                <textarea id="addr_street" required rows="2"
                                          placeholder="123 Main St, Bldg 5, Apt 10"
                                          class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ old('street') }}</textarea>
                            </div>

                            <!-- Details -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Details (Optional)</label>
                                <textarea id="addr_details" rows="2"
                                          placeholder="Near pharmacy, ring twice..."
                                          class="w-full px-4 py-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ old('details') }}</textarea>
                            </div>

                            <!-- Default Checkbox -->
                            <div class="md:col-span-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" id="addr_is_default" value="1"
                                           {{ (!$addresses || $addresses->count() == 0) ? 'checked' : '' }}
                                           class="w-5 h-5 text-blue-600 rounded">
                                    <span class="font-medium text-gray-700">Save as default address</span>
                                </label>
                            </div>
                        </div>

                        <!-- SAVE BUTTON - type="button" وليس submit! -->
                        <div class="mt-5 pt-4 border-t">
                            <button type="button"
                                    onclick="submitAddressForm()"
                                    class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-md text-base">
                                💾 Save This Address
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ========== SECTION 2: PAYMENT METHOD ========== -->
                <div class="bg-white rounded-lg shadow border p-5">
                    <h2 class="text-xl font-bold mb-4 pb-3 border-b flex items-center gap-2">
                        <span class="w-7 h-7 bg-purple-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</span>
                        Payment Method
                    </h2>

                    <div class="space-y-3">
                        <label class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer hover:bg-purple-50 has-[:checked]:border-purple-500 has-[:checked]:bg-purple-50">
                            <input type="radio" name="payment_method" value="credit_card" checked class="w-5 h-5 text-purple-600">
                            <span class="font-semibold">Credit / Debit Card</span>
                            <span class="ml-auto text-sm text-gray-500">Visa, Mastercard</span>
                        </label>

                        <label class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer hover:bg-purple-50 has-[:checked]:border-purple-500 has-[:checked]:bg-purple-50">
                            <input type="radio" name="payment_method" value="paypal" class="w-5 h-5 text-purple-600">
                            <span class="font-semibold">PayPal</span>
                            <span class="ml-auto text-sm text-gray-500">Fast & secure</span>
                        </label>

                        <label class="flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer hover:bg-purple-50 has-[:checked]:border-purple-500 has-[:checked]:bg-purple-50">
                            <input type="radio" name="payment_method" value="cod" class="w-5 h-5 text-purple-600">
                            <span class="font-semibold">Cash on Delivery</span>
                            <span class="ml-auto text-sm text-gray-500">Pay when you receive</span>
                        </label>
                    </div>
                </div>

                <!-- ========== SECTION 3: ORDER ITEMS ========== -->
                <div class="bg-white rounded-lg shadow border p-5">
                    <h2 class="text-xl font-bold mb-4 pb-3 border-b flex items-center gap-2">
                        <span class="w-7 h-7 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</span>
                        Order Items ({{ $cart->items->count() }})
                    </h2>

                    <div class="divide-y">
                        @foreach($cart->items as $item)
                        <div class="py-4 flex gap-4">
                            <img src="{{ $item->product->image_url ?? asset('images/default.png') }}"
                                 alt="{{ $item->product->name }}"
                                 class="w-20 h-20 object-cover rounded border"
                                 onerror="this.src='{{ asset('images/default.png') }}'">

                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    Qty: <strong>{{ $item->quantity }}</strong> x
                                    <strong>${{ number_format($item->price, 2) }}</strong>
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="font-bold text-lg">${{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- RIGHT SIDEBAR (1/3) -->
            <div class="lg:col-span-1">
                <div class="sticky top-4 space-y-4">

                    <!-- ✅ فورم Checkout الرئيسي هنا فقط -->
                    <form method="POST" action="{{ route('checkout.store') }}" id="checkoutForm">
                        @csrf

                        <!-- ORDER SUMMARY CARD -->
                        <div class="bg-white rounded-lg shadow border overflow-hidden">
                            <div class="bg-orange-500 text-white px-5 py-3 font-bold">
                                Order Summary
                            </div>

                            <div class="p-5 space-y-3">
                                <?php
                                $subtotal = $cart->items->sum(function($i) { return $i->price * $i->quantity; });
                                $shipping = $subtotal >= 30 ? 0 : 4.99;
                                $tax = $subtotal * 0.16;
                                ?>

                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <strong>${{ number_format($subtotal, 2) }}</strong>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping:</span>
                                    <strong class="{{ $shipping == 0 ? 'text-green-600' : '' }}">
                                        {{ $shipping == 0 ? 'FREE' : '$'.number_format($shipping, 2) }}
                                    </strong>
                                </div>

                                @if($shipping > 0)
                                <div class="bg-blue-50 text-blue-700 text-xs p-2 rounded text-center">
                                    Add ${{ number_format(30 - $subtotal, 2) }} more for FREE shipping!
                                </div>
                                @endif

                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax (16%):</span>
                                    <strong>${{ number_format($tax, 2) }}</strong>
                                </div>

                                <hr class="my-3">

                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">Total:</span>
                                    <span class="text-2xl font-black text-gray-900">
                                        ${{ number_format($subtotal + $shipping + $tax, 2) }}
                                    </span>
                                </div>

                                <!-- Coupon Input -->
                                <div class="pt-3 border-t">
                                    <div class="flex gap-2">
                                        <input type="text" name="coupon_code" placeholder="Coupon code"
                                               value="{{ old('coupon_code') }}"
                                               class="flex-1 px-3 py-2 border rounded text-sm">
                                        <button type="button" class="px-4 py-2 bg-gray-800 text-white text-sm rounded font-semibold hover:bg-gray-700">
                                            Apply
                                        </button>
                                    </div>
                                </div>

                                <!-- PLACE ORDER BUTTON -->
                                <button type="submit"
                                        onclick="return validateOrder(event)"
                                        class="w-full bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-black py-4 rounded-lg hover:from-yellow-500 hover:to-orange-600 transition-all shadow-lg text-lg mt-4">
                                    Place Your Order
                                </button>

                                <p class="text-xs text-center text-gray-500 mt-3">
                                    Secure SSL Encryption - Safe Checkout
                                </p>
                            </div>
                        </div>
                    </form>

                    <!-- Trust Badges -->
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-white rounded-lg p-3 text-center border shadow-sm">
                            <div class="text-2xl">Secure</div>
                            <p class="text-xs font-semibold mt-1">Payment</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 text-center border shadow-sm">
                            <div class="text-2xl">Returns</div>
                            <p class="text-xs font-semibold mt-1">Easy</p>
                        </div>
                        <div class="bg-white rounded-lg p-3 text-center border shadow-sm">
                            <div class="text-2xl">Gift</div>
                            <p class="text-xs font-semibold mt-1">Wrap</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    @endif

</div>

{{-- ✅✅✅ JavaScript - ينشئ فورم ديناميكي وي отправله ✅✅✅ --}}
<script>
// Show New Address Form
function showNewAddrForm() {
    var form = document.getElementById('newAddrForm');
    var btn = document.getElementById('addNewBtn');

    if (form) form.classList.remove('hidden');
    if (btn) btn.style.display = 'none';

    // Uncheck any selected address radio
    var radios = document.querySelectorAll('input[name="address_id"]');
    radios.forEach(function(r) { r.checked = false; });

    if (form) {
        setTimeout(function() {
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    }
}

// Hide New Address Form
function hideNewAddrForm() {
    var form = document.getElementById('newAddrForm');
    var btn = document.getElementById('addNewBtn');

    if (form) form.classList.add('hidden');
    if (btn) btn.style.display = '';
}

// ✅✅✅ الدالة السحرية - تنشئ فورم وترسله! ✅✅✅
function submitAddressForm() {
    // جمع البيانات من الحقول
    var fullName = document.getElementById('addr_full_name').value.trim();
    var phone = document.getElementById('addr_phone').value.trim();
    var city = document.getElementById('addr_city').value.trim();
    var area = document.getElementById('addr_area').value.trim();
    var street = document.getElementById('addr_street').value.trim();
    var details = document.getElementById('addr_details').value.trim();
    var isDefault = document.getElementById('addr_is_default').checked ? '1' : '0';

    // التحقق من الحقول المطلوبة
    if (!fullName) { alert('Please enter Full Name'); document.getElementById('addr_full_name').focus(); return; }
    if (!phone) { alert('Please enter Phone Number'); document.getElementById('addr_phone').focus(); return; }
    if (!city) { alert('Please enter City'); document.getElementById('addr_city').focus(); return; }
    if (!area) { alert('Please enter Area'); document.getElementById('addr_area').focus(); return; }
    if (!street) { alert('please enter Street Address'); document.getElementById('addr_street').focus(); return; }

    // ✅ إنشاء فورم ديناميكي (خارج أي فورم آخر!)
    var tempForm = document.createElement('form');
    tempForm.method = 'POST';
    tempForm.action = '{{ route("addresses.store") }}';

    // إضافة CSRF Token
    var csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    tempForm.appendChild(csrfInput);

    // إضافة from_checkout
    var fromCheckout = document.createElement('input');
    fromCheckout.type = 'hidden';
    fromCheckout.name = 'from_checkout';
    fromCheckout.value = '1';
    tempForm.appendChild(fromCheckout);

    // إضافة الحقول
    var fields = [
        { name: 'full_name', value: fullName },
        { name: 'phone', value: phone },
        { name: 'city', value: city },
        { name: 'area', value: area },
        { name: 'street', value: street },
        { name: 'details', value: details },
        { name: 'is_default', value: isDefault }
    ];

    fields.forEach(function(field) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = field.name;
        input.value = field.value;
        tempForm.appendChild(input);
    });

    // إضافة الفورم للـ Page وإرساله
    document.body.appendChild(tempForm);
    tempForm.submit();
}

// Validate Order
function validateOrder(e) {
    var formVisible = document.getElementById('newAddrForm');

    // إذا فورم العنوان الجديد مفتوح
    if (formVisible && !formVisible.classList.contains('hidden')) {
        e.preventDefault();
        alert('Please save the new address first, or select a saved address above.');
        return false;
    }

    // تحقق من address_id
    var selected = document.querySelector('input[name="address_id"]:checked');
    if (!selected) {
        e.preventDefault();
        alert('Please select a shipping address.');
        return false;
    }

    // تحقق من payment_method
    var paymentMethod = document.querySelector('input[name="payment_method"]:checked');
    if (!paymentMethod) {
        e.preventDefault();
        alert('Please select a payment method.');
        return false;
    }

    // ✅ أضف address_id للفورم
    var addrInput = document.getElementById('checkoutForm').querySelector('input[name="address_id"]');
    if (!addrInput) {
        addrInput = document.createElement('input');
        addrInput.type = 'hidden';
        addrInput.name = 'address_id';
        document.getElementById('checkoutForm').appendChild(addrInput);
    }
    addrInput.value = selected.value;

    // ✅ أضف payment_method للفورم
    var payInput = document.getElementById('checkoutForm').querySelector('input[name="payment_method"]');
    if (!payInput) {
        payInput = document.createElement('input');
        payInput.type = 'hidden';
        payInput.name = 'payment_method';
        document.getElementById('checkoutForm').appendChild(payInput);
    }
    payInput.value = paymentMethod.value;

    if (!confirm('Place this order?')) {
        e.preventDefault();
        return false;
    }

    return true;
}
</script>

@endsection
