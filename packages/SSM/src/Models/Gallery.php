<?php

namespace SSM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Gallery extends Model
{
    use HasUuids;

    protected $table = 'gallery';

    protected $fillable = [
        'title', 'category_id', 'image_url', 'category'
    ];

    public function categoryItem()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
