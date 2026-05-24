<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class LoanAdvance extends Model
{
    use HasFactory, Loggable;

    protected $table = 'loan_advances';

    protected $fillable = [
        'employee_id',
        'loan_amount',
        'month',
        'start_month',
        'deduction_amount',
        'status',
        'created_by',
        'updated_by'
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    // app/Models/LoanAdvance.php
public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id');
}
// Employee.php
public function branch()
{
    return $this->belongsTo(Branch::class, 'branch_id');
}

}