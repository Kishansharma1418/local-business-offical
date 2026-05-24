<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
class DebitNote extends Model
{   use Loggable;
    protected $table = 'debit_notes';
    
    protected $fillable = [
        'debit_note_number',
        'customer_id',
        'invoice_order_id',
        'sales_order_id',
        'sales_person_id',
        'branch_id',
        'payment_term_id',
        'type',
        'reference_number',
        'debit_note_date',
        'total_amount',
        'tax_amount',
        'net_amount',
        'balance_due',
        'due_date',
        'reason_type',
        'remarks',
        'created_by',
        'updated_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function paymentTerms()
    {
        return $this->belongsTo(PaymentTerms::class, 'payment_term_id');
    }
    public function employees()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
  public function salesPerson()
    {
        return $this->belongsTo(Employee::class, 'sales_person_id');
    }
  public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function debitNoteDetails()
    {
        return $this->hasMany(DebitNoteDetail::class, 'debit_note_id');
    }
    public function payments()
{
    return $this->hasMany(Payment::class, 'debit_note_id');
}


    
}
