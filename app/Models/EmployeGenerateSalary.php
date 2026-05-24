<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable; 
class EmployeGenerateSalary extends Model
{  use Loggable;
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'gross_salary',
        'deduction_total',
        'leave_amount',
        'verified_expenses',
        'net_salary',
        'present_days',
        'absent_days',
        'weekly_off',
        'half_day',
        'holiday',
        'status',
        'loan_amount_deduction',
        'expense_total',
        'pf_amount',
        'esi_amount',
        'hra_amount',
        'conveyance_amount',
        'last_month_adjustment',
        'pf_company_contribution',
        'esi_company_contribution',
        'total_company_contribution',
        'total_deduction',
        'total_earning',
        'bounnce_employee',
        'tds_amount',
         'created_by',
        'updated_by',
        
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
      public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
