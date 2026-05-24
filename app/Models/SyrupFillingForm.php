<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyrupFillingForm extends Model
{
    protected $fillable = [
        'production_flow_start_id',
        'bom_master_id',
        'product_id',
        'product_name',
        'batch_number',
        'datetime',
        'filled_volume',
        'ropp_cap',
        'checked_by',
        'verified_by',
        'total_filled_qty',
        'prev_product',
        'prev_batch',
        'line_clearance_by',
        'temprature',
        'colour_appearance',
        'ph',
        'inspection_start',
        'inspection_done_by',
        'inspection_completed',
        'inspection_verified'

    ];

    protected $casts = [
        'datetime' => 'array',
        'filled_volume' => 'array',
        'ropp_cap' => 'array',
        'checked_by' => 'array',
        'verified_by' => 'array'
    ];

    public function productionFlowStart()
    {
        return $this->belongsTo(ProductionFlowStart::class);
    }

}
