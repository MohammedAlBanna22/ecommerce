<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',           // ✅ Add slug for SEO-friendly URLs
        'description',
        'image',
        'status',         // ✅ Add status (active/inactive)
        'parent_id'       // ✅ If you want subcategories later
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all products for the category
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }

        // Return default placeholder if no image
        return asset('images/default-category.png');
    }

    /**
     * Check if category has image
     */
    public function hasImage(): bool
    {
        return !empty($this->image) && Storage::disk('public')->exists($this->image);
    }
}