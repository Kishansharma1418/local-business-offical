<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
class CustomerAddress extends Model
{  
    use Loggable;
    
    protected $table = "customer_addresses";
           
    protected $fillable=[
            'customer_id',
            'address_title',
           'address_type',
            'address_line1',
            'address_line2',
            'city_id',
            'state_id',
            'country_id',
            'pincode',
           'is_default', 
            'created_by',
            'updated_by',
          
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
