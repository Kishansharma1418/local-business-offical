<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeAttandance extends Model
{
    use Loggable,SoftDeletes;

    protected $table="employe_attandances";

    

    protected $fillable = [
        'employee_id',
        'name',
        'date',
        'status',
        'is_holiday',
        'holiday_name',
        'check_in',
        'check_out',
        'remarks',
        'created_by',
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }


}
