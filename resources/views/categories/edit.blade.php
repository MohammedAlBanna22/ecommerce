<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-xl shadow">

    <h2 class="text-2xl font-bold mb-6 text-center">Edit Category</h2>

    <form method="POST"
          action="{{ route('categories.update', $category->id) }}"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <!-- Name -->
        <input type="text" name="name"
               value="{{ $category->name }}"
               class="w-full border p-2 rounded mb-3">

        <!-- Description -->
        <textarea name="description"
                  class="w-full border p-2 rounded mb-3">{{ $category->description }}</textarea>

        <!-- Image -->
        @if($category->image)
            <img src="{{ asset('storage/'.$category->image) }}"
                 class="h-24 mb-2 rounded">
        @endif

        <input type="file" name="image"
               class="w-full border p-2 rounded mb-3">

        <button class="w-full bg-yellow-500 text-white py-2 rounded hover:bg-yellow-600">
            Update
        </button>

    </form>

</div>

</body>
</html>
