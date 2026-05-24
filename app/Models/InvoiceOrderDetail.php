<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class InvoiceOrderDetail extends Model
{
    use Loggable;
    protected $table = "invoice_order_details";

    protected $fillable = [
        'invoice_order_id',
        'product_id',
        'batch_id',
       'quantity_ordered',
        'quantity_delivered',
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
        'created_by',
        'updated_by',
    ];

    public function product()
    {
        return $this->belongsTo(FinishedGood::class, 'product_id');
    }

   

    public function invoiceOrder()
    {
        return $this->belongsTo(InvoiceOrder::class, 'invoice_order_id');
    }
}
