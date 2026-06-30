@extends('layouts.app')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit: {{ $product->name }} — {{ config('app.name', 'MyShop') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'Arial', 'sans-serif'] },
                    colors: {
                        amz: {
                            dark: '#131921', nav: '#232F3E', navHover: '#37475A',
                            orange: '#FF9900', 'orange-light': '#FFA41C',
                            'orange-btn': '#FFD814', 'orange-btn-hover': '#F7CA00',
                            'orange-border': '#F3A847', blue: '#007185',
                            'blue-hover': '#C7511F', link: '#0F1111',
                            'link-hover': '#C7511F', star: '#FFA41C',
                            prime: '#007185', badge: '#CC0C39',
                            green: '#067D62', 'green-light': '#D4F3EA',
                            page: '#EAEDED', card: '#FFFFFF',
                            border: '#DDD', 'border-light': '#E7E7E7',
                            text: '#0F1111', 'text-sec': '#565959',
                            'text-tri': '#737373', 'text-weak': '#979797',
                            deal: '#CC0C39', 'deal-bg': '#FDEEE8',
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
        .amz-input.is-error { border-color: #CC0C39; box-shadow: 0 0 0 3px rgba(204,12,57,0.12); }

        .amz-select { border: 1px solid #DDD; transition: border-color 100ms, box-shadow 100ms; appearance: none; background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23565959' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; }
        .amz-select:focus { border-color: #E77600; box-shadow: 0 0 0 3px rgba(228,166,54,0.3); outline: none; }

        .amz-btn-primary { background: linear-gradient(to bottom, #FFE696, #FFD814); border: 1px solid #F3A847; border-radius: 100px; color: #0F1111; transition: background 100ms; box-shadow: 0 2px 5px rgba(213,166,60,0.4); }
        .amz-btn-primary:hover { background: linear-gradient(to bottom, #FFD814, #F7CA00); }
        .amz-btn-primary:active { background: linear-gradient(to bottom, #F7CA00, #E5B800); }

        .amz-btn-secondary { background: white; border: 1px solid #DDD; border-radius: 100px; color: #0F1111; transition: all 100ms; }
        .amz-btn-secondary:hover { background: #F6F6F7; border-color: #8C9196; }

        .amz-btn-danger { background: white; border: 1px solid #DDD; border-radius: 100px; color: #CC0C39; transition: all 100ms; }
        .amz-btn-danger:hover { background: #FDEEE8; border-color: #CC0C39; }

        .upload-zone { border: 2px dashed #C9CCCF; transition: all 200ms ease; }
        .upload-zone:hover, .upload-zone.dragover { border-color: #E77600; background: #FEF7E4; }

        .img-thumb { transition: all 200ms ease; }
        .img-thumb:hover { box-shadow: 0 2px 12px rgba(0,0,0,0.12); }
        .img-thumb.is-primary { border-color: #E77600 !important; box-shadow: 0 0 0 2px #E77600; }

        .img-overlay { opacity: 0; transition: opacity 200ms ease; }
        .img-thumb:hover .img-overlay { opacity: 1; }

        .form-section { border-bottom: 1px solid #E7E7E7; }
        .form-section:last-child { border-bottom: none; }

        .nav-link { transition: all 100ms; border: 1px solid transparent; border-radius: 3px; }
        .nav-link:hover { border-color: white; }

        .toast { animation: toastIn 300ms ease, toastOut 300ms ease 2.5s forwards; }
        @keyframes toastIn { from { transform: translateY(20px) scale(0.95); opacity: 0; } to { transform: translateY(0) scale(1); opacity: 1; } }
        @keyframes toastOut { to { transform: translateY(20px) scale(0.95); opacity: 0; } }

        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
        .animate-pulse { animation: pulse 1.5s ease-in-out infinite; }

        @media (max-width: 1024px) { .main-preview { display: none; } }
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
                <a href="{{ route('products.show', $product->id) }}" class="text-amz-blue hover:text-amz-link-hover hover:underline line-clamp-1 max-w-[200px]">{{ $product->name }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak flex-shrink-0"></i>
                <span class="text-amz-text font-medium">Edit</span>
            </nav>
        </div>
    </div>


    <!-- ═══════════ MAIN CONTENT ═══════════ -->
    <main class="flex-1">
        <div class="max-w-[1200px] mx-auto px-3 sm:px-4 py-6">

            <!-- Page Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <a href="{{ route('products.show', $product->id) }}" class="p-2 rounded-lg hover:bg-white transition-colors text-amz-text-sec">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-amz-text">Edit Product</h1>
                        <p class="text-[13px] text-amz-text-sec mt-0.5">Update product details for "{{ $product->name }}"</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[12px] text-amz-text-weak font-mono">ID: #{{ $product->id }}</span>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide
                        {{ $product->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $product->status }}
                    </span>
                </div>
            </div>

            <div class="flex gap-6">

                <!-- ═══════════ LEFT: FORM ═══════════ -->
                <div class="flex-1 min-w-0">
                    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" id="editForm">

                        @csrf
                        @method('PUT')

                        <div class="bg-white rounded-lg border border-amz-border overflow-hidden">

                            <!-- Section: Basic Info -->
                            <div class="form-section px-6 py-5">
                                <h2 class="text-[16px] font-bold text-amz-text mb-4 flex items-center gap-2">
                                    <i data-lucide="package" class="w-5 h-5 text-amz-orange"></i>
                                    Basic Information
                                </h2>

                                <div class="space-y-4">
                                    <!-- Name -->
                                    <div>
                                        <label class="block text-[13px] font-medium text-amz-text mb-1.5">
                                            Product Name <span class="text-amz-deal">*</span>
                                        </label>
                                        <input type="text" name="name"
                                            value="{{ old('name', $product->name) }}"
                                            placeholder="Enter product name"
                                            class="amz-input w-full px-4 py-2.5 rounded-lg text-[14px] {{ $errors->has('name') ? 'is-error' : '' }}">
                                        @error('name')
                                        <p class="text-[12px] text-amz-deal mt-1.5 flex items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <label class="block text-[13px] font-medium text-amz-text mb-1.5">Description</label>
                                        <textarea name="description" rows="5"
                                            placeholder="Enter product description"
                                            class="amz-input w-full px-4 py-2.5 rounded-lg text-[14px] resize-y min-h-[120px]">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                        <p class="text-[12px] text-amz-deal mt-1.5 flex items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                        </p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Pricing -->
                            <div class="form-section px-6 py-5">
                                <h2 class="text-[16px] font-bold text-amz-text mb-4 flex items-center gap-2">
                                    <i data-lucide="dollar-sign" class="w-5 h-5 text-amz-orange"></i>
                                    Pricing
                                </h2>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Price -->
                                    <div>
                                        <label class="block text-[13px] font-medium text-amz-text mb-1.5">
                                            Price <span class="text-amz-deal">*</span>
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[14px] text-amz-text-sec font-medium">$</span>
                                            <input type="number" name="price" step="0.01" min="0"
                                                value="{{ old('price', $product->price) }}"
                                                placeholder="0.00"
                                                class="amz-input w-full pl-7 pr-4 py-2.5 rounded-lg text-[14px] {{ $errors->has('price') ? 'is-error' : '' }}">
                                        </div>
                                        @error('price')
                                        <p class="text-[12px] text-amz-deal mt-1.5 flex items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <!-- Discount Price -->
                                    <div>
                                        <label class="block text-[13px] font-medium text-amz-text mb-1.5">
                                            Compare-at Price
                                        </label>
                                        <div class="relative">
                                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-[14px] text-amz-text-sec font-medium">$</span>
                                            <input type="number" name="discount_price" step="0.01" min="0"
                                                value="{{ old('discount_price', $product->discount_price) }}"
                                                placeholder="0.00"
                                                class="amz-input w-full pl-7 pr-4 py-2.5 rounded-lg text-[14px]">
                                        </div>
                                        <p class="text-[11px] text-amz-text-weak mt-1.5">Optional — shown as strikethrough price</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Organization -->
                            <div class="form-section px-6 py-5">
                                <h2 class="text-[16px] font-bold text-amz-text mb-4 flex items-center gap-2">
                                    <i data-lucide="folder-open" class="w-5 h-5 text-amz-orange"></i>
                                    Organization
                                </h2>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Category -->
                                    <div>
                                        <label class="block text-[13px] font-medium text-amz-text mb-1.5">
                                            Category <span class="text-amz-deal">*</span>
                                        </label>
                                        <select name="category_id"
                                            class="amz-select w-full px-4 py-2.5 rounded-lg text-[14px] pr-10 {{ $errors->has('category_id') ? 'is-error' : '' }}">
                                            <option value="">Select category</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <p class="text-[12px] text-amz-deal mt-1.5 flex items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <!-- SKU -->
                                    <div>
                                        <label class="block text-[13px] font-medium text-amz-text mb-1.5">SKU</label>
                                        <input type="text" name="sku"
                                            value="{{ old('sku', $product->sku) }}"
                                            placeholder="e.g. TSHIRT-BLK-M"
                                            class="amz-input w-full px-4 py-2.5 rounded-lg text-[14px] font-mono">
                                        <p class="text-[11px] text-amz-text-weak mt-1.5">Stock Keeping Unit — auto-generated if left empty</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Inventory -->
                            <div class="form-section px-6 py-5">
                                <h2 class="text-[16px] font-bold text-amz-text mb-4 flex items-center gap-2">
                                    <i data-lucide="warehouse" class="w-5 h-5 text-amz-orange"></i>
                                    Inventory
                                </h2>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <!-- Quantity -->
                                    <div>
                                        <label class="block text-[13px] font-medium text-amz-text mb-1.5">
                                            Quantity <span class="text-amz-deal">*</span>
                                        </label>
                                        <input type="number" name="quantity" min="0"
                                            value="{{ old('quantity', $product->quantity) }}"
                                            class="amz-input w-full px-4 py-2.5 rounded-lg text-[14px] {{ $errors->has('quantity') ? 'is-error' : '' }}">
                                        @error('quantity')
                                        <p class="text-[12px] text-amz-deal mt-1.5 flex items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                                        </p>
                                        @enderror
                                    </div>

                                    <!-- Reserved (read-only info) -->
                                    <div>
                                        <label class="block text-[13px] font-medium text-amz-text mb-1.5">Reserved</label>
                                        <div class="w-full px-4 py-2.5 rounded-lg text-[14px] bg-amz-page border border-amz-border-light text-amz-orange font-medium">
                                            {{ $product->reserved_quantity ?? 0 }}
                                        </div>
                                        <p class="text-[11px] text-amz-text-weak mt-1.5">Items in pending orders</p>
                                    </div>

                                    <!-- Available (read-only) -->
                                    <div>
                                        <label class="block text-[13px] font-medium text-amz-text mb-1.5">Available</label>
                                        <div class="w-full px-4 py-2.5 rounded-lg text-[14px] bg-green-50 border border-green-200 text-amz-green font-bold">
                                            {{ $product->available }}
                                        </div>
                                        <p class="text-[11px] text-amz-text-weak mt-1.5">Quantity minus reserved</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Status -->
                            <div class="form-section px-6 py-5">
                                <h2 class="text-[16px] font-bold text-amz-text mb-4 flex items-center gap-2">
                                    <i data-lucide="toggle-right" class="w-5 h-5 text-amz-orange"></i>
                                    Status
                                </h2>

                                <div class="flex items-center gap-6">
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" name="status" value="available" {{ old('status', $product->status) === 'available' ? 'checked' : '' }}
                                            class="w-4 h-4 text-amz-orange border-gray-300 focus:ring-amz-orange/20">
                                        <div>
                                            <span class="text-[14px] font-medium text-amz-text group-hover:text-amz-orange transition-colors">Available</span>
                                            <p class="text-[11px] text-amz-text-weak">Visible and purchasable by customers</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio" name="status" value="unavailable" {{ old('status', $product->status) === 'unavailable' ? 'checked' : '' }}
                                            class="w-4 h-4 text-amz-orange border-gray-300 focus:ring-amz-orange/20">
                                        <div>
                                            <span class="text-[14px] font-medium text-amz-text group-hover:text-amz-orange transition-colors">Unavailable</span>
                                            <p class="text-[11px] text-amz-text-weak">Hidden from customers</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Section: Images -->
                            <div class="px-6 py-5">
                                <h2 class="text-[16px] font-bold text-amz-text mb-4 flex items-center gap-2">
                                    <i data-lucide="image" class="w-5 h-5 text-amz-orange"></i>
                                    Add New Images
                                </h2>

                                <div id="uploadZone" class="upload-zone rounded-xl p-6 flex flex-col items-center justify-center cursor-pointer"
                                     onclick="document.getElementById('imageInput').click()"
                                     ondragover="event.preventDefault(); this.classList.add('dragover')"
                                     ondragleave="this.classList.remove('dragover')"
                                     ondrop="handleDrop(event)">
                                    <div class="w-12 h-12 bg-amz-page rounded-full flex items-center justify-center mb-2">
                                        <i data-lucide="upload-cloud" class="w-6 h-6 text-amz-text-weak"></i>
                                    </div>
                                    <p class="text-[13px] font-medium text-amz-text-sec">Click to upload or drag and drop</p>
                                    <p class="text-[11px] text-amz-text-weak mt-1">PNG, JPG, WEBP up to 2MB · Multiple files</p>
                                </div>

                                <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="hidden" onchange="handleFileSelect(event)">

                                <p class="text-[12px] text-amz-text-weak mt-2 flex items-center gap-1">
                                    <i data-lucide="info" class="w-3.5 h-3.5"></i>
                                    New images will appear in the gallery on the right with a "NEW" badge
                                </p>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="px-6 py-5 bg-amz-page/50 flex items-center justify-between gap-3 flex-wrap">
                                <a href="{{ route('products.show', $product->id) }}" class="amz-btn-secondary px-6 py-2.5 text-[14px] font-medium">
                                    Cancel
                                </a>
                                <div class="flex items-center gap-2">
                                    <button type="submit" class="amz-btn-primary px-8 py-2.5 text-[14px] font-semibold flex items-center gap-2">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>


                <!-- ═══════════ RIGHT: IMAGE GALLERY ═══════════ -->
                <div class="w-[340px] flex-shrink-0 hidden lg:block">

                    <!-- Main Preview (sticky) -->
                    <div class="sticky top-[120px] space-y-4">
                        <div class="bg-white rounded-lg border border-amz-border overflow-hidden">
                            <div class="px-5 py-4 border-b border-amz-border-light">
                                <h3 class="text-[15px] font-bold text-amz-text flex items-center gap-2">
                                    <i data-lucide="images" class="w-4 h-4 text-amz-orange"></i>
                                    Current Images
                                    <span class="text-[12px] font-normal text-amz-text-weak">({{ $product->media->count() }})</span>
                                </h3>
                            </div>

                            <!-- Main Image Preview -->
                            <div class="p-4">
                                <div class="main-preview aspect-square bg-white border border-amz-border-light rounded-lg overflow-hidden mb-4 flex items-center justify-center">
                                    @if($product->image_url && !str_contains($product->image_url, 'default.png'))
                                        <img id="mainImage" src="{{ $product->image_url }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain p-2">
                                    @else
                                        <div id="mainImage" class="flex flex-col items-center justify-center text-amz-text-weak p-4">
                                            <i data-lucide="image" class="w-12 h-12 mb-2"></i>
                                            <span class="text-[13px]">No image</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Thumbnails Grid -->
                                @if($product->media->count() > 0)
                                <div id="gallery" class="grid grid-cols-3 gap-2">
                                    @foreach($product->media->sortBy('sort_order') as $image)
                                    <div class="img-thumb relative rounded-lg border-2 overflow-hidden cursor-pointer aspect-square
                                        {{ $image->is_primary ? 'is-primary' : 'border-amz-border-light' }}"
                                         data-id="{{ $image->id }}">
                                        <img src="{{ asset('storage/'.$image->path) }}" alt=""
                                             class="w-full h-full object-cover"
                                             onclick="preview(this)">

                                        @if($image->is_primary)
                                        <span class="absolute top-1 left-1 bg-amz-orange text-white text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center gap-0.5">
                                            <i data-lucide="star" class="w-2.5 h-2.5"></i> MAIN
                                        </span>
                                        @endif

                                        <div class="img-overlay absolute inset-0 bg-black/50 rounded-lg flex flex-col items-center justify-center gap-1.5">
                                            <button type="button" onclick="setMain({{ $image->id }})"
                                                class="bg-white text-amz-text px-2.5 py-1 rounded text-[11px] font-medium hover:bg-amz-orange-hover transition-colors flex items-center gap-1">
                                                <i data-lucide="star" class="w-3 h-3"></i> Set Main
                                            </button>
                                            <button type="button" onclick="deleteImage({{ $image->id }}, this)"
                                                class="bg-white text-amz-deal px-2.5 py-1 rounded text-[11px] font-medium hover:bg-red-100 transition-colors flex items-center gap-1">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-8">
                                    <i data-lucide="image-off" class="w-10 h-10 text-amz-text-weak mx-auto mb-2"></i>
                                    <p class="text-[13px] text-amz-text-sec">No images uploaded yet</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Danger Zone -->
                        <div class="bg-white rounded-lg border border-red-200 overflow-hidden">
                            <div class="px-5 py-4 border-b border-red-100">
                                <h3 class="text-[15px] font-bold text-amz-deal flex items-center gap-2">
                                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                    Danger Zone
                                </h3>
                            </div>
                            <div class="p-5">
                                <p class="text-[13px] text-amz-text-sec mb-3">Permanently remove this product from your store. This action cannot be undone.</p>
                                <form method="POST" action="{{ route('products.destroy', $product->id) }}" onsubmit="return confirm('Are you sure you want to DELETE \"{{ addslashes($product->name) }}\"?\n\nThis action CANNOT be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="amz-btn-danger w-full py-2.5 text-[13px] font-medium flex items-center justify-center gap-2">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        Delete this product
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>




    <!-- Toast Container -->
    <div id="toastContainer" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] flex flex-col items-center gap-2"></div>
@endsection

    <script>
        // ─── إضافة صور جديدة للـ Gallery ───
        function addNewImagesToGallery(files) {
            const gallery = document.getElementById('gallery');

            // لو ما فيه صور أصلاً، نظف الـ "No images" رسالة
            const noImagesMsg = gallery?.querySelector('.no-images-msg');
            if (noImagesMsg) noImagesMsg.remove();

            files.forEach((file, index) => {
                if (file.size > 2 * 1024 * 1024) {
                    showToast(file.name + ' exceeds 2MB', 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = (e) => {
                    // ننشئ عنصر thumbnail جديد يشبه اللي موجودة
                    const div = document.createElement('div');
                    div.className = 'img-thumb relative rounded-lg border-2 border-dashed border-amz-orange overflow-hidden cursor-pointer aspect-square new-upload';
                    div.dataset.newImage = 'true';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="" class="w-full h-full object-cover">

                        <span class="absolute top-1 left-1 bg-amz-orange text-white text-[9px] font-bold px-1.5 py-0.5 rounded animate-pulse">
                            NEW
                        </span>

                        <div class="absolute bottom-0 left-0 right-0 bg-black/70 text-white text-[9px] text-center py-0.5 truncate px-1 opacity-80">
                            ${file.name}
                        </div>

                        <div class="img-overlay absolute inset-0 bg-black/50 rounded-lg flex items-center justify-center">
                            <span class="bg-white text-amz-text px-2 py-1 rounded text-[11px] font-medium flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Pending
                            </span>
                        </div>
                    `;

                    // لما تضغط عليه يعرضه في الـ main preview
                    div.addEventListener('click', function() {
                        preview(this.querySelector('img'));
                    });

                    gallery.appendChild(div);

                    // لو ما فيه صورة رئيسية، نعرض أول صورة (جديدة أو قديمة)
                    if (!document.querySelector('.img-thumb.is-primary')) {
                        preview(div.querySelector('img'));
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        // ─── File Select Handler ───
        function handleFileSelect(event) {
            const files = Array.from(event.target.files);
            if (files.length === 0) return;
            addNewImagesToGallery(files);
        }

        // ─── Drag & Drop Handler ───
        function handleDrop(event) {
            event.preventDefault();
            document.getElementById('uploadZone').classList.remove('dragover');

            const files = Array.from(event.dataTransfer.files).filter(f => f.type.startsWith('image/'));
            if (files.length === 0) return;

            // نضيف الملفات لـ input المخفي عشان ترسل مع الفورم
            const dataTransfer = new DataTransfer();
            files.forEach(f => dataTransfer.items.add(f));
            document.getElementById('imageInput').files = dataTransfer.files;

            addNewImagesToGallery(files);
        }

        // ─── Image Preview (الصور الموجودة + الجديدة) ───
        function preview(imgEl) {
            const mainImg = document.getElementById('mainImage');
            if (mainImg.tagName === 'IMG') {
                mainImg.src = imgEl.src;
            } else {
                mainImg.outerHTML = `<img id="mainImage" src="${imgEl.src}" class="max-w-full max-h-full object-contain p-2">`;
            }

            document.querySelectorAll('.img-thumb').forEach(t => {
                t.classList.remove('is-primary');
                if (!t.dataset.newImage) {
                    t.classList.add('border-amz-border-light');
                }
            });
            const thumb = imgEl.closest('.img-thumb');
            if (thumb) {
                thumb.classList.add('is-primary');
                if (!thumb.dataset.newImage) {
                    thumb.classList.remove('border-amz-border-light');
                }
            }
        }

       // ─── Set Main ───
function setMain(id) {
    fetch(`/media/${id}/set-main`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json",
            "Content-Type": "application/json"
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    })
    .then(data => {
        if (data.success) {
            // ✅ حدّث الـ UI بدون reload

            // احذف الـ "MAIN" badge من جميع الصور
            document.querySelectorAll('.img-thumb').forEach(t => {
                t.classList.remove('is-primary');
                t.classList.add('border-amz-border-light');

                const badge = t.querySelector('span[class*="bg-amz-orange"]');
                if (badge) badge.remove();
            });

            // أضف الـ "MAIN" badge للصورة الجديدة
            const newMainThumb = document.querySelector(`[data-id="${id}"]`);
            if (newMainThumb) {
                newMainThumb.classList.add('is-primary');
                newMainThumb.classList.remove('border-amz-border-light');

                // أضف النجمة والـ MAIN label
                const img = newMainThumb.querySelector('img');
                const badge = document.createElement('span');
                badge.className = 'absolute top-1 left-1 bg-amz-orange text-white text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center gap-0.5';
                badge.innerHTML = `<i data-lucide="star" class="w-2.5 h-2.5"></i> MAIN`;
                newMainThumb.appendChild(badge);

                // عدّل الصورة الرئيسية في الـ preview
                if (img) {
                    preview(img);
                }

                // رينيو الـ icons بتوع lucide
                lucide.createIcons();
            }

            showToast('Main image updated!');
        } else {
            showToast(data.message || 'Failed', 'error');
        }
    })
    .catch(err => {
        console.error('setMain:', err);
        showToast('Network error — check console (F12)', 'error');
    });
}

function deleteImage(id, button) {
    if (!confirm('Delete this image?')) return;

    const thumb = button.closest('[data-id]');
    if (!thumb) return;

    const overlay = thumb.querySelector('.img-overlay');
    if (overlay) {
        overlay.innerHTML = '<div style="width:24px;height:24px;border:3px solid white;border-top-color:transparent;border-radius:50%;animation:spin 0.6s linear infinite"></div>';
        overlay.style.opacity = '1';
        overlay.style.display = 'flex';
    }

    fetch(`/media/${id}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json"
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('HTTP ' + res.status);
        return res.json();
    })
    .then(data => {
        if (data.success) {
            const wasMain = thumb.classList.contains('is-primary');

            thumb.style.transition = 'all 300ms ease';
            thumb.style.opacity = '0';
            thumb.style.transform = 'scale(0.8)';

            setTimeout(() => {
                thumb.remove();

                // ✅ إذا كانت صورة رئيسية، اعرض الصورة الجديدة مع الـ MAIN badge
                if (wasMain) {
                    const firstThumb = document.querySelector('.img-thumb');
                    if (firstThumb) {
                        const firstImg = firstThumb.querySelector('img');

                        // حدّث الـ classes عشان تصير primary
                        firstThumb.classList.add('is-primary');
                        firstThumb.classList.remove('border-amz-border-light');

                        // أضف الـ MAIN badge
                        let badge = firstThumb.querySelector('span[class*="bg-amz-orange"]');
                        if (!badge) {
                            badge = document.createElement('span');
                            badge.className = 'absolute top-1 left-1 bg-amz-orange text-white text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center gap-0.5';
                            badge.innerHTML = `<i data-lucide="star" class="w-2.5 h-2.5"></i> MAIN`;
                            firstThumb.appendChild(badge);
                            lucide.createIcons(); // ✅ حدّث الـ icons
                        }

                        // عرّض الصورة في الـ preview
                        if (firstImg) {
                            preview(firstImg);
                        }
                    } else {
                        // لو ما فيه صور متبقية
                        const mainImage = document.getElementById('mainImage');
                        if (mainImage) {
                            mainImage.outerHTML = `
                                <div id="mainImage" class="flex flex-col items-center justify-center text-amz-text-weak p-4">
                                    <i data-lucide="image" class="w-12 h-12 mb-2"></i>
                                    <span class="text-[13px]">No image</span>
                                </div>`;
                        }
                    }
                }

                // حدّث العدد
                const countSpan = document.querySelector('h3 span');
                if (countSpan) {
                    const match = countSpan.textContent.match(/\d+/);
                    if (match) {
                        const newCount = parseInt(match[0]) - 1;
                        countSpan.textContent = `(${newCount})`;
                    }
                }

                showToast('Image deleted');
            }, 300);
        } else {
            showToast(data.message || 'Failed', 'error');
        }
    })
    .catch(err => {
        console.error('deleteImage:', err);
        showToast('Error: ' + err.message, 'error');
    });
}

        // ─── Toast ───
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            const bg = type === 'error' ? 'bg-red-600' : 'bg-amz-dark';
            toast.className = `toast flex items-center gap-2.5 ${bg} text-white px-5 py-3 rounded-lg shadow-xl min-w-[280px]`;
            toast.innerHTML = `<svg class="w-5 h-5 ${type === 'error' ? 'text-red-200' : 'text-amz-orange'} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-[13px] font-medium">${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // ─── Init ───
        lucide.createIcons();
    </script>
</body>
</html>
