<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReleaseExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    //protected $signature = 'app:release-expired-carts';
    protected $signature = 'cart:release';
    //php artisan cart:release to run the command

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //

        $carts = Cart::with('items')
            ->where('expires_at','<',now())
            ->get();


        foreach($carts as $cart){


            DB::transaction(function() use($cart){


                foreach($cart->items as $item){


                    $product = Product::lockForUpdate()
                    ->find($item->product_id);


                    $product->decrement(
                        'reserved_quantity',
                        $item->quantity
                    );


                }


                $cart->items()->delete();


                $cart->update([
                    //'expires_at'=>now()->addMinutes(30)
                    'expires_at'=>null,
                ]);


            });

        }


    }
}
