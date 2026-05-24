<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class ApplyCreditInvoice extends Model
{     use Loggable;
    protected $table = 'apply_credit_invoices';

    protected $fillable = [
        'credit_note_id',
        'invoice_id',
        'applied_amount',
        'remaining_amount',
        'credit_note_balance',
        'invoice_balance',
        'over_applied_amount',
        'under_applied_amount',
        'applied_date',
        'status',
    ];

    public function creditNote()
    {
        return $this->belongsTo(CreditNote::class, 'credit_note_id');
    }

    public function invoice()
    {
        return $this->belongsTo(InvoiceOrder::class, 'invoice_id');
    }
}
