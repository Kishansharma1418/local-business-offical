<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class Branch extends Model
{
    use HasFactory, SoftDeletes,Loggable;
    protected $table = 'branches';
    
    protected $fillable = [
         'code', 'branch_name', 'branch_type', 'address_line1', 'address_line2',
        'city_id', 'state_id', 'country_id', 'pin_code', 'phone', 'mobile', 'email',
        'gst_number', 'pan_number', 'manager_employee_id', 'parent_branch_id', 'currency_id',
        'status', 'notes','created_by', 'updated_by', 'policy_no', 'dl_no', 'cbn_no'
    ];

    public function users()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class,'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }

    // public function branchManager()
    // {
    //     return $this->belongsTo(Employee::class,'branch_id');
    // }

    public function employees()
    {
        return $this->hasMany(Employee::class,'branch_id');
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
