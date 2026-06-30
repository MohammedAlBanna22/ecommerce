@extends('layouts.app')
@section('content')

    <!-- ═══════════ BREADCRUMB ═══════════ -->
    <div class="bg-white border-b border-amz-border">
        <div class="max-w-[1500px] mx-auto px-3 sm:px-4 py-2">
            <nav class="flex items-center gap-1 text-[12px]">
                <a href="{{ route('home') }}" class="text-amz-blue hover:text-amz-link-hover hover:underline">{{ config('app.name', 'MyShop') }}</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
                <a href="{{ route('categories.index') }}" class="text-amz-blue hover:text-amz-link-hover hover:underline">Categories</a>
                <i data-lucide="chevron-right" class="w-3 h-3 text-amz-text-weak"></i>
                <span class="text-amz-text font-medium">Edit {{ $category->name }}</span>
            </nav>
        </div>
    </div>

    <!-- ═══════════ MAIN CONTENT ═══════════ -->
    <div class="max-w-2xl mx-auto px-3 sm:px-4 py-6">

        <div class="bg-white rounded-lg border border-amz-border overflow-hidden">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-amz-border bg-amz-page/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white p-2 rounded-lg border border-amz-border">
                        <i data-lucide="pencil" class="w-5 h-5 text-amz-orange"></i>
                    </div>
                    <div>
                        <h2 class="text-[18px] font-bold text-amz-text leading-tight">Edit Category</h2>
                        <p class="text-[12px] text-amz-text-sec">Update category information below</p>
                    </div>
                </div>
                <a href="{{ route('categories.index') }}" class="text-[12px] text-amz-blue hover:text-amz-link-hover hover:underline flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    Back
                </a>
            </div>

            <!-- Form -->
            <form method="POST"
                  action="{{ route('categories.update', $category->id) }}"
                  enctype="multipart/form-data"
                  class="p-6 space-y-5">

                @csrf
                @method('PUT')

                <!-- Current Image Display -->
                @if($category->image)
                <div class="bg-amz-page/40 border border-amz-border rounded-lg p-4">
                    <div class="flex items-start gap-4">
                        <img src="{{ asset('storage/'.$category->image) }}"
                             alt="{{ $category->name }}"
                             class="w-24 h-24 object-cover rounded-lg border border-amz-border bg-white">
                        <div class="flex-1">
                            <p class="text-[13px] font-bold text-amz-text">Current Image</p>
                            <p class="text-[12px] text-amz-text-sec mt-0.5">Upload a new image to replace this one</p>
                            <p class="text-[11px] text-amz-text-weak mt-1 italic truncate">{{ $category->image }}</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-amz-page/40 border border-amz-border rounded-lg p-4 text-center">
                    <i data-lucide="image-off" class="w-6 h-6 text-amz-text-weak mx-auto mb-1"></i>
                    <p class="text-[13px] text-amz-text-sec">No image uploaded yet</p>
                </div>
                @endif

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-[13px] font-bold text-amz-text mb-1.5">
                        Category Name <span class="text-amz-deal">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $category->name) }}"
                           class="amz-input w-full h-10 px-3 text-[13px] rounded-md"
                           required>
                    @error('name')
                    <p class="mt-1 text-[12px] text-amz-deal">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description Field -->
                <div>
                    <label for="description" class="block text-[13px] font-bold text-amz-text mb-1.5">
                        Description
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="amz-input w-full px-3 py-2 text-[13px] rounded-md resize-none">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                    <p class="mt-1 text-[12px] text-amz-deal">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Image Upload -->
                <div>
                    <label for="image" class="block text-[13px] font-bold text-amz-text mb-1.5">
                        Upload New Image <span class="text-[11px] font-normal text-amz-text-weak">(optional)</span>
                    </label>

                    <div class="relative border-2 border-dashed border-amz-border rounded-lg p-6 hover:border-amz-orange transition-colors">
                        <div id="newImagePreviewContainer" class="hidden mb-3">
                            <img id="newImagePreview" src="#" alt="New Preview" class="max-h-40 mx-auto rounded-lg border border-amz-border">
                            <p class="text-center text-[12px] text-amz-green font-medium mt-2 flex items-center justify-center gap-1">
                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                New image selected (will replace current)
                            </p>
                        </div>

                        <div id="newUploadArea" class="text-center">
                            <i data-lucide="upload-cloud" class="w-10 h-10 text-amz-text-weak mx-auto"></i>
                            <p class="mt-2 text-[13px] text-amz-text-sec">
                                <span class="font-bold text-amz-orange">Click to upload</span> a new image
                            </p>
                            <p class="text-[11px] text-amz-text-weak mt-0.5">PNG, JPG up to 2MB</p>
                        </div>

                        <input type="file"
                               name="image"
                               id="image"
                               accept="image/*"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                               onchange="previewNewImage(this)">
                    </div>
                    @error('image')
                    <p class="mt-1 text-[12px] text-amz-deal">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-2">
                    <a href="{{ route('categories.index') }}"
                       class="flex-1 text-center py-2.5 rounded-md text-[13px] font-bold border border-amz-border text-amz-text hover:bg-amz-page transition-colors">
                        Cancel
                    </a>
                   <button type="submit"
    class="flex-1 py-2.5 text-[13px] font-bold text-white bg-blue-600 hover:bg-blue-700 rounded flex items-center justify-center gap-2 shadow-sm">
    <i data-lucide="check" class="w-4 h-4"></i>
    Update Category
</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewNewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('newImagePreview').src = e.target.result;
                    document.getElementById('newImagePreviewContainer').classList.remove('hidden');
                    document.getElementById('newUploadArea').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        lucide.createIcons();
    </script>

@endsection
