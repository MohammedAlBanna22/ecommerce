<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price'
    ];


    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
public static function getTotalQuantity()
{
    return self::whereHas('cart', function($q){

        $q->where('user_id', auth()->id());

    })->sum('quantity');
}
}