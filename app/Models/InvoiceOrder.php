<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Carbon\Carbon;
use App\Models\PaymentTerms;

class InvoiceOrder extends Model
{
    use Loggable;
    protected $table = "invoice_orders";

    protected $fillable = [
        'code',
        'date',
        'customer_id',
        'sale_order_id',
        'branch_id',
        'currency_id',
        'sales_person_id',
        'payment_terms_id',
        'credit_limit',
        'approval_status',
        'total_amount',
        'overall_bill_discount_amount',
        'overall_bill_discount_type',
        'overall_bill_discount_percent',      
        'tax_amount',
        'net_amount',
        'balance_amount',
        'outstanding_amount',
        'payment_status',
        'status',
        'due_date',
        'created_by',
        'updated_by',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceOrderDetail::class, 'invoice_order_id');
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sale_order_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'invoice_order_id');
    }


    public function salesPerson()
    {
        return $this->belongsTo(Employee::class, 'sales_person_id');
    }

    public function paymentTerms()
    {
        return $this->belongsTo(PaymentTerms::class, 'payment_terms_id');
    }

    public function creditNotes()
    {
        return $this->hasMany(CreditNote::class, 'invoice_id');
    }

    public function debitNotes()
    {
        return $this->hasMany(DebitNote::class, 'invoice_order_id');
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            if ($order->payment_terms_id && $order->date) {
                $term = PaymentTerms::find($order->payment_terms_id);
                $order->due_date = \Carbon\Carbon::parse($order->date)->addDays($term->days);
            }
        });
    }

        public function states()
        {
            return $this->belongto(State::class,'state_id');
        }
}
