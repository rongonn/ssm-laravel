<?php

namespace SSM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Team extends Model
{
    use HasUuids;

    protected $table = 'team';

    protected $fillable = [
        'name', 'role', 'bio', 'image_url', 'specialty', 'facebook_url', 'instagram_url', 'whatsapp_url'
    ];

    protected $casts = [
        'specialty' => 'array',
    ];
}
