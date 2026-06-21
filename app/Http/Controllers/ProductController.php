<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
       $products = Product::with([
                    'category:id,name',
                    'mainImage:id,path,mediable_id,mediable_type'
                    ])
                    ->latest()
                    ->paginate(12);

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        return DB::transaction(function () use ($request) {


            $data = $request->validated();


            // create product
            $product = Product::create($data);



            if($request->hasFile('images'))
            {

                foreach($request->file('images') as $index=>$file)
                {


                    $path = $file->store(
                        'media/products',
                        'public'
                    );


                    $product->media()->create([

                        'path'=>$path,

                        'type'=>'image',

                        // اول صورة فقط primary
                        'is_primary'=>$index === 0,

                        'sort_order'=>$index

                    ]);


                }

            }



            //return $product;
                   return redirect()
            ->route('products.index')
            ->with('success','Product created successfully');

        });
        return redirect()
            ->route('products.index')
            ->with('success','Product created successfully');


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
    public function show(Product $product)
    {
        //
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
         $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
         $data = $request->validated();

        if ($request->hasFile('image')) {
        $data['image'] = $this->uploadImage($request->file('image'), $product->image);
        }

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // later if requriment make soft delete we can use it
        $product->delete();
        return redirect()->route('products.index');
    }
}
