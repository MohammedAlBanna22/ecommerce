<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        //
         $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        Category::create($data);

        return to_route('categories.index')
        ->with('success', 'Category created successfully');
    }


        private function uploadImage($file, $oldImage = null)
    {
        //should make php artisan storage:link
        // حذف الصورة القديمة إذا موجودة
        if ($oldImage) {
        Storage::disk('public')->delete($oldImage);
        }
        return $file->store('products', 'public');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
        $data = $request->validated();

        if ($request->hasFile('image')) {
        $data['image'] = $this->uploadImage($request->file('image'), $category->image);
        }

        $category->update($data);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
         $category->delete();
        return redirect()->route('categories.index');
    }
}