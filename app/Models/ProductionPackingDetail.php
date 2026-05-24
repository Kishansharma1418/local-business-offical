<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionPackingDetail extends Model
{
    protected $fillable = [
        'production_flow_start_id',
        'bom_master_id',
        'product_name',
        'batch_no',
        'previous_product',
        'previous_batch_no',
        'line_clearance_date',
        'packing_date',
        'machine_id',
        'machine_operator',
        'bfr_temperature',
        'sfr_temperature',
        'duration',
        'verified_by',
        'carton_batch_no',
        'carton_mfd',
        'carton_exp',
        'carton_mrp',
        'carton_printed_date',
        'foil_batch_no',
        'foil_mfd',
        'foil_exp',
        'foil_mrp',
        'foil_printed_date'
    ];

    public function productionFlowStart()
    {
        return $this->belongsTo(ProductionFlowStart::class);
    }   
}
