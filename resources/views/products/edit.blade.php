<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-6xl mx-auto mt-10 bg-white rounded-2xl shadow p-8">

    <h2 class="text-3xl font-bold mb-8 text-center">✏️ Edit Product</h2>

    <form method="POST"
          action="{{ route('products.update', $product->id) }}"
          enctype="multipart/form-data"
          class="grid grid-cols-1 md:grid-cols-2 gap-8">

        @csrf
        @method('PUT')

        {{-- LEFT SIDE --}}
        <div class="space-y-5">

            {{-- NAME --}}
            <div>
                <label class="block font-medium">Product Name</label>
                <input type="text" name="name"
                       value="{{ $product->name }}"
                       class="w-full border p-2 rounded-lg focus:ring">
            </div>

            {{-- PRICE --}}
            <div>
                <label class="block font-medium">Price</label>
                <input type="number" name="price"
                       value="{{ $product->price }}"
                       class="w-full border p-2 rounded-lg focus:ring">
            </div>

            {{-- CATEGORY --}}
            <div>
                <label class="block font-medium">Category</label>
                <select name="category_id" class="w-full border p-2 rounded-lg">

                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- QUANTITY --}}
            <div>
                <label class="block font-medium">Quantity</label>
                <input type="number" name="quantity"
                       value="{{ $product->quantity }}"
                       class="w-full border p-2 rounded-lg">
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="block font-medium">Description</label>
                <textarea name="description"
                          rows="4"
                          class="w-full border p-2 rounded-lg">{{ $product->description }}</textarea>
            </div>

            {{-- STATUS --}}
            <div>
                <label class="block font-medium">Status</label>
                <select name="status" class="w-full border p-2 rounded-lg">

                    <option value="available" {{ $product->status == 'available' ? 'selected' : '' }}>
                        Available
                    </option>

                    <option value="unavailable" {{ $product->status == 'unavailable' ? 'selected' : '' }}>
                        Unavailable
                    </option>

                </select>
            </div>

            {{-- ADD NEW IMAGES --}}
            <div>
                <label class="block font-medium">Add Images</label>

                <input type="file"
                       name="images[]"
                       multiple
                       class="w-full border p-2 rounded-lg">

                <p class="text-xs text-gray-500 mt-1">
                    You can upload multiple images
                </p>
            </div>

            {{-- SUBMIT --}}
            <button class="w-full bg-yellow-500 text-white py-3 rounded-xl hover:bg-yellow-600 font-bold">
                Update Product
            </button>

        </div>

        {{-- RIGHT SIDE (MEDIA) --}}
        <div class="space-y-4">

            <h3 class="text-xl font-bold">Product Gallery</h3>

            {{-- MAIN IMAGE --}}
            <div class="h-72 bg-gray-100 rounded-xl overflow-hidden border">

                @if($product->mainImage)
                    <img id="mainImage"
                         src="{{ asset('storage/'.$product->mainImage->path) }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="h-full flex items-center justify-center text-gray-500">
                        No Image
                    </div>
                @endif

            </div>

            {{-- GALLERY --}}
            <div class="grid grid-cols-4 gap-3">

                @foreach($product->media as $image)

                    <div class="relative group">

                        {{-- IMAGE --}}
                        <img src="{{ asset('storage/'.$image->path) }}"
                             class="w-full h-20 object-cover rounded border cursor-pointer
                             {{ $image->is_primary ? 'ring-2 ring-blue-500' : '' }}"
                             onclick="setPreview(this)">

                        {{-- MAIN BADGE --}}
                        @if($image->is_primary)
                            <span class="absolute top-1 left-1 bg-blue-600 text-white text-[10px] px-1 rounded">
                                Main
                            </span>
                        @endif

                        {{-- HOVER ACTIONS --}}
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition rounded">

                            <div class="hidden group-hover:flex justify-center items-center gap-2 h-full">

                                {{-- SET MAIN --}}
                                <button type="button"
                                        onclick="setMain({{ $image->id }})"
                                        class="bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                    Main
                                </button>

                                {{-- DELETE --}}
                                <button type="button"
                                        onclick="deleteImage({{ $image->id }}, this)"
                                        class="bg-red-500 text-white text-xs px-2 py-1 rounded">
                                    Delete
                                </button>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </form>
</div>

{{-- JS --}}
<script>

function setPreview(el) {
    document.getElementById('mainImage').src = el.src;
}

function setMain(id) {

    fetch(`/media/${id}/set-main`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(() => location.reload());
}

function deleteImage(id, el) {

    if (!confirm('Delete this image?')) return;

    fetch(`/media/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            el.closest('.relative').remove();
        }
    });
}

</script>

</body>
</html>
