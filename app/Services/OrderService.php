<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * Create order with proper address handling
     */
    public function createOrder($user, $cart, $request, ?Address $address = null)
    {
        // ✅ أضفنا $address إلى use() !
        return DB::transaction(function () use ($user, $cart, $request, $address) {

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
                throw new \Exception('Cart is empty or invalid');
            }

            $subtotal = "0.00";
            $discount = 0;
            $coupon = null;

            /*
            |--------------------------------------------------------------------------
            | 1. STOCK VALIDATION + SUBTOTAL CALCULATION
            |--------------------------------------------------------------------------
            */
            foreach ($currentCart->items as $item) {
                $product = Product::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw new \Exception("Product #{$item->product_id} not found");
                }

                $available = $product->quantity - $product->reserved_quantity;

                if ($available < $item->quantity) {
                    throw new \Exception(
                        "Product \"{$product->name}\" only has {$available} items available. " .
                        "You requested {$item->quantity}."
                    );
                }

                $subtotal = bcadd(
                    $subtotal,
                    bcmul($item->price, $item->quantity, 2),
                    2
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 2. COUPON SYSTEM (Optional)
            |--------------------------------------------------------------------------
            */
            if (!empty($request->coupon_code)) {
                $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))
                    ->where('status', true)
                    ->first();

                if (!$coupon) {
                    throw new \Exception('Invalid coupon code. Please check and try again.');
                }

                if ($coupon->expires_at && $coupon->expires_at < now()) {
                    throw new \Exception('This coupon has expired');
                }

                if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                    throw new \Exception('This coupon has reached its usage limit');
                }

                $discount = $coupon->type === 'percent'
                    ? ($subtotal * $coupon->value) / 100
                    : min($coupon->value, $subtotal); // لا يتجاوز الإجمالي
            }

            /*
            |--------------------------------------------------------------------------
            | 3. FINAL TOTAL CALCULATION
            |--------------------------------------------------------------------------
            */
            $total = bcsub($subtotal, $discount, 2);

            // ✅ التحقق من العنوان
            if (!$address) {
                throw new \Exception('No shipping address provided. Please select or add an address.');
            }

            /*
            |--------------------------------------------------------------------------
            | 4. CREATE ORDER RECORD
            |--------------------------------------------------------------------------
            */
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address->id,  // ✅ الآن $address متوفر!
                'total_price' => $total,
                'payment_method' => $request->payment_method ?? 'cod',
                'payment_status' => 'unpaid',
                'order_status' => 'processing',
                'coupon_code' => $coupon?->code,
                'discount' => $discount,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 5. CREATE ORDER ITEMS + UPDATE STOCK
            |--------------------------------------------------------------------------
            */
            foreach ($currentCart->items as $item) {
                // إنشاء عنصر الطلب
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => bcmul($item->price, $item->quantity, 2),
                ]);

                // تحديث المخزون
                $product = Product::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if ($product) {
                    $product->sell(
                        $item->quantity,
                        "Order #{$order->id} - Item sold"
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 6. UPDATE COUPON USAGE (if used)
            |--------------------------------------------------------------------------
            */
            if ($coupon) {
                $coupon->increment('used_count');
            }

            /*
            |--------------------------------------------------------------------------
            | 7. CLEAR CART ITEMS
            |--------------------------------------------------------------------------
            */
            $currentCart->items()->delete();

            return $order;
        });
    }
}