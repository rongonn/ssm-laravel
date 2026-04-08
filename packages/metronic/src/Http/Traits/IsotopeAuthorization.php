<?php

namespace Isotope\Metronic\Http\Traits;

use Isotope\Metronic\Models\Role;

trait IsotopeAuthorization
{
    public function initializeIsotopeAuthorization()
    {
        $this->fillable[] = 'uuid';
        $this->fillable[] = 'role_id';
        $this->fillable[] = 'locale';
    }

    public function isSuperAdmin()
    {
        return $this->role_id == "1" ? true : false;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function hasPermission($ability)
    {
        return $this->role->hasPermission($ability);
    }

    public function hasActionPermission($namespace, $controller, $method, $action)
    {
        $permission = $this->role->permissions
                    ->where('namespace', $namespace)
                    ->where('controller', $controller)
                    ->where('method', $method)
                    ->where('action', $action)
                    ->count();

        return $permission > 0 ? true : false;
    }

}