<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeTds extends Model
{
    use SoftDeletes;

    protected $table = 'employee_tds';

    protected $fillable = [
        'employee_id',
        'financial_year',
        'month',
        'gross_salary',
        'taxable_salary',
        'tds_percent',
        'tds_amount',
        'remark',
        'created_by',
        'updated_by'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}