<?php

namespace Isotope\Metronic\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table   = 'role_permissions';
    protected $guarded = ['id'];
}