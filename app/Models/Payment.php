<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    //
      use HasFactory;

    /**
     * الحقول القابلة للتعبئة (Mass Assignment)
     */
    protected $fillable = [
        'order_id',
        'amount',
        'currency',
        'method',
        'transaction_id',
        'status',
        'gateway_response',
        'paid_at',
    ];

    /**
     * تحويل الأنواع (Casting)
     */
    protected $casts = [
        'gateway_response' => 'array', // JSON → Array تلقائياً
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * ===========================
     * RELATIONSHIPS (العلاقات)
     * ===========================
     */

    /**
     * كل دفع ينتمي لطلب واحد
     *
     * مثال:
     * $payment->order->total_price
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * ===========================
     * SCOPES (نطاقات الاستعلام)
     * ===========================
     */

    /**
     * الدفعات الناجحة فقط
     *
     * Usage: Payment::completed()->get()
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * الدفعات الفاشلة
     *
     * Usage: Payment::failed()->get()
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * الدفعات المعلقة
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * دفعات بطاقة معينة (Stripe/PayPal/COD)
     *
     * Usage: Payment::ofMethod('stripe')->get()
     */
    public function scopeOfMethod($query, string $method)
    {
        return $query->where('method', $method);
    }

    /**
     * ===========================
     * ACCESSORS & HELPERS
     * ===========================
     */

    /**
     * هل الدفع ناجح؟
     *
     * Usage: if ($payment->isSuccessful()) { ... }
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * هل الدفع فشل؟
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * هل يمكن استرداده؟
     */
    public function canBeRefunded(): bool
    {
        return in_array($this->status, ['completed']) &&
               $this->refunds()->count() == 0;
    }

    /**
     * تنسيق المبلغ مع العملة
     *
     * Usage: $payment->formatted_amount // "$50.00"
     */
    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount, 2);
    }

    /**
     * أيقونة الحالة (للـ Frontend)
     *
     * Usage: $payment->status_icon // "✅"
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'completed' => '✅',
            'pending' => '⏳',
            'failed' => '❌',
            'refunded'=> '💰',
            default => '❓'
        };
    }

    /**
     * لون الحالة (للـ CSS)
     *
     * Usage: $payment->status_color // "green"
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'completed' => 'green',
            'pending' => 'yellow',
            'failed' => 'red',
            'refunded' => 'gray',
            default => 'gray'
        };
    }

    /**
     * ===========================
     * CUSTOM METHODS
     * ===========================
     */

    /**
     * تحديث حالة الدفع إلى مكتوب
     */
    public function markAsCompleted(string $transactionId, ?array $gatewayResponse = null): self
    {
        $this->update([
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'paid_at' => now(),
            'gateway_response' => $gatewayResponse,
        ]);

        return $this;
    }

    /**
     * تحديث حالة الدفع إلى فاشل
     */
    public function markAsFailed(string $errorMessage): self
    {
        $this->update([
            'status' => 'failed',
            'gateway_response' => ['error' => $errorMessage],
        ]);

        return $this;
    }

    /**
     * تسجيل محاولة الدفع (قبل المعالجة)
     */
    public static function createPending(Order $order, string $method): self
    {
        return self::create([
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'currency' => $order->currency ?? config('cashier.currency', 'USD'),
            'method' => $method,
            'status' => 'pending',
        ]);
    }
}
