<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [

        'user_id',
        'address_id',
        'total_price',
        'payment_method',
        'payment_status',
        'order_status',
        'coupon_code',
        'discount',
        'stripe_pi',
         //'subtotal',
        'tracking_number',
        'shipping_carrier',
        'shipped_at',
        'delivered_at',
    ];
    protected $casts = [
    'shipped_at' => 'datetime',
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

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    // // ✅ Scopes
    // public function scopePending($query)
    // {
    //     return $query->where('order_status', 'pending');
    // }

    // public function scopeCompleted($query)
    // {
    //     return $query->where('order_status', 'completed');
    // }

    // public function scopeUnpaid($query)
    // {
    //     return $query->where('payment_status', 'unpaid');
    // }




    public function payments()
{
    return $this->hasMany(Payment::class);
}

/**
 * آخر دفع لهذا الطلب
 *
 * Usage: $order->latestPayment->status
 */
public function latestPayment()
{
    return $this->hasOne(Payment::class)->latestOfMany();
}

/**
 * هل الطلب مدفوع؟
 */
public function isPaid(): bool
{
    return $this->payment_status === 'paid' ||
           $this->payments()->completed()->exists();
}

/**
 * هل الطلب في انتظار الدفع؟
 */
public function isPaymentPending(): bool
{
    return in_array($this->payment_status, ['pending', 'unpaid']);
}

/**
 * تحديث حالة دفع الطلب
 */
public function markAsPaid(string $transactionId = null): void
{
    $this->update([
        'payment_status' => 'paid',
        'order_status' => 'processing',
        'paid_at' => now(),
    ]);

    // تحديث آخر payment
    if ($this->latestPayment) {
        $this->latestPayment->markAsCompleted($transactionId ?? 'manual');
    }
}
}