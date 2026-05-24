<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAllowance extends Model
{
    protected $fillable = [
        'employee_id',
        'hq',
        'exst',
        'outst',
        'phone',
        'hotel',
        'created_by',
        'updated_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
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