<?php

namespace SSM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'is_active', 'image_url'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function galleryItems()
    {
        return $this->hasMany(Gallery::class);
    }
}
