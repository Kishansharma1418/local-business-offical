<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * Auto-scopes a model to the currently logged-in client's tenant.
 * Also auto-fills `tenant_id` on create.
 *
 * Super admins (role=admin) bypass the scope so they can see all tenants' data.
 */
trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (Auth::check() && Auth::user()->role === 'client' && Auth::user()->tenant_id) {
                $builder->where($builder->getModel()->getTable() . '.tenant_id', Auth::user()->tenant_id);
            }
        });

        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->role === 'client' && empty($model->tenant_id)) {
                $model->tenant_id = Auth::user()->tenant_id;
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
}
