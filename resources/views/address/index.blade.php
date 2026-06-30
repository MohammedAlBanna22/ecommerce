
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Your Addresses - {{ config('app.name', 'MyShop') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'Arial', 'sans-serif'] },
                    colors: {
                        amz: {
                            dark: '#131921', nav: '#232F3E', navHover: '#37475A',
                            orange: '#FF9900', 'orange-btn': '#FFD814', 'orange-border': '#F3A847',
                            blue: '#007185', green: '#067D62', 'green-light': '#D4F3EA',
                            page: '#EAEDED', card: '#FFFFFF', border: '#DDD',
                            text: '#0F1111', 'text-sec': '#565959', 'text-weak': '#979797',
                            deal: '#CC0C39', 'deal-bg': '#FDEEE8',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { -webkit-font-smoothing: antialiased; }

        .amz-input {
            border: 1px solid #DDD;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 13px;
            width: 100%;
            transition: border-color 100ms, box-shadow 100ms;
            font-family: 'Inter', sans-serif;
            color: #0F1111;
        }
        .amz-input:focus { border-color: #E77600; box-shadow: 0 0 0 3px rgba(228,166,54,0.3); outline: none; }
        .amz-input::placeholder { color: #979797; }

        .amz-label { font-size: 13px; font-weight: 600; color: #0F1111; margin-bottom: 5px; display: block; }
        .amz-label span { color: #CC0C39; margin-left: 2px; }

        .amz-btn-primary {
            background: linear-gradient(to bottom, #FFE696, #FFD814);
            border: 1px solid #F3A847;
            border-radius: 8px;
            color: #0F1111;
            font-weight: 700;
            font-size: 13px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background 100ms;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .amz-btn-primary:hover { background: linear-gradient(to bottom, #FFD814, #F7CA00); }

        .amz-btn-ghost {
            border: 1px solid #DDD;
            border-radius: 6px;
            color: #0F1111;
            font-size: 13px;
            font-weight: 500;
            padding: 7px 14px;
            cursor: pointer;
            background: white;
            transition: border-color 100ms, background 100ms;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .amz-btn-ghost:hover { border-color: #888; background: #F7F7F7; }

        .amz-btn-danger {
            border: 1px solid #CC0C39;
            border-radius: 6px;
            color: #CC0C39;
            font-size: 13px;
            font-weight: 500;
            padding: 7px 14px;
            cursor: pointer;
            background: white;
            transition: background 100ms;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .amz-btn-danger:hover { background: #FDEEE8; }

        .address-card {
            border: 1px solid #DDD;
            border-radius: 8px;
            background: white;
            transition: box-shadow 200ms, border-color 200ms;
            position: relative;
        }
        .address-card:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .address-card.is-default { border-color: #FF9900; border-width: 2px; }

        .add-card {
            border: 2px dashed #DDD;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: border-color 200ms, background 200ms;
            min-height: 180px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .add-card:hover { border-color: #FF9900; background: #FFFBF2; }

        /* Modal */
        #addressModal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        #addressModal.open { display: flex; }

        .modal-box {
            background: white;
            border-radius: 8px;
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 8px 40px rgba(0,0,0,0.25);
        }
        .modal-header {
            padding: 16px 20px;
            border-bottom: 1px solid #DDD;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            background: white;
            z-index: 1;
        }
        .modal-body { padding: 20px; }
        .modal-footer {
            padding: 14px 20px;
            border-top: 1px solid #DDD;
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            position: sticky;
            bottom: 0;
            background: white;
        }

        .amz-checkbox {
            width: 15px; height: 15px;
            border: 1px solid #888;
            border-radius: 3px;
            accent-color: #FF9900;
            cursor: pointer;
        }

        .nav-link { transition: all 100ms; border: 1px solid transparent; border-radius: 3px; }
        .nav-link:hover { border-color: white; }

        .field-group { margin-bottom: 14px; }

        /* toast */
        #toast {
            position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
            background: #232F3E; color: white; font-size: 13px; font-weight: 500;
            padding: 10px 20px; border-radius: 6px; z-index: 999;
            opacity: 0; transition: opacity 200ms; pointer-events: none;
        }
        #toast.show { opacity: 1; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f0f0f0; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
    </style>
</head>
@extends('layouts.app')

@section('content')

<!-- ═══ BREADCRUMB ═══ -->
<div class="bg-white border-b border-amz-border">
    <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-2">
        <nav class="flex items-center gap-1 text-[12px]">
            <a href="{{ route('home') }}" class="text-amz-blue hover:underline">{{ config('app.name', 'MyShop') }}</a>
            <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
            <a href="#" class="text-amz-blue hover:underline">Account</a>
            <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
            <span class="text-amz-text font-medium">Your Addresses</span>
        </nav>
    </div>
</div>

<!-- ═══ MAIN ═══ -->
<div class="max-w-[900px] mx-auto px-3 sm:px-4 py-6">

    <!-- Page Header -->
    <div class="bg-white rounded-lg border border-amz-border px-4 py-4 mb-6">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-[26px] font-bold text-amz-text mb-0.5">Your Addresses</h1>
                <p class="text-[13px] text-amz-text-sec">{{ $addresses->count() }} saved address{{ $addresses->count() !== 1 ? 'es' : '' }}</p>
            </div>
            <button onclick="openAddModal()" class="amz-btn-primary">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add New Address
            </button>
        </div>
    </div>

    <!-- Addresses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="addressesGrid">

        @foreach($addresses as $address)
        <div class="address-card {{ $address->is_default ? 'is-default' : '' }} p-4" id="address-card-{{ $address->id }}">

            @if($address->is_default)
            <div class="absolute -top-3 left-4">
                <span class="inline-flex items-center gap-1 bg-amz-orange text-white text-[11px] font-bold px-2.5 py-1 rounded-full">
                    <i data-lucide="star" class="w-3 h-3"></i>
                    Default
                </span>
            </div>
            @endif

            <!-- Address Body -->
            <div class="pt-2">
                <p class="text-[14px] font-bold text-amz-text mb-2">{{ $address->full_name }}</p>
                <div class="text-[13px] text-amz-text-sec space-y-0.5">
                    <p>{{ $address->street }}</p>
                    <p>{{ $address->city }}{{ $address->state ? ', ' . $address->state : '' }}{{ $address->postal_code ? ' ' . $address->postal_code : '' }}</p>

                    <p class="text-amz-text font-medium pt-1 flex items-center gap-1">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-amz-text-weak"></i>
                        {{ $address->phone }}
                    </p>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-amz-border mt-4 pt-3 flex items-center justify-between gap-2 flex-wrap">
                <div class="flex items-center gap-2">
                    <button onclick='openEditModal(@json($address))' class="amz-btn-ghost py-1.5 px-3 text-[12px]">
                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit
                    </button>
                    <button onclick="deleteAddress({{ $address->id }})" class="amz-btn-danger py-1.5 px-3 text-[12px]">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Remove
                    </button>
                </div>
                @if(!$address->is_default)
                <form action="{{ route('addresses.set-default', $address) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-[12px] text-amz-blue hover:underline font-medium flex items-center gap-1">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                        Set as Default
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach

        <!-- Add New Card -->
        <div onclick="openAddModal()" class="add-card p-4">
            <div class="w-12 h-12 bg-amz-page rounded-full flex items-center justify-center border border-amz-border">
                <i data-lucide="plus" class="w-6 h-6 text-amz-text-weak"></i>
            </div>
            <p class="text-[13px] font-semibold text-amz-text-sec">Add a new address</p>
        </div>

    </div>

    @if($addresses->isEmpty())
    <div class="bg-white rounded-lg border border-amz-border p-10 text-center mt-2">
        <div class="w-20 h-20 bg-amz-page rounded-full flex items-center justify-center mx-auto mb-4 border border-amz-border">
            <i data-lucide="map-pin-off" class="w-9 h-9 text-amz-text-weak"></i>
        </div>
        <h2 class="text-[17px] font-bold text-amz-text mb-1">No saved addresses</h2>
        <p class="text-[13px] text-amz-text-sec mb-5">Add an address to speed up checkout.</p>
        <button onclick="openAddModal()" class="amz-btn-primary mx-auto">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Address
        </button>
    </div>
    @endif

</div>

<!-- ═══ FOOTER ═══ -->
<footer class="mt-10">
    <a href="#" class="block bg-amz-navHover text-white text-center py-3 text-[13px] hover:bg-amz-nav transition-colors">Back to top</a>
    <div class="bg-amz-nav text-white">
        <div class="max-w-[1200px] mx-auto px-6 py-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-[15px] font-bold mb-3">Get to Know Us</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline">About {{ config('app.name','MyShop') }}</a></li>
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline">Careers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[15px] font-bold mb-3">Let Us Help You</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline">Your Account</a></li>
                        <li><a href="{{ route('orders.index') }}" class="text-[13px] text-gray-300 hover:underline">Your Orders</a></li>
                        <li><a href="{{ route('addresses.index') }}" class="text-[13px] text-gray-300 hover:underline">Your Addresses</a></li>
                        <li><a href="#" class="text-[13px] text-gray-300 hover:underline">Help</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-amz-dark text-center py-4 border-t border-white/10">
        <a href="{{ route('home') }}" class="flex items-center justify-center gap-1.5 mb-2">
            <i data-lucide="shopping-bag" class="w-5 h-5 text-amz-orange"></i>
            <span class="text-white font-bold text-base">{{ config('app.name', 'MyShop') }}</span>
        </a>
        <p class="text-[11px] text-gray-500">© {{ date('Y') }} {{ config('app.name', 'MyShop') }}. All rights reserved.</p>
    </div>
</footer>

<!-- ═══ MODAL ═══ -->
<div id="addressModal">
    <div class="modal-box">

        <!-- Modal Header -->
        <div class="modal-header">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-amz-page rounded-full flex items-center justify-center border border-amz-border">
                    <i data-lucide="map-pin" class="w-4 h-4 text-amz-text-sec"></i>
                </div>
                <h3 id="modalTitle" class="text-[16px] font-bold text-amz-text">Add a New Address</h3>
            </div>
            <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded hover:bg-amz-page transition-colors text-amz-text-sec hover:text-amz-text">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="addressForm" class="modal-body" novalidate>
            @csrf
            <input type="hidden" id="address_id" name="address_id" value="">

            <!-- Country notice -->
            <div class="bg-[#FFF8E7] border border-[#F3A847] rounded-md px-3 py-2.5 mb-4 flex gap-2 items-start">
                <i data-lucide="info" class="w-4 h-4 text-[#E47911] flex-shrink-0 mt-0.5"></i>
                <p class="text-[12px] text-amz-text-sec">Your address information is used only for shipping and billing purposes.</p>
            </div>

            <!-- Full Name + Phone -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="amz-label">Full Name <span>*</span></label>
                    <input type="text" id="full_name" name="full_name" required placeholder="e.g. John Doe" class="amz-input">
                    <p class="error-msg hidden text-[11px] text-amz-deal mt-1" id="err-full_name">Full name is required.</p>
                </div>
                <div>
                    <label class="amz-label">Phone Number <span>*</span></label>
                    <input type="text" id="phone" name="phone" required placeholder="e.g. +962 7..." class="amz-input">
                    <p class="error-msg hidden text-[11px] text-amz-deal mt-1" id="err-phone">Phone number is required.</p>
                </div>
            </div>

            <!-- Street -->
            <div class="mb-4">
                <label class="amz-label">Street Address <span>*</span></label>
                <input type="text" id="street" name="street" required placeholder="House No., Street, Area" class="amz-input">
                <p class="error-msg hidden text-[11px] text-amz-deal mt-1" id="err-street">Street address is required.</p>
            </div>

            <!-- City + State -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="amz-label">City <span>*</span></label>
                    <input type="text" id="city" name="city" required placeholder="e.g. Amman" class="amz-input">
                    <p class="error-msg hidden text-[11px] text-amz-deal mt-1" id="err-city">City is required.</p>
                </div>
                <div>
                    <label class="amz-label">State / Province</label>
                    <input type="text" id="state" name="state" placeholder="Optional" class="amz-input">
                </div>
            </div>

            <!-- Postal + Country -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="amz-label">Postal Code</label>
                    <input type="text" id="postal_code" name="postal_code" placeholder="Optional" class="amz-input">
                </div>

            </div>

            <!-- Default checkbox -->
            <div class="flex items-center gap-2.5 py-3 border-t border-amz-border">
                <input type="checkbox" id="is_default" name="is_default" class="amz-checkbox">
                <label for="is_default" class="text-[13px] text-amz-text cursor-pointer select-none">
                    Make this my default shipping address
                </label>
            </div>
        </form>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button onclick="closeModal()" class="amz-btn-ghost">Cancel</button>
            <button onclick="submitForm()" id="submitBtn" class="amz-btn-primary">
                <i data-lucide="save" class="w-4 h-4" id="submitIcon"></i>
                <span id="submitText">Save Address</span>
            </button>
        </div>

    </div>
</div>

<!-- Toast -->
<div id="toast"></div>

@endsection


<script>
    let isEditing = false;

    // ── MODAL OPEN / CLOSE ──
    function openAddModal() {
        isEditing = false;
        document.getElementById('modalTitle').innerText = 'Add a New Address';
        document.getElementById('addressForm').reset();
        document.getElementById('address_id').value = '';

        document.getElementById('addressModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function openEditModal(address) {
        isEditing = true;
        document.getElementById('modalTitle').innerText = 'Edit Address';
        document.getElementById('address_id').value   = address.id;
        document.getElementById('full_name').value    = address.full_name   || '';
        document.getElementById('phone').value        = address.phone       || '';
        document.getElementById('street').value       = address.street      || '';
        document.getElementById('city').value         = address.city        || '';
        document.getElementById('state').value        = address.state       || '';
        document.getElementById('postal_code').value  = address.postal_code || '';

        document.getElementById('is_default').checked = !!address.is_default;
        clearErrors();
        document.getElementById('addressModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('addressModal').classList.remove('open');
        document.body.style.overflow = '';
    }

    // Close on backdrop click
    document.getElementById('addressModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    // ── VALIDATION ──
    function clearErrors() {
        document.querySelectorAll('.error-msg').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.amz-input').forEach(el => el.style.borderColor = '');
    }

    function validateForm() {
        clearErrors();
        const required = ['full_name', 'phone', 'street', 'city'];
        let valid = true;
        required.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                document.getElementById('err-' + field)?.classList.remove('hidden');
                input.style.borderColor = '#CC0C39';
                input.style.boxShadow = '0 0 0 2px rgba(204,12,57,0.15)';
                valid = false;
            }
        });
        return valid;
    }

    // ── SUBMIT ──
    async function submitForm() {
        if (!validateForm()) return;

        const btn      = document.getElementById('submitBtn');
        const btnText  = document.getElementById('submitText');
        btnText.innerText = 'Saving...';
        btn.disabled = true;

        const id  = document.getElementById('address_id').value;
        const url = id ? `/addresses/${id}` : '/addresses';
        const method = id ? 'PUT' : 'POST';

        const formData = new FormData(document.getElementById('addressForm'));
        if (!id) formData.delete('address_id');
        // Checkbox fix: if unchecked it won't be in FormData
        if (!document.getElementById('is_default').checked) {
            formData.set('is_default', '0');
        } else {
            formData.set('is_default', '1');
        }

        try {
            const response = await fetch(url, {
                method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            });

            if (response.ok) {
                closeModal();
                showToast(id ? 'Address updated successfully' : 'Address added successfully');
                setTimeout(() => window.location.reload(), 800);
            } else {
                const data = await response.json();
                // Show Laravel validation errors if any
                if (data.errors) {
                    Object.entries(data.errors).forEach(([field, msgs]) => {
                        const errEl = document.getElementById('err-' + field);
                        const input = document.getElementById(field);
                        if (errEl) { errEl.innerText = msgs[0]; errEl.classList.remove('hidden'); }
                        if (input) { input.style.borderColor = '#CC0C39'; }
                    });
                } else {
                    showToast(data.message || 'Something went wrong', true);
                }
            }
        } catch (err) {
            showToast('Network error. Please try again.', true);
        } finally {
            btnText.innerText = 'Save Address';
            btn.disabled = false;
        }
    }

    // ── DELETE ──
    async function deleteAddress(id) {
        if (!confirm('Are you sure you want to remove this address?')) return;

        try {
            const response = await fetch(`/addresses/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            });

            if (response.ok) {
                const card = document.getElementById('address-card-' + id);
                if (card) {
                    card.style.transition = 'opacity 200ms, transform 200ms';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.97)';
                    setTimeout(() => { card.remove(); }, 220);
                }
                showToast('Address removed');
            } else {
                showToast('Could not remove address', true);
            }
        } catch {
            showToast('Network error. Please try again.', true);
        }
    }

    // ── TOAST ──
    function showToast(msg, isError = false) {
        const t = document.getElementById('toast');
        t.innerText = msg;
        t.style.background = isError ? '#CC0C39' : '#232F3E';
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2800);
    }

    // ── INIT ──
    document.addEventListener('DOMContentLoaded', () => { lucide.createIcons(); });
</script>

</body>
</html>
