<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
class CreditNote extends Model
{    
    use Loggable;
    protected $table = 'credit_notes';

    protected $fillable = [
        'credit_note_number',
        'customer_id',
        'branch_id',
        'sales_order_id',
        'invoice_id',
        'sales_person_id',
        'credit_note_date',
        'total_amount',
        'tax_amount',
        'net_amount',
        'type',
        'status',
        'payment_status',
        'remarks',
        'created_by',
        'updated_by',
        'reference_number',
        'reason_type',
        'balance_due',
        'used_amount',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function invoice()
    {
        return $this->belongsTo(InvoiceOrder::class, 'invoice_id');
    }
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
    public function creditNoteDetails()
    {
        return $this->hasMany(CreditNoteDetail::class, 'credit_note_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function salesPerson()
    {
        return $this->belongsTo(Employee::class, 'sales_person_id');
    }

    public function refundOrders()
    {
        return $this->hasMany(RefundOrder::class, 'credit_note_id');
    }

    public function getBalanceDueAttribute()
    {
        $refunded = $this->refundOrders()->sum('amount');
        return $this->net_amount - $refunded;
    }
 public function states()
        {
            return $this->belongto(State::class,'state_id');
        }
        
}
