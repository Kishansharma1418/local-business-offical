<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employee extends Model
{
    use Loggable,SoftDeletes;

    protected $fillable=[
        'role',
            'code',
            'first_name',
            'middle_name',
            'last_name',
            'full_name',
            'gender',
            'dob',
            'official_mail',
            'personal_mail',
            'mobile_no',
            'alternative_no',
            'joining_date',
            'branch_id',
            'department_id',
            'designation_id',
            'reporting_id',
            'role_id',
            'fathers_name',
            'territory_id',
            'city_id',
            'address_line1',
            'address_line2',
            'state_id',
            'country_id',
            'pincode',
            'marital_status',
            'sales_head',
            'blood_group',
            'emergancy_contact_name',
            'emergancy_contact_number',
            'employee_type',
            'pan_no',
            'pf_number',
            'esi_number',
            'aadhaar_no',
            'is_login',
            'status',
            'uan_no',
            'relieving_date',
            'separation_type',
            'separation_remarks',
            'relieving_approved_by',
            'relieving_approvel_date',
            'created_by',
            'pf_aplicable',
            'esi_aplicable',
            'employee_image',
    ];


    public function countries()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function states()
    {
        return $this->belongsTo(State::class,'state_id');
    }

    public function cities()
    {
        return $this->belongsTo(City::class,'city_id');
    }

    public function branches()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }

    public function departments()
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class,'designation_id');
    }

    public function roles()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class,'role_id');
    }
    public function manager()
{
    return $this->belongsTo(Employee::class,'reporting_id');
}
public function team()
{
    return $this->hasMany(Employee::class,'reporting_id');
}

    public function getemployeSalary()
    {
        return $this->hasOne(EmployeeSalary::class, 'employee_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'reporting_id','id');
    }

    public function bankDetails()
    {
        return $this->hasOne(EmployeBank::class, 'employee_id');
    }
    public function salaryRevisions()
    {
        return $this->hasMany(EmployeeSalaryRevision::class, 'employee_id');
    }
    public function latestRevision()
    {
        return $this->hasOne(EmployeeSalaryRevision::class, 'employee_id')->latestOfMany();
    }

    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            do {
                $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            } while (self::where('unique_id', $code)->exists());

            $employee->unique_id = $code;
        });
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function assets()
{
    return $this->hasMany(EmployeeAsset::class);
}
}