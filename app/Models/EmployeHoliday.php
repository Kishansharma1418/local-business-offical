<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class EmployeHoliday extends Model
{
    use SoftDeletes,Loggable;
    
        protected $table = "employe_holidays";
        protected $fillable=[
            'employe_name',
            'title',
            'description',
            'start_date',
            'end_date',
            'status',
            'created_by',
            'updated_by'
        ];
        
        protected $dates = [
            'start_date',
            'end_date',
        ];

        public function getDateRangeAttribute()
        {
            if ($this->start_date && $this->end_date) {
                return $this->start_date->format('d M Y') . ' - ' . $this->end_date->format('d M Y');
            }
            return '-';
        }
     
        public function branches()
        {
            return $this->hasMany(EmployeHolidayBranch::class, 'employe_holiday_id');
        }

}
