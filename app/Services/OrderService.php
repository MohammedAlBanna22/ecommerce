<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;


class OrderService
{
    public function createOrder($user, $cart, $request)
    {
        return DB::transaction(function () use ($user, $cart, $request) {

            /*
            |--------------------------------------------------------------------------
            | LOAD CART ITEMS WITH LOCK
            |--------------------------------------------------------------------------
            */
            $currentCart = Cart::where('id', $cart->id)
            ->where('user_id', $user->id)
            ->with('items.product')
            ->lockForUpdate()
            ->first();

            if (!$currentCart || $currentCart->items->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            $subtotal = "0.00";
            $discount = 0;
            $coupon = null;

            /*
            |--------------------------------------------------------------------------
            | 1. STOCK VALIDATION + SUBTOTAL
                |--------------------------------------------------------------------------
            */
            foreach ($currentCart->items as $item) {

                $product = Product::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                $available = $product->quantity - $product->reserved_quantity;

                if ($available < $item->quantity) {
                    throw new \Exception("Product {$product->name} is out of stock");
                }

                $subtotal = bcadd(
                    $subtotal,
                    bcmul($item->price, $item->quantity, 2),
                    2
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 2. COUPON SYSTEM
            |--------------------------------------------------------------------------
            */
            if ($request->coupon_code) {

                $coupon = Coupon::where('code', $request->coupon_code)
                    ->where('status', true)
                    ->first();

                if (!$coupon) {
                     throw new \Exception('Invalid coupon');
                }

                if ($coupon->expires_at && $coupon->expires_at < now()) {
                    throw new \Exception('Coupon expired');
                }

                if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                    throw new \Exception('Coupon limit reached');
                }

                $discount = $coupon->type === 'percent'
                    ? ($subtotal * $coupon->value) / 100
                    : $coupon->value;
            }

            /*
            |--------------------------------------------------------------------------
            | 3. FINAL TOTAL
            |--------------------------------------------------------------------------
            */
            $total = bcsub($subtotal, $discount, 2);

            /*
            |   --------------------------------------------------------------------------
            |   4. CREATE ORDER
            |--------------------------------------------------------------------------
            */
             $order = Order::create([
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_price' => $total,
                'payment_method' => 'cash',
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'coupon_code' => $coupon?->code,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 5. ORDER ITEMS + STOCK FINALIZATION
            |--------------------------------------------------------------------------
            */
            foreach ($currentCart->items as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => bcmul($item->price, $item->quantity, 2),
                ]);

                $product = Product::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                /*
                |--------------------------------------------------------------------------
                | RESERVED → SOLD TRANSITION
                |--------------------------------------------------------------------------
                */

                // release reserved stock&&finalize stock (sold)

                //$product->confirmSale($item->quantity);
                $product->sell(
                    $item->quantity,
                    "Order #".$order->id
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 6. UPDATE COUPON USAGE
            |--------------------------------------------------------------------------
            */
            if ($coupon) {
                $coupon->increment('used_count');
            }

            /*
            |--------------------------------------------------------------------------
            | 7. CLEAR CART
            |--------------------------------------------------------------------------
            */
            $currentCart->items()->delete();

            return $order;
        });
    }
}
