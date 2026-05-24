<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
class Vendor extends Model
{    use Loggable;
    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'vendor_type',
        'contact_person',
        'gst_no',
        'pan_no',
        'is_gst_registered',
        'address_line1',
        'address_line2',
        'city_id',
        'state_id',
        'country_id',
        'pincode',
        'currency_id',
        'payment_term_id',
        'status',
        'remarks',
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

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerms::class,'payment_term_id');
    }
}
