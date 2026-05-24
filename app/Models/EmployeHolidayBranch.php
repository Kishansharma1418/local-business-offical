<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeHolidayBranch extends Model
{
    protected $table = "employe_holiday_branches";

    protected $fillable=[
        'employe_holiday_id',
        'branch_id'
    ];
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
