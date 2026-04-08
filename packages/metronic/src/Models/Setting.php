<?php

namespace Isotope\Metronic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:d-M-Y H:i:s',
    ];
    protected $guarded = [];
}
