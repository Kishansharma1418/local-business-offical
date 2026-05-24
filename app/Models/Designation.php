<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;


class Designation extends Model
{
    use SoftDeletes,Loggable;

    protected $table="designation";
    protected $filable=['code','name','status','description','department_id','created_by','updated_by'];


     public function departments()
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class,'designation_id');
    }
}
