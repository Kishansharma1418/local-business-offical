<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
class EmployeeAssets extends Model
{
    use HasFactory,SoftDeletes,Loggable;

    protected $fillable = [
        'type',
        'employee_id',
        'hq_allow',
        'ex_stn_allow',
        'out_stn_allow',
        'start_date',
        'end_date',
        'bus_ticket_amount',
        'amount',
        'city_id', 'state_id', 'country_id',
        'total_amount',
        'status',
          'reason', 
        'created_by',  
        'updated_by',
        'image',
        'distance',
    ];
    
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    
}