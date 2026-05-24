<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeOvertime extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'employee_id',
        'date',
        'hours',
        'rate_per_hour',
        'total_amount',
        'remark',
        'created_by',
        'updated_by'

    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}