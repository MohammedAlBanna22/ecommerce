<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $cart = Cart::with('items.product')
        ->where('user_id', auth()->id())
        ->first();


        $total = 0;

        if ($cart) {

            foreach ($cart->items as $item) {
                $total += $item->price * $item->quantity;
             }
         }

    return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $item)
    {
        //
         $request->validate([
        'quantity'=>'required|integer|min:1'
        ]);


        $item->update([

            'quantity'=>$request->quantity

        ]);


        return back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
        $cart->delete();


        return back();
    }
    public function add(Product $product)
    {

        $cart = Cart::firstOrCreate([
        'user_id'=>auth()->id()
        ]);


        $item = $cart->items()
        ->where('product_id',$product->id)
        ->first();


        if($item){

            $item->increment('quantity');

        }else{


            $cart->items()->create([

                'product_id'=>$product->id,

                'quantity'=>1,

                // نحفظ السعر وقت الإضافة
                'price'=>$product->price

            ]);

        }


        return back();

    }
}
