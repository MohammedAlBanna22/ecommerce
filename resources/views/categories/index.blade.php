<!DOCTYPE html>
<html>
<head>
    <title>Categories</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">

<div class="max-w-6xl mx-auto py-10">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Categories</h1>

        <a href="{{ route('categories.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Add Category
        </a>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @foreach($categories as $category)

        <div class="bg-white rounded-2xl shadow hover:shadow-xl transition overflow-hidden">

            <!-- Image -->
            <div class="h-40 bg-gray-100">
                @if($category->image)
                    <img src="{{ asset('storage/'.$category->image) }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="h-full flex items-center justify-center text-gray-400">
                        No Image
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="p-4">

                <h2 class="text-lg font-bold">{{ $category->name }}</h2>

                <p class="text-gray-500 text-sm">
                    {{ $category->description }}
                </p>

                <!-- Buttons -->
                <div class="flex gap-2 mt-4">

                    <a href="{{ route('categories.edit', $category->id) }}"
                       class="flex-1 text-center bg-yellow-500 text-white py-2 rounded-lg hover:bg-yellow-600">
                        Edit
                    </a>

                    <form method="POST"
                          action="{{ route('categories.destroy', $category->id) }}"
                          class="flex-1">

                        @csrf
                        @method('DELETE')

                        <button onclick="return confirm('Delete category?')"
                                class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600">
                            Delete
                        </button>

                    </form>

                </div>

            </div>
        </div>

        @endforeach

    </div>
</div>

</body>
</html>
