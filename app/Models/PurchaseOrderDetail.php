<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PurchaseOrderDetail extends Model
{
    protected $table = 'purchase_order_details';
    protected $fillable = [
        'purchase_order_id',
        'raw_material_id',
        'quantity_ordered',
        'uom_id',
        'unit_price',
        'gst_amount',
        'discount_amount',
        'discount_percent',
        'gst_percent',
        'total_price',
        'received_quantity',
        'notes',
        'status',
        'invoice_number',
        'created_by',
        'updated_by',
        'mfg_date',
        'expiry_date'   
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }

    

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
