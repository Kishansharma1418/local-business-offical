<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Carbon\Carbon;
use App\Models\PaymentTerms;

class SalesOrder extends Model
{
    use Loggable;
    protected $table = 'sales_orders';

    protected $fillable = [
        'code',
        'date',
        'customer_id',
        'branch_id',
        'currency_id',
        'sales_person_id',
        'payment_terms_id',
        'credit_limit',
        'approval_status',
        'total_amount',
        'tax_amount',
        'net_amount',
        'outstanding_amount',
        'payment_status',
        'status',
        'due_date',
        'type',
        'created_by',
        'updated_by',
        'overall_bill_discount_amount',
        'overall_bill_discount_type',
        'overall_bill_discount_percent'
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }


    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

  
    public function salesPerson()
    {
        return $this->belongsTo(Employee::class, 'sales_person_id');
    }

    public function paymentTerms()
    {
        return $this->belongsTo(PaymentTerms::class, 'payment_terms_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function salesOrderDetails()
    {
        return $this->hasMany(SalesOrderDetails::class, 'sales_order_id');
    }


    public function salesOrderApprovals()
    {
        return $this->hasMany(SalesOrderApproval::class, 'sales_order_id');
    }


    
    protected static function booted()
    {
        static::creating(function ($order) {
            if ($order->payment_terms_id && $order->date) {
                $term = PaymentTerms::find($order->payment_terms_id);
                $order->due_date = Carbon::parse($order->date)->addDays($term->days);
            }
        });
    }




    

}
