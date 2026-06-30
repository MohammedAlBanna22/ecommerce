<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Cart;
use App\Models\CartItem;
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
    public function index(Request $request)
    {
        //
    //    $products = Product::with([
    //                 'category:id,name',
    //                 'mainImage:id,path,mediable_id,mediable_type'
    //                 ])
    //                 ->latest()
    //                 ->paginate(12);
    //                  $cartCount = CartItem::getTotalQuantity() ?? 0;

    //     return view('products.index', compact('products','cartCount'));

    $products = Product::with(['category', 'mainImage'])
        ->when($request->filled('search'), fn($q) =>
            $q->where('name', 'LIKE', "%{$request->search}%")
              ->orWhere('description', 'LIKE', "%{$request->search}%")
              ->orWhere('sku', 'LIKE', "%{$request->search}%")
        )
       // ─── Department ───
        ->when($request->filled('category_id'), fn($q) =>
            $q->where('category_id', $request->category_id)
        )
        // ─── Price Min ───
        ->when($request->filled('price_min'), fn($q) =>
            $q->where('price', '>=', $request->price_min)
        )
        // ─── Price Max ───
        ->when($request->filled('price_max'), fn($q) =>
            $q->where('price', '<=', $request->price_max)
        )
        // ─── Availability ───
        ->when($request->filled('availability'), function($q) use ($request) {
            if ($request->availability === 'in_stock') {
                $q->whereColumn('quantity', '>', 'reserved_quantity');
            } elseif ($request->availability === 'out_of_stock') {
                $q->whereColumn('quantity', '<=', 'reserved_quantity');
            }
        })
        ->when($request->filled('sort'), function($q) use ($request) {
    match($request->sort) {
        'price-asc' => $q->orderBy('price'),
        'price-desc' => $q->orderByDesc('price'),
        'name-asc' => $q->orderBy('name'),
        'name-desc' => $q->orderByDesc('name'),
        'newest' => $q->latest(),
        default => $q->latest(),
    };
}, fn($q) => $q->latest())
        ->latest()
        ->paginate(20)
        ->appends($request->query()); // ← مهم: يحافظ على الفلاتر بالـ pagination


    $categories = Category::withCount('products')->get();

    $cartCount = CartItem::getTotalQuantity() ?? 0;

    return view('products.index', compact('products', 'categories', 'cartCount'));


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
                  $primaryIndex = (int) $request->input('primary_image_index', 0);
                foreach($request->file('images') as $index=>$file)
                {


                    $path = $file->store(
                        'media/products',
                        'public'
                    );


                    $product->media()->create([

                        'path'=>$path,

                        'type'=>'image',

                       
                        'is_primary' => ($index === $primaryIndex), // الصورة المختارة

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
        $product->load([
        'category',
        'media' => fn($q) => $q->orderBy('sort_order'),
    ]);


    // منتجات مشابهة
    $related = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('status', 'available')
        ->with('mainImage')
        ->inRandomOrder()
        ->take(5)
        ->get();
        $cartCount = CartItem::getTotalQuantity() ?? 0;
        return view('products.show', compact('product','cartCount','related'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //

        $product->load([
        'media',

        ]);

        $categories= Category::all();

        return view(
        'products.edit',
        compact('product','categories')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
         $data = $request->validated();

        // if ($request->hasFile('image')) {
        // $data['image'] = $this->uploadImage($request->file('image'), $product->image);
        // }
         unset($data['images']);
        $product->update($data);

        if($request->hasFile('images'))
        {
            $lastOrder = $product->media()
                ->max('sort_order') ?? 0;


            foreach($request->file('images') as $index=>$file)
            {


                $path = $file->store(
                    'media/products',
                    'public'
                );


                $product->media()->create([

                    'path'=>$path,

                    'type'=>'image',

                    'is_primary'=>false,

                    'sort_order'=>$lastOrder + $index + 1

                ]);

            }

        }

        return redirect()
           ->route('products.edit', $product->id)
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