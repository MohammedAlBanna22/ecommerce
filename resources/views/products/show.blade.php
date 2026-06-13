<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-5xl mx-auto mt-10 bg-white rounded-2xl shadow-lg overflow-hidden">

    <div class="grid grid-cols-1 md:grid-cols-2">

        <!-- Image -->
        <div class="bg-gray-200 h-96 flex items-center justify-center">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}"
                     class="w-full h-full object-cover">
            @else
                <span class="text-gray-500">No Image</span>
            @endif
        </div>

        <!-- Details -->
        <div class="p-6 space-y-4">

            <h1 class="text-3xl font-bold text-gray-800">
                {{ $product->name }}
            </h1>

            <p class="text-gray-500">
                Category:
                <span class="font-semibold">
                    {{ $product->category->name ?? 'No Category' }}
                </span>
            </p>

            <div class="flex items-center gap-3">
                <span class="text-2xl font-bold text-blue-600">
                    ${{ $product->price }}
                </span>

                @if($product->discount_price)
                    <span class="line-through text-gray-400">
                        ${{ $product->discount_price }}
                    </span>
                @endif
            </div>

            <p class="text-gray-600">
                {{ $product->description ?? 'No description available.' }}
            </p>

            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-sm
                    {{ $product->status == 'available' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    {{ $product->status }}
                </span>
            </div>

            <p class="text-sm text-gray-500">
                Quantity: {{ $product->quantity }}
            </p>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">

                <a href="{{ route('products.edit', $product->id) }}"
                   class="flex-1 text-center bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-600 transition">
                    Edit
                </a>

                <form method="POST" action="{{ route('products.destroy', $product->id) }}" class="flex-1">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            onclick="return confirm('Delete this product?')"
                            class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition">
                        Delete
                    </button>
                </form>

            </div>

            <a href="{{ route('products.index') }}"
               class="block text-center text-gray-500 mt-4 hover:text-black">
                ← Back to Products
            </a>

        </div>
    </div>
</div>

</body>
</html>
