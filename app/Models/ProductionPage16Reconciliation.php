<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionPage16Reconciliation extends Model
{

    protected $fillable = [
        'production_flow_start_id',
        'bom_master_id',
        'material_type',
        'std_qty',
        'qty_issued',
        'additional_required',
        'total_issued',
        'packed_qty',
        'sample_qty',
        'specimen_qty',
        'total_packed',
        'rejection_qty',
        'total_consumed',
        'returned_qty',
        'final_total'
    ];

    public function productionFlowStart()
    {
        return $this->belongsTo(ProductionFlowStart::class, 'production_flow_start_id');
    }
}
