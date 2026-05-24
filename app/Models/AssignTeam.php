<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignTeam extends Model
{
    
    protected $fillable = [
        'production_id',
        'role_id',
        'user_id',
        'assign_date',
        'stage',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];
}
