<?php

namespace SSM\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $table = 'product_reviews';
    
    protected $fillable = [
        'product_id',
        'name',
        'mobile',
        'age',
        'rating',
        'review_text',
        'is_approved',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
