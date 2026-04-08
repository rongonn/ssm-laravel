<?php

namespace SSM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Testimonial extends Model
{
    use HasUuids;

    protected $fillable = [
        'author', 'content', 'rating', 'avatar_url', 'date'
    ];
}
