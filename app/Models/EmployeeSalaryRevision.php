<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class EmployeeSalaryRevision extends Model
{
    
    use SoftDeletes,Loggable;

    protected $fillable = [
        'employee_id',
        'new_salary_total',
        'effective_from',
        'total_deduction',
        'revision_reason',
        'status',
        'created_by',
        'updated_by',
        'net_salary',
    ];

    public function branches()
    {
        return $this->hasMany(EmployeeSalaryComponent::class, 'employe_holiday_id');
    }

    public function components()
    {
        return $this->hasMany(EmployeeSalaryComponent::class, 'revision_id');
    }

     public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
