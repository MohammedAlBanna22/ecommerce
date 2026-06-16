<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\NewOrderNotification;

class CheckoutController extends Controller
{
    //
    public function index()
    {

    $cart = Cart::with('items.product')
        ->where('user_id',auth()->id())
        ->first();


    return view('checkout.index',compact('cart'));

    }

    public function store(Request $request)
    {
        //  $validated = $request->validate([
        //     'payment_method' => 'required|in:cash,credit_card,paypal', // Adjust based on your methods
        // ]);

        $cart = Cart::with('items.product')
        ->where('user_id', auth()->id())
        ->first();


        if (!$cart || $cart->items->isEmpty()) {

            return back()
                ->with('error', 'Your cart is empty');

        }



        try {


            DB::beginTransaction();



            $total = "0.00";


            // Check stock + calculate total
            foreach($cart->items as $item){


                $product = $item->product;


                if($product->quantity < $item->quantity){

                    throw new \Exception(
                        "Product {$product->name} is out of stock"
                    );

                }


                $total = bcadd(
                    $total,
                    bcmul(
                        $item->price,
                        $item->quantity,
                        2
                    ),
                    2
                );


            }




            // Create Order

            $order = Order::create([

                'user_id'=>auth()->id(),

                'total_price'=>$total,

                'payment_method'=>'cash',

                'order_status'=>'pending'

            ]);
            $admins = User::where('role','admin')->get();

            foreach($admins as $admin){
                $admin->notify(new NewOrderNotification($order));
            }





            foreach($cart->items as $item){



                OrderItem::create([

                    'order_id'=>$order->id,

                    'product_id'=>$item->product_id,

                    'quantity'=>$item->quantity,

                    'price'=>$item->price,

                    'total'=>bcmul(
                        $item->price,
                        $item->quantity,
                        2
                    ),

                ]);




                // decrease stock safely

                $updated = Product::where('id',$item->product_id)
                    ->where('quantity','>=',$item->quantity)
                    ->decrement(
                        'quantity',
                        $item->quantity
                    );



                if(!$updated){

                    throw new \Exception(
                        'Stock changed. Please try again'
                    );

                }


            }





             // clear cart

            $cart->items()->delete();



             DB::commit();



            return redirect()

                ->route('orders.show',$order)

                ->with(
                'success',
                'Order created successfully'
             );





            } catch(\Exception $e){


                DB::rollBack();



                 return back()

                ->with(
                'error',
                $e->getMessage()
                );


        }

    }
}
