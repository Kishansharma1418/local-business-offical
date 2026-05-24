<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Payment extends Model
{
    use Loggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
   protected $fillable = [
        'code',
        'invoice_order_id',
        'debit_note_id',
        'customer_id',
        'amount_paid',
        'amount_withheld',
        'payment_date',
        'payment_received_on',
        'bank_charges',
        'upload_receipt',
        'payment_method',
        'notes',
        'reference_number',
        'tax_deduction',
        'payment_status',
        'created_by',
        'updated_by',
    ];

    public function invoiceOrder()
    {
        return $this->belongsTo(InvoiceOrder::class, 'invoice_order_id');
    }

}
