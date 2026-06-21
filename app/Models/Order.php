<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [

        'user_id',
        'total_price',
        'payment_method',
        'payment_status',
        'order_status',
        'coupon_code',
        'discount',
    ];


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function histories()
    {
        return $this->hasMany(
            OrderStatusHistory::class
         );
    }
}
