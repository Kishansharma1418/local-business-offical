<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;


class LastMonthAdjustment extends Model
{
    use Loggable,SoftDeletes;
    
    protected $table = 'last_month_adjustment';
    protected $fillable = [
        'loan_id',
        'employee_id',
        'adjustment_date',
        'adjustment_month',
        'current_month',
        'adjustment_amount',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function loanAdvance()
    {
        return $this->belongsTo(LoanAdvance::class, 'loan_id', 'id');
    }
}
