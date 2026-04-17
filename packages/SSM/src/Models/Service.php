<?php

namespace SSM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Service extends Model
{
    use HasUuids;

    protected $fillable = [
        'name', 'description', 'price', 'duration', 'category_id', 'image_url', 'category'
    ];

    public function categoryItem()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
