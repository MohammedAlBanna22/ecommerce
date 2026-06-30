@extends('layouts.app')
@section('content')

    <!-- ═══════════ BREADCRUMB ═══════════ -->
    <div class="bg-white border-b border-amz-border">
        <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-2">
            <nav class="flex items-center gap-1 text-[12px]">
                <a href="{{ route('home') }}" class="text-amz-blue hover:text-amz-link-hover hover:underline">{{ config('app.name', 'MyShop') }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
                <span class="text-amz-text font-medium">Categories</span>
            </nav>
        </div>
    </div>

    <!-- ═══════════ MAIN CONTENT ═══════════ -->
    <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-4">

        <!-- Page Header -->
        <div class="bg-white rounded-lg border border-amz-border px-4 py-4 mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="bg-amz-page p-2 rounded-lg border border-amz-border">
                    <i data-lucide="layout-grid" class="w-6 h-6 text-amz-blue"></i>
                </div>
                <div>
                    <h1 class="text-[20px] font-bold text-amz-text leading-tight">Shop by Category</h1>
                    <p class="text-[13px] text-amz-text-sec"><span class="font-bold text-amz-text">{{ $categories->count() }}</span> categories</p>
                </div>
            </div>

            @auth
            <a href="{{ route('categories.create') }}"
               class="inline-flex items-center justify-center gap-2 amz-btn-cart px-5 py-2.5 text-[13px] font-bold w-full sm:w-auto">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add New Category
            </a>
            @endauth
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 border-l-4 border-l-amz-green rounded-md p-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 text-amz-green"></i>
                <span class="text-[13px] font-semibold text-amz-text">{{ session('success') }}</span>
            </div>
            <button onclick="this.closest('div.mb-4').remove()" class="text-amz-text-weak hover:text-amz-deal">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        @endif

        <!-- Categories Grid -->
        @if($categories->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">

            @foreach($categories as $category)
            <div class="product-card bg-white rounded-lg border border-amz-border overflow-hidden group">

                <!-- Image -->
                <a href="{{ route('products.index', ['category_id' => $category->id]) }}" class="card-img block relative overflow-hidden bg-white">
                    <div class="aspect-square p-4 flex items-center justify-center bg-amz-page/40">
                        @if($category->image)
                            <img src="{{ asset('storage/'.$category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-amz-text-weak">
                                <i data-lucide="image" class="w-10 h-10 mb-1"></i>
                                <span class="text-[11px]">No image</span>
                            </div>
                        @endif
                    </div>

                    <span class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm text-amz-text-sec text-[11px] font-bold px-2 py-0.5 rounded-full border border-amz-border">
                        #{{ $category->id }}
                    </span>
                </a>

                <div class="p-3">
                    <a href="{{ route('products.index', ['category_id' => $category->id]) }}">
                        <h3 class="text-[13px] font-bold text-amz-text line-clamp-1 hover:text-amz-link-hover transition-colors mb-1">
                            {{ $category->name }}
                        </h3>
                    </a>

                    <p class="text-[12px] text-amz-text-sec line-clamp-2 min-h-[32px] mb-2">
                        {{ $category->description ?: 'No description provided' }}
                    </p>

                    <div class="flex items-center gap-1 text-[11px] text-amz-text-weak mb-3 pb-3 border-b border-amz-border-light">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        Updated {{ $category->updated_at->diffForHumans() }}
                    </div>

                    <a href="{{ route('products.index', ['category_id' => $category->id]) }}"
                       class="block text-center w-full py-2 text-[12px] font-bold text-amz-blue border border-amz-border rounded-md hover:bg-amz-page transition-colors mb-2">
                        Shop Now
                    </a>

                    @auth
                    <div class="flex items-center justify-between pt-1">
                        <a href="{{ route('categories.edit', $category->id) }}" class="text-[11px] font-medium text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                            Edit
                        </a>
                        <form method="POST"
                              action="{{ route('categories.destroy', $category->id) }}"
                              onsubmit="return confirm('Are you sure you want to delete &quot;{{ $category->name }}&quot;? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[11px] font-medium text-red-600 hover:text-red-800 hover:underline transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
            @endforeach

        </div>

        @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg border border-amz-border p-12 text-center">
            <div class="w-20 h-20 bg-amz-page rounded-full flex items-center justify-center mx-auto mb-5">
                <i data-lucide="layout-grid" class="w-10 h-10 text-amz-text-weak"></i>
            </div>
            <h2 class="text-xl font-bold text-amz-text mb-2">No Categories Yet</h2>
            <p class="text-[14px] text-amz-text-sec mb-6 max-w-md mx-auto">Get started by creating your first category to organize your products.</p>
            @auth
            <a href="{{ route('categories.create') }}" class="amz-btn-buy inline-flex items-center gap-2 px-6 py-2.5 text-[14px] font-bold text-white">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Create Your First Category
            </a>
            @endauth
        </div>
        @endif

    </div>

    <script>
        lucide.createIcons();
    </script>

@endsection
