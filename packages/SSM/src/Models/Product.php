<?php

namespace SSM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'name', 'description', 'price', 'brand', 'stock', 'image_url', 'is_active', 'category'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
