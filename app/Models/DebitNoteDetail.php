<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class DebitNoteDetail extends Model
{
    use Loggable;
    protected $table = 'debit_note_details';

    protected $fillable = [
        'debit_note_id',
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
    public function debitNote()
    {
        return $this->belongsTo(DebitNote::class, 'debit_note_id');
    }
    public function product()
    {
        return $this->belongsTo(FinishedGood::class, 'product_id');
    }
    
}
