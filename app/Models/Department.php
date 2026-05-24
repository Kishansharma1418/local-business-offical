<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
class Department extends Model
{
    use Loggable, SoftDeletes;

    protected $fillable = [
        'department_id', 'code', 'department_name',
        'parent_department_id', 'branch_id', 'department_head_id',
        'email', 'phone', 'description', 'status', 'created_by', 'updated_by'
    ];

    protected static function booted()
    {
        static::creating(function ($department) {
            $department->department_id = Str::uuid();
        });
    }


    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_department_id');
    }

  
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function head()
    {
        return $this->belongsTo(User::class, 'department_head_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}




