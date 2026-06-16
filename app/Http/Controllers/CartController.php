<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\Product;

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


        //$total = $cart ? $cart->getTotalAttribute() : 0;
         $total = $cart?->total ?? 0;



        return view('cart.index', compact('cart', 'total'));
    }


        public function add(Product $product)
    {

        $cart = Cart::firstOrCreate([
        'user_id'=>auth()->id()
        ]);


        $item = $cart->items()
        ->where('product_id',$product->id)
        ->first();


        if ($product->quantity <= 0) {
         return back()->with('error', 'Product out of stock');
        }

        if($item){

             // الكمية الحالية + 1 أكبر من المخزون
        if($item->quantity + 1 > $product->quantity){

            return back()->with(
                'error',
                'Only '.$product->quantity.' items available'
            );
        }
        $item->increment('quantity');

        }else{
              if($product->quantity < 1){

                return back()->with(
                'error',
                'Product is out of stock'
                );
            }


            $cart->items()->create([

                'product_id'=>$product->id,

                'quantity'=>1,

                // نحفظ السعر وقت الإضافة
                'price'=>$product->price

            ]);

        }




        return back();

    }



    public function update(Request $request, CartItem $item)
    {
        //
         $request->validate([
        'quantity'=>'required|integer|min:1'
        ]);

        abort_unless($item->cart->user_id === auth()->id(), 403);
        $item->update([

            'quantity'=>$request->quantity

        ]);


        return back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $item)
    {
        //
        abort_unless($item->cart->user_id === auth()->id(), 403);
        $item->delete();


        return back();
    }

}