<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionBatchTeam extends Model
{
    protected $table="production_batch_teams";

    protected $fillable = [
        'production_batch_id',
        'bom_master_id',
        'bom_type_id',
        'role_id',
        'user_id',
        'module_type',
        'module_id',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
       return $this->belongsTo(User::class,'user_id');
    }

    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id', 'id');
    }
}
