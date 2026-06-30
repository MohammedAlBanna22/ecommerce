@extends('layouts.app')
@section('content')

<style>
    :root {
        --amz-blue: #0066c0;
        --amz-orange: #ff9900;
        --amz-deal: #cc0c00;
        --amz-green: #4CAF50;
        --amz-text: #222222;
        --amz-text-sec: #555555;
        --amz-text-weak: #999999;
        --amz-border: #e1e4e6;
        --amz-border-light: #f0f0f0;
        --amz-page: #f5f5f5;
        --amz-link-hover: #c45911;
    }

    .amz-input {
        border: 1px solid var(--amz-border);
        color: var(--amz-text);
    }

    .amz-input:focus {
        outline: none;
        border-color: var(--amz-blue);
        box-shadow: 0 0 0 2px rgba(0, 102, 192, 0.1);
    }

    .image-preview {
        max-height: 300px;
        object-fit: contain;
    }

    .upload-zone {
        transition: all 0.2s ease;
    }

    .upload-zone:hover {
        border-color: var(--amz-blue);
        background-color: rgba(0, 102, 192, 0.02);
    }

    .upload-zone.drag-over {
        border-color: var(--amz-blue);
        background-color: rgba(0, 102, 192, 0.05);
    }
</style>

<!-- ═══════════ BREADCRUMB ═══════════ -->
<div class="bg-white border-b border-[var(--amz-border)]">
    <div class="max-w-[1200px] mx-auto px-4 py-2">
        <nav class="flex items-center gap-1 text-[12px]">
            <a href="{{ route('home') }}" class="text-[var(--amz-blue)] hover:text-[var(--amz-link-hover)] hover:underline">{{ config('app.name', 'MyShop') }}</a>
            <i data-lucide="chevron-right" class="w-3 h-3 text-[var(--amz-text-weak)]"></i>
            <span class="text-[var(--amz-text)] font-medium">Categories</span>
            <i data-lucide="chevron-right" class="w-3 h-3 text-[var(--amz-text-weak)]"></i>
            <span class="text-[var(--amz-text)] font-medium">Create New</span>
        </nav>
    </div>
</div>

<!-- ═══════════ MAIN CONTENT ═══════════ -->
<div class="bg-[var(--amz-page)] min-h-screen py-8">
    <div class="max-w-[800px] mx-auto px-4">

        <!-- Back Button -->
        <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-2 text-[var(--amz-blue)] hover:text-[var(--amz-link-hover)] mb-6 text-[14px] font-medium hover:underline">
            <i data-lucide="chevron-left" class="w-4 h-4"></i>
            Back to Categories
        </a>

        <!-- Card Header -->
        <div class="bg-white rounded-lg border border-[var(--amz-border)] overflow-hidden">

            <!-- Header Section -->
            <div class="bg-gradient-to-r from-[#2c3e50] to-[#34495e] px-6 py-8 text-white">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i data-lucide="folder-plus" class="w-6 h-6"></i>
                    </div>
                    <h1 class="text-2xl font-bold">Create New Category</h1>
                </div>
                <p class="text-white/80 text-[14px]">Add a new product category to your store</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <!-- Success Message -->
                @if(session('success'))
                <div class="flex items-start gap-3 p-4 bg-[var(--amz-green)]/10 border border-[var(--amz-green)] rounded-lg">
                    <i data-lucide="check-circle" class="w-5 h-5 text-[var(--amz-green)] flex-shrink-0 mt-0.5"></i>
                    <span class="text-[14px] text-[var(--amz-green)] font-medium">{{ session('success') }}</span>
                </div>
                @endif

                <!-- Category Name -->
                <div>
                    <label for="name" class="block text-[14px] font-bold text-[var(--amz-text)] mb-2">
                        Category Name <span class="text-[var(--amz-deal)]">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        placeholder="e.g., Electronics, Clothing, Books, Home & Kitchen..."
                        value="{{ old('name') }}"
                        class="amz-input w-full px-4 py-3 rounded text-[14px] bg-white"
                        required
                    >
                    @error('name')
                    <p class="mt-1.5 text-[13px] text-[var(--amz-deal)] flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-[14px] font-bold text-[var(--amz-text)] mb-2">
                        Description
                    </label>
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        placeholder="Provide a brief description of this category (optional)..."
                        class="amz-input w-full px-4 py-3 rounded text-[14px] bg-white resize-none"
                    >{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1.5 text-[13px] text-[var(--amz-deal)]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div>
                    <label for="image" class="block text-[14px] font-bold text-[var(--amz-text)] mb-2">
                        Category Image
                    </label>

                    <div class="upload-zone border-2 border-dashed border-[var(--amz-border)] rounded-lg p-8 bg-white cursor-pointer" id="uploadZone">

                        <!-- Preview Container -->
                        <div id="imagePreviewContainer" class="hidden text-center mb-4">
                            <img id="imagePreview" src="#" alt="Preview" class="image-preview mx-auto rounded-lg shadow-sm mb-4">
                            <button
                                type="button"
                                onclick="removeImage()"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-[var(--amz-deal)]/10 text-[var(--amz-deal)] rounded hover:bg-[var(--amz-deal)]/20 transition text-[13px] font-medium"
                            >
                                <i data-lucide="x" class="w-4 h-4"></i>
                                Remove Image
                            </button>
                        </div>

                        <!-- Upload Area -->
                        <div id="uploadArea" class="text-center">
                            <div class="flex justify-center mb-3">
                                <div class="w-12 h-12 bg-[var(--amz-page)] rounded-full flex items-center justify-center">
                                    <i data-lucide="image" class="w-6 h-6 text-[var(--amz-text-weak)]"></i>
                                </div>
                            </div>
                            <p class="text-[14px] text-[var(--amz-text)] mb-1">
                                <span class="font-bold text-[var(--amz-blue)]">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-[12px] text-[var(--amz-text-weak)]">PNG, JPG, GIF up to 2MB</p>
                            <input
                                type="file"
                                name="image"
                                id="image"
                                accept="image/*"
                                class="hidden"
                                onchange="previewImage(this)"
                            >
                        </div>
                    </div>

                    @error('image')
                    <p class="mt-1.5 text-[13px] text-[var(--amz-deal)]">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-[var(--amz-border)]">
                    <button
                        type="submit"
                        class="w-full py-3 px-4 bg-[var(--amz-orange)] text-white font-bold text-[16px] rounded hover:bg-[#ec7211] active:bg-[#da6f0e] transition-colors shadow-sm hover:shadow-md"
                    >
                        <span class="flex items-center justify-center gap-2">
                            <i data-lucide="check" class="w-5 h-5"></i>
                            Create Category
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
    setupDragAndDrop();
});

function setupDragAndDrop() {
    const zone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('image');

    zone.addEventListener('click', () => fileInput.click());

    zone.addEventListener('dragover', (e) => {
        e.preventDefault();
        zone.classList.add('drag-over');
    });

    zone.addEventListener('dragleave', () => {
        zone.classList.remove('drag-over');
    });

    zone.addEventListener('drop', (e) => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            previewImage(fileInput);
        }
    });
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreviewContainer').classList.remove('hidden');
            document.getElementById('uploadArea').classList.add('hidden');
            lucide.createIcons();
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreviewContainer').classList.add('hidden');
    document.getElementById('uploadArea').classList.remove('hidden');
}
</script>

@endsection
