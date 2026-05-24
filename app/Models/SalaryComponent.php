<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryComponent extends Model
{
    use Loggable,SoftDeletes;

    protected $fillable=[
        'component_name',
        'component_type',
        'calculation_type',
        'based_component_id',
        'is_taxable',
        'status',
        'created_by',
        'updated_by',
        'percentage_value',
    ];
}
