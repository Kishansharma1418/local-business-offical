<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmployeeSalary extends Model
{

    use SoftDeletes,Loggable;

    
    protected $fillable=[
        'employee_id',
        'component_id',
        'amount',
        'percentage',
        'effactive_from',
        'effactive_to',
        'status',
        'remarks',
        'created_by',
        'updated_by'
    ];
}
