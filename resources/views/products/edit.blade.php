
<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-xl shadow">

    <h2 class="text-2xl font-bold mb-6 text-center">✏️ Edit Product</h2>

    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" class="space-y-4">

        @csrf
        @method('PUT')

        {{-- Name --}}
        <div>
            <label class="block font-medium">Product Name</label>
            <input type="text" name="name"
                   value="{{ $product->name }}"
                   class="w-full border p-2 rounded focus:ring">
        </div>

        {{-- Price --}}
        <div>
            <label class="block font-medium">Price</label>
            <input type="number" name="price"
                   value="{{ $product->price }}"
                   class="w-full border p-2 rounded focus:ring">
        </div>

        {{-- Category --}}
        <div>
            <label class="block font-medium">Category</label>
            <select name="category_id" class="w-full border p-2 rounded">
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Quantity --}}
        <div>
            <label class="block font-medium">Quantity</label>
            <input type="number" name="quantity"
                   value="{{ $product->quantity }}"
                   class="w-full border p-2 rounded">
        </div>

        {{-- Description --}}
        <div>
            <label class="block font-medium">Description</label>
            <textarea name="description"
                      class="w-full border p-2 rounded"
                      rows="3">{{ $product->description }}</textarea>
        </div>

        {{-- Image --}}
        <div>
            <label class="block font-medium">Image</label>

            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}"
                     class="h-24 mb-2 rounded">
            @endif

            <input type="file" name="image" class="w-full border p-2 rounded">
        </div>

        {{-- Status --}}
        <div>
            <label class="block font-medium">Status</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="available" {{ $product->status == 'available' ? 'selected' : '' }}>
                    Available
                </option>
                <option value="unavailable" {{ $product->status == 'unavailable' ? 'selected' : '' }}>
                    Unavailable
                </option>
            </select>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600 transition">
            Update Product
        </button>

    </form>

</div>

</body>
</html>
