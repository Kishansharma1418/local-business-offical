<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class EmployeBank extends Model
{
    use HasFactory,Loggable;

    protected $fillable = [
        'employee_id',
        'employee_name',
        'bank_name',
        'ifsc_code',
        'account_number',
        'confirm_account_number',
        'branch_address',
        'bank_passbook',
        'cheque',
        'created_by',
    ];

    public function banks()
    {
        return $this->belongsTo(BankDetail::class,'bank_name');
    }

     public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
}

