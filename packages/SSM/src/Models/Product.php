<?php

namespace SSM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'name', 'description', 'price', 'offer_price', 'brand', 'image_url', 'is_active', 'category_id', 'category'
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'image_url'  => 'array',
    ];

    /**
     * Get the first (main) image URL.
     */
    public function getMainImageAttribute(): ?string
    {
        $images = $this->image_url;
        return is_array($images) && count($images) > 0 ? $images[0] : null;
    }

    /**
     * Get all image URLs as an array.
     */
    public function getAllImagesAttribute(): array
    {
        return is_array($this->image_url) ? $this->image_url : [];
    }

    public function categoryItem()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}
