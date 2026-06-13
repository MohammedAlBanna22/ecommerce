<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    use HasFactory;
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'discount_price',
        'quantity',
        'image',
        'status'
    ];
     protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        //'status' => 'boolean',
        'quantity' => 'integer',
    ];

    public function category() {
    return $this->belongsTo(Category::class);
    }

    public function reviews() {
    return $this->hasMany(Review::class);
    }
    public function cartItems()
    {
    return $this->hasMany(CartItem::class);
    }

}
