<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
      use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'city',
        'area',
        'street',
        'details',
        'is_default'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope للعناوين الافتراضية
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
     // لعرض العنوان بشكل مرتب في الفاتورة وصفحة الطلب
    public function getFullAddressAttribute()
    {
        $parts = array_filter([$this->street, $this->area, $this->city]);
        $address = implode(', ', $parts);

        if ($this->details) {
            $address .= ' - ' . $this->details;
        }

        return $address;
    }


    /**
     * Get short address (for lists)
     *
     * Usage: $address->short_address
     * Output: "Cairo - Apt 5B"
     */
    public function getShortAddressAttribute(): string
    {
        $short = $this->city;

        if ($this->details) {
            $short .= ' - ' . $this->details;
        }

        return $short;
    }

    /**
     * Get address for shipping labels
     */
    public function getShippingLabelAttribute(): string
    {
        return "{$this->full_name}\n{$this->street}\n{$this->area}, {$this->city}\n{$this->phone}";
    }

    /**
     * Format phone number
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        return $this->phone ? '+' . preg_replace('/[^0-9]/', '', $this->phone) : null;
    }

    /**
     * Check if address is complete
     */
    public function isComplete(): bool
    {
        return $this->full_name &&
               $this->street &&
               $this->city &&
               $this->phone;
    }

    /**
     * ═══════════════════════════════════════
     * EVENTS (Optional)
     * ═══════════════════════════════════════
     */

    protected static function booted(): void
    {
        // When setting a new default, unset others
        static::creating(function ($address) {
            if ($address->is_default) {
                $address->user_id
                    ?->addresses()
                    ->update(['is_default' => false]);
            }
        });

        static::updating(function ($address) {
            if ($address->is_default) {
                $address->user_id
                    ?->addresses()
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
