<!DOCTYPE html>
<html>
<head>
    <title>Create Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-5xl mx-auto mt-10 bg-white rounded-2xl shadow p-8">

    <h2 class="text-3xl font-bold mb-8 text-center">➕ Create Product</h2>

    {{-- ERRORS --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-3 rounded mb-5">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">

        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- LEFT SIDE --}}
            <div class="space-y-6">

                {{-- PRODUCT INFO --}}
                <div class="bg-gray-50 p-4 rounded-xl">
                    <h3 class="font-bold mb-3">Product Info</h3>

                    <input type="text" name="name"
                           class="w-full border p-2 rounded mb-3"
                           placeholder="Product Name">

                    <textarea name="description"
                              class="w-full border p-2 rounded mb-3"
                              rows="4"
                              placeholder="Product Description"></textarea>

                    <select name="category_id"
                            class="w-full border p-2 rounded mb-3">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <select name="status"
                            class="w-full border p-2 rounded">
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>

                {{-- PRICING --}}
                <div class="bg-gray-50 p-4 rounded-xl">
                    <h3 class="font-bold mb-3">Pricing</h3>

                    <input type="number" step="0.01" name="price"
                           class="w-full border p-2 rounded mb-3"
                           placeholder="Price">

                    <input type="number" step="0.01" name="discount_price"
                           class="w-full border p-2 rounded"
                           placeholder="Discount Price">
                </div>

            </div>

            {{-- RIGHT SIDE --}}
            <div class="space-y-6">

                {{-- INVENTORY --}}
                <div class="bg-gray-50 p-4 rounded-xl">
                    <h3 class="font-bold mb-3">Inventory</h3>

                    <input type="number" name="quantity"
                           class="w-full border p-2 rounded mb-3"
                           placeholder="Quantity">

                    <input type="number"
                           class="w-full border p-2 rounded bg-gray-100"
                           value="0"
                           disabled>

                    <p class="text-xs text-gray-500 mt-2">
                        Reserved quantity is managed automatically by system
                    </p>
                </div>

                {{-- MEDIA --}}
                <div class="bg-gray-50 p-4 rounded-xl">
                    <h3 class="font-bold mb-3">Media</h3>

                    <input type="file"
                           name="images[]"
                           multiple
                           class="w-full border p-2 rounded">

                    <p class="text-xs text-gray-500 mt-2">
                        First image will be the main product image
                    </p>

                    <div id="preview" class="grid grid-cols-4 gap-2 mt-3"></div>
                </div>

            </div>

        </div>

        {{-- SUBMIT --}}
        <div class="mt-8">
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition font-bold">
                Create Product
            </button>
        </div>

    </form>
</div>

{{-- IMAGE PREVIEW --}}
<script>
document.querySelector('input[name="images[]"]').addEventListener('change', function (event) {

    let preview = document.getElementById('preview');
    preview.innerHTML = '';

    [...event.target.files].forEach(file => {

        let reader = new FileReader();

        reader.onload = function (e) {
            let img = document.createElement('img');
            img.src = e.target.result;
            img.className = "w-full h-20 object-cover rounded border";
            preview.appendChild(img);
        }

        reader.readAsDataURL(file);
    });

});
</script>

</body>
</html>
