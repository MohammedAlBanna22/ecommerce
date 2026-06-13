<!DOCTYPE html>
<html>
<head>
    <title>Create Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-xl shadow">

    <h2 class="text-2xl font-bold mb-6 text-center">➕ Create Product</h2>
@if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- Name --}}
        <div>
            <label class="block font-medium">Product Name</label>
            <input type="text" name="name"
                   class="w-full border p-2 rounded focus:outline-none focus:ring"
                   placeholder="Enter product name">
        </div>

        {{-- Price --}}
        <div>
            <label class="block font-medium">Price</label>
            <input type="number" name="price"
                   class="w-full border p-2 rounded focus:outline-none focus:ring"
                   placeholder="Enter price">
        </div>

        {{-- Category --}}
        <div>
            <label class="block font-medium">Category</label>
            <select name="category_id"
                    class="w-full border p-2 rounded focus:outline-none focus:ring">
                <option value="">Select Category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Quantity --}}
        <div>
            <label class="block font-medium">Quantity</label>
            <input type="number" name="quantity"
                   class="w-full border p-2 rounded focus:outline-none focus:ring"
                   placeholder="Enter quantity">
        </div>

        {{-- Description --}}
        <div>
            <label class="block font-medium">Description</label>
            <textarea name="description"
                      class="w-full border p-2 rounded focus:outline-none focus:ring"
                      rows="3"
                      placeholder="Enter description"></textarea>
        </div>

        {{-- Image --}}
        <div>
            <label class="block font-medium">Image</label>
            <input type="file" name="image"
                   class="w-full border p-2 rounded">
        </div>

        {{-- Status --}}
        <div>
            <label class="block font-medium">Status</label>
            <select name="status"
                    class="w-full border p-2 rounded">
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Save Product
        </button>

    </form>
</div>

</body>
</html>
