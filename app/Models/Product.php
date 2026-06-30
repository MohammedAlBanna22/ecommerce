<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //

    use HasFactory;
    protected $fillable = [
        'sku',
        'barcode',
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
    public function getAvailableAttribute()
    {
        return $this->quantity - $this->reserved_quantity;
    }
    public function canSell($qty)
    {
        return $this->available >= $qty;
    }
    public function confirmSale($qty)
    {
        if ($this->reserved_quantity < $qty || $this->quantity < $qty) {
        throw new \Exception("Invalid stock state");
        }
        $this->decrement('reserved_quantity', $qty);
        $this->decrement('quantity', $qty);
    }
    protected static function boot()
    {
        parent::boot();


        static::creating(function($product){

            if(!$product->sku)
            {
                $product->sku =
                'SKU-'.str()->upper(
                    str()->random(8)
                );
            }

        });

    }


    public function inventoryLogs()
    {
        return $this->hasMany(
            InventoryLog::class
        );
    }


    public function sell($qty,$reason)
    {

        $this->decrement(
        'quantity',
        $qty
        );


        $this->inventoryLogs()->create([

        'quantity'=>$qty,

        'type'=>'decrease',

        'reason'=>$reason

        ]);

    }

        public function media()
    {
        return $this->morphMany(Media::class, 'mediable')->orderBy('sort_order');
    }

    public function images()
    {
        return $this->morphMany(Media::class, 'mediable')
            ->where('type', 'image');
    }

    public function mainImage()
    {
        return $this->morphOne(Media::class, 'mediable')
            ->where('is_primary', true);
    }


    public function getImageUrlAttribute()
    {
        return $this->mainImage
            ? asset('storage/'.$this->mainImage->path)
            : asset('images/default.png');
    }



}