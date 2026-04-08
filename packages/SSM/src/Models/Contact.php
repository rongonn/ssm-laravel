<?php

namespace SSM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Contact extends Model
{
    use HasUuids;

    protected $fillable = [
        'name', 'email', 'subject', 'message', 'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
