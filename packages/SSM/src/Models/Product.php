<?php

namespace SSM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'name', 'description', 'price', 'brand', 'image_url', 'is_active', 'category_id', 'category'
    ];

    public function categoryItem()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
