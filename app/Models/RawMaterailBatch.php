<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterailBatch extends Model
{
    protected $fillable = [
        'raw_material_id',
        'purchase_order_id',
        'batch_no',
        'quantity',
        'referance_no',
        'expiry_date',
        'grn_no',
        'branch_id',
        'uom_id',
        'analytic_report_no',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }

    public function uoms()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    public function PurchaseOrderDetail()
    {
        return $this->belongsTo(PurchaseOrderDetail::class,'purchase_order_id');
    }
}
