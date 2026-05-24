<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionPage15Log extends Model
{

   protected $fillable = [
        'production_flow_start_id',
        'product_name',
        'batch_no',
        'previous_product',
        'previous_product_batch_no',
        'leak_date',
        'leak_time',
        'leak_done_by',
        'leak_result',
        'leak_verified_by',
        'leak_remarks',
        'packing_date',
        'strip_checked_by',
        'line_clierence_by',
        'carton_packing_done_by',
        'packed_carton_count',
        'rejected_carton_count',
        'packing_verified_by'
    ];

    public function productionFlowStart()
    {
        return $this->belongsTo(ProductionFlowStart::class);
    }
}
