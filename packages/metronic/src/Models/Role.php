<?php

namespace Isotope\Metronic\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = ['id'];
    
    public function permissions() {
        return $this->hasMany(Permission::class, 'role_id');
    }

    public function hasPermission($ability)
    {
        $permissions = $this->permissions->pluck('permission')->toArray();
        return in_array($ability, $permissions);
    }

}