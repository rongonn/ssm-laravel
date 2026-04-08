<?php

namespace Isotope\Metronic\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterModel extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at' => 'datetime:d-M-Y H:i:s',
    ];
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (Schema::connection($model->getConnectionName())->hasColumn($model->getTable(), 'uuid')) {
                strlen($model->uuid) == 0 && $model->uuid  = (string) Str::uuid();
            }
            
            if (Auth::guard('web')->check() && strlen($model->created_by) == 0 && Schema::connection($model->getConnectionName())->hasColumn($model->getTable(), 'created_by')) {
                $model->created_by =  Auth::user()->id;
            } elseif (Auth::guard('web')->check() == false && Schema::connection($model->getConnectionName())->hasColumn($model->getTable(), 'created_by')){
                $model->created_by = null;
            }
        });
        static::updating(function ($model) {
            if (Auth::guard('web')->check() && strlen($model->updated_by) == 0 && Schema::connection($model->getConnectionName())->hasColumn($model->getTable(), 'updated_by')) {
                $model->updated_by = Auth::user()->id;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public static function scopeSearch(Builder $query)
    {
        $req = collect(request()->input('search'));
        $q = $req->map(fn ($r, $k) => isset($r) ? "$k LIKE '%$r%'" : "")
            ->filter(fn ($r) => !empty($r))
            ->join(' and ');
        strlen($q) > 0 && $query->whereRaw($q);
    }
}
