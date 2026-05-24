<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;


class Warehouse extends Model
{  use SoftDeletes; 
    use HasFactory,Loggable;

    protected $fillable = [
        'code', 'warehouse_name', 'branch_id', 'warehouse_purpose', 'material_type',
        'is_default_warehouse', 'address_line1', 'address_line2', 'city_id', 'state_id', 'country_id',
        'pincode', 'latitude', 'longitude', 'contact_person', 'contact_number', 'email',
        'temperature_controlled', 'temperature_range_min', 'temperature_range_max', 'storage_conditions',
        'is_active', 'created_by',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

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

}
