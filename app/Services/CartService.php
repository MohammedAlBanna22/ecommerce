<?php
namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartService
{
public function add(Product $product, $user)
{
    return DB::transaction(function () use ($product, $user) {

    $product = Product::where('id', $product->id)
    ->lockForUpdate()
    ->first();

    $cart = Cart::firstOrCreate(
    [
        'user_id'=>$user->id
    ],
    [
        'expires_at'=>now()->addMinutes(30)
    ]
    );

    if (! $product->canSell(1)) {
    throw new \Exception('Out of stock');
    }

    $item = $cart->items()
    ->where('product_id', $product->id)
    ->first();

    if ($item) {

    if (! $product->canSell($item->quantity + 1)) {
    throw new \Exception('Not enough stock');
    }

    $item->increment('quantity', 1);

    } else {

    $cart->items()->create([
    'product_id' => $product->id,
    'quantity' => 1,
    'price' => $product->price,
    ]);
    }

    $product->increment('reserved_quantity', 1);

    return $cart;
    });
    }

    public function update($item, $quantity)
    {
    return DB::transaction(function () use ($item, $quantity) {

    $product = Product::where('id', $item->product_id)
    ->lockForUpdate()
    ->first();

    $diff = $quantity - $item->quantity;

    if ($diff > 0 && ! $product->canSell($diff)) {
    throw new \Exception('Not enough stock');
    }

    $product->increment('reserved_quantity', $diff);

    $item->update([
    'quantity' => $quantity
    ]);

    return $item;
    });
    }

    public function remove($item)
    {
    return DB::transaction(function () use ($item) {

    $product = Product::where('id', $item->product_id)
    ->lockForUpdate()
    ->first();

    $product->decrement('reserved_quantity', $item->quantity);

    $item->delete();
    });
}
}