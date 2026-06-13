<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop Landing</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

<!-- Navbar -->
<header class="bg-white shadow">
    <div class="max-w-6xl mx-auto flex justify-between items-center p-4">
        <h1 class="text-xl font-bold">MyShop</h1>

        <nav class="space-x-4">
            <a href="#" class="text-gray-600 hover:text-black">Home</a>
            <a href="#" class="text-gray-600 hover:text-black">Products</a>
            <a href="#" class="text-gray-600 hover:text-black">Contact</a>
        </nav>
    </div>
</header>

<!-- Hero Section -->
<section class="text-center py-20 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
    <h2 class="text-4xl font-bold mb-4">Best Products for You</h2>
    <p class="text-lg mb-6">High quality products at the best prices</p>
    <a href="#products" class="bg-white text-blue-600 px-6 py-3 rounded-full font-bold">
        Shop Now
    </a>
</section>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Products</h2>

    <a href="{{ route('products.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
        + Add Product
    </a>
</div>
<!-- Products Section -->

<section id="products" class="max-w-6xl mx-auto py-12 px-4">
    <h2 class="text-2xl font-bold mb-6">Latest Products</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
@foreach($products as $product)

<div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden">

    <!-- Image (Clickable) -->
    <a href="{{ route('products.show', $product->id) }}">
        <div class="h-48 bg-gray-100">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}"
                     class="w-full h-full object-cover hover:scale-105 transition duration-300">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    No Image
                </div>
            @endif
        </div>
    </a>

    <!-- Content -->
    <div class="p-4">

        <!-- Name clickable -->
        <a href="{{ route('products.show', $product->id) }}">
            <h3 class="text-lg font-bold hover:text-blue-600 transition">
                {{ $product->name }}
            </h3>
        </a>

        <p class="text-gray-500 text-sm">
            {{ $product->category->name ?? 'No Category' }}
        </p>

        <div class="flex justify-between items-center mt-2">
            <span class="text-blue-600 font-bold">
                ${{ $product->price }}
            </span>

            <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-600">
                {{ $product->status  }}
            </span>
        </div>

        <!-- Buttons -->
        <div class="flex gap-2 mt-4">

            <!-- Edit -->
            <a href="{{ route('products.edit', $product->id) }}"
               class="flex-1 text-center bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-600 transition">
                Edit
            </a>

            <!-- Delete -->
            <form method="POST"
                  action="{{ route('products.destroy', $product->id) }}"
                  class="flex-1">

                @csrf
                @method('DELETE')

                <button type="submit"
                        onclick="return confirm('Are you sure?')"
                        class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">
                    Delete
                </button>

            </form>

        </div>

    </div>
</div>

@endforeach

    </div>
</section>

<!-- Footer -->
<footer class="bg-white mt-12 py-6 text-center text-gray-500">
    © 2026 MyShop. All rights reserved.
</footer>

</body>
</html>
