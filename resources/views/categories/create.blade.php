<!DOCTYPE html>
<html>
<head>
    <title>Create Category</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-xl mx-auto mt-10 bg-white p-8 rounded-xl shadow">

    <h2 class="text-2xl font-bold mb-6 text-center">Create Category</h2>

    <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">

        @csrf

        <!-- Name -->
        <input type="text" name="name"
               placeholder="Category Name"
               class="w-full border p-2 rounded mb-3">

        <!-- Description -->
        <textarea name="description"
                  placeholder="Description"
                  class="w-full border p-2 rounded mb-3"></textarea>

        <!-- Image -->
        <input type="file" name="image"
               class="w-full border p-2 rounded mb-3">

        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Save
        </button>

    </form>

</div>

</body>
</html>
