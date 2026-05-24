<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
class EmployeeSalaryComponent extends Model
{     
    use Loggable;
    protected $table="employee_salary_components";
    protected $fillable=[
        'revision_id',
        'salary_component_id',
        'old_amount',
        'amount'
    ];

    public function salaryComponet()
    {
        return $this->belongsTo(SalaryComponent::class, 'salary_component_id');
    }

   
 
}
