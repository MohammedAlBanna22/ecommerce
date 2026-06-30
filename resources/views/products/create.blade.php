@extends('layouts.app')

@section('content')

<div class="max-w-[1200px] mx-auto px-3 sm:px-4 py-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('products.index') }}" class="p-2 rounded-lg hover:bg-white transition-colors text-amz-text-sec">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-amz-text">Create New Product</h1>
                <p class="text-[13px] text-amz-text-sec mt-0.5">Add a new product to your store</p>
            </div>
        </div>


    </div>

    <!-- Errors -->
    @if ($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <h3 class="text-sm font-bold text-red-600 mb-2">Please fix errors:</h3>
        <ul class="space-y-1">
            @foreach ($errors->all() as $message)
                <li class="text-sm text-red-500 flex items-center gap-2">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                    {{ $message }}
                </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" id="createForm">
        @csrf

        <div class="bg-white rounded-lg border border-amz-border overflow-hidden">

            <!-- Basic -->
            <div class="px-6 py-5 border-b">
                <h2 class="font-bold mb-4 flex items-center gap-2">
                    <i data-lucide="package" class="w-5 h-5 text-amz-orange"></i>
                    Basic Information
                </h2>

                <input type="text" name="name"
                       value="{{ old('name') }}"
                       placeholder="Product name"
                       class="w-full border rounded px-4 py-2 mb-3">

                <textarea name="description"
                          rows="4"
                          class="w-full border rounded px-4 py-2"
                          placeholder="Description">{{ old('description') }}</textarea>
            </div>

           <!-- Pricing -->
<div class="px-6 py-5 border-b">
    <h2 class="font-bold mb-4 flex items-center gap-2">
        <i data-lucide="dollar-sign" class="w-5 h-5 text-amz-orange"></i>
        Pricing
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <!-- Price -->
        <div>
            <label class="text-sm font-medium text-gray-700 mb-1 block">
                Price <span class="text-red-500">*</span>
            </label>

            <div class="relative">
                <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                <input type="number" step="0.01" min="0"
                       name="price"
                       value="{{ old('price') }}"
                       class="w-full border rounded px-8 py-2"
                       placeholder="0.00">
            </div>
        </div>

        <!-- Discount -->
        <div>
            <label class="text-sm font-medium text-gray-700 mb-1 block">
                Discount Price
            </label>

            <div class="relative">
                <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                <input type="number" step="0.01" min="0"
                       name="discount_price"
                       value="{{ old('discount_price') }}"
                       class="w-full border rounded px-8 py-2"
                       placeholder="0.00">
            </div>

            <p class="text-xs text-gray-400 mt-1">
                Optional — shown as crossed price
            </p>
        </div>

    </div>
</div>

            <!-- Organization -->
            <div class="px-6 py-5 border-b">
                <h2 class="font-bold mb-4 flex items-center gap-2">
                    <i data-lucide="folder-open" class="w-5 h-5 text-amz-orange"></i>
                    Organization
                </h2>

                <select name="category_id" class="w-full border rounded px-4 py-2 mb-3">
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>


            </div>

            <!-- Inventory -->
            <div class="px-6 py-5 border-b">
                <h2 class="font-bold mb-4 flex items-center gap-2">
                    <i data-lucide="warehouse" class="w-5 h-5 text-amz-orange"></i>
                    Inventory
                </h2>

                <input type="number" name="quantity"
                       value="{{ old('quantity', 0) }}"
                       class="w-full border rounded px-4 py-2">
            </div>

            <!-- Images -->
            <div class="px-6 py-5 border-b">
                <h2 class="font-bold mb-4 flex items-center gap-2">
                    <i data-lucide="image" class="w-5 h-5 text-amz-orange"></i>
                    Images
                </h2>

                <input type="file" name="images[]" multiple class="w-full">
            </div>

            <!-- Submit -->
            <div class="px-6 py-5 flex justify-between">
                <a href="{{ route('products.index') }}" class="px-4 py-2 border rounded">
                    Cancel
                </a>

                <button class="px-6 py-2 bg-yellow-400 rounded font-semibold">
                    Create Product
                </button>
            </div>

        </div>
    </form>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') lucide.createIcons();
});
</script>
@endpush
