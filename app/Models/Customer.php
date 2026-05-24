<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class Customer extends Model
{
    use SoftDeletes,Loggable;

    protected $table="customers";
    protected $fillable=[
            'code',
            'name',
           'customer_type',
            'contact_person',
            'mobile_no',
            'email',
            'gst_no',
            'gst_type',
            'pan_no',
            'credit_limit',
            'credit_days',
            'payment_terms_id',
            'is_blocked', 
            'blocked_reason',
            'branch_id',
            'payment_terms_id',
            'state_id',
            'is_login', 
            'status', 
            'created_by',
            'updated_by',
    ];

    public function branches()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function getCustomerAddress()
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id');
    }

    public function getCustomerDiscounts()
    {
        return $this->hasMany(CustomerProductDiscount::class, 'customer_id');
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_id');
    }

    public function billingAddress()
    {
        return $this->hasOne(CustomerAddress::class)
            ->where('address_type', 'Billing');
    }

    public function shippingAddress()
    {
        return $this->hasOne(CustomerAddress::class)
            ->where('address_type', 'Shipping');
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerms::class, 'payment_terms_id');
    }

    public function invoices()
    {
        return $this->hasMany(InvoiceOrder::class, 'customer_id');
    }

    public function states()
    {
        return $this->belongsTo(State::class,'state_id');
    }

}
