<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable,  Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
            'phone',
        'birth_date',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
             'birth_date' => 'date',
        ];
    }

    public function orders() {
    return $this->hasMany(Order::class);
    }
   public function cart()
    {
    return $this->hasOne(Cart::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Order::class);
    }
      public function reviews()
    {
        return $this->hasMany(Review::class);
    }


      public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        // Fallback to a placeholder service if needed
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&background=FF9900&color=ffffff";
    }
      /**
     * Get user's initials from name
     */
    public function getInitialsAttribute(): string
    {
        $parts = explode(' ', $this->name);
        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1) . substr($parts[count($parts) - 1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 1));
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        return $this->phone ? '+' . preg_replace('/[^0-9]/', '', $this->phone) : null;
    }

    /**
     * Check if user's profile is complete
     */
    public function isProfileComplete(): bool
    {
        return $this->name &&
               $this->email &&
               $this->phone &&
               $this->birth_date;
    }


}
