<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapsuleFilling extends Model
{
    protected $fillable = [
        'production_flow_start_id',
        'bom_master_id',
        'product_id',
        'product_name',
        'batch_no',
        'capsule_records',
        'filled_capsules_inspected_by',
        'inspection_date',
        'total_weight_filled_capsules',
        'total_weight_rejected_capsules',
        'production_chemist_signature',
        'production_chemist_date',
        'qa_chemist_signature',
        'qa_chemist_date'
    ];

    protected $casts = [
        'capsule_records' => 'array', // Cast JSON to array
    ];

    public function productionFlowStart()
    {
        return $this->belongsTo(ProductionFlowStart::class, 'production_flow_start_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
