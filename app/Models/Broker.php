<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
class Broker extends Model
{
    use HasFactory, Loggable;

    protected $table = 'brokers';

    protected $fillable = [
        'code',
        'broker_name',
        'contact_person',
        'mobile_no',
        'email',
        'gst_number',
        'pan_number',
        'address_line1',
        'address_line2',
        'city_id',
        'state_id',
        'country_id',
        'pincode',
        'commission_type',
        'commission_value',
        'currency_id',
        'status',
        'remarks',
    ];

    /* ================= RELATIONSHIPS ================= */

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
