<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class CreditNoteDetail extends Model
{
    use Loggable;
    protected $table = 'credit_note_details';

    protected $fillable = [
        'credit_note_id',
        'invoice_detail_id',
        'is_manual_item',
        'product_id',
        'batch_id',
        'quantity',
        'unit_price',
        'discount_percent',
        'discount_amount',
        'gst_percent',
        'gst_amount',
        'total_amount',
        'expiry_date',
        'manufacturing_date',
        'status',
        'created_by',
        'updated_by',
    ];

    public function product()
    {
        return $this->belongsTo(FinishedGood::class, 'product_id');
    }
    public function creditNote()
    {
        return $this->belongsTo(CreditNote::class, 'credit_note_id');
    }

    public function batch()
    {
        return $this->belongsTo(BatchManagement::class, 'batch_id');
    }

    public function invoiceDetail()
    {
        return $this->belongsTo(InvoiceOrderDetail::class, 'invoice_detail_id');
    }

    
}
