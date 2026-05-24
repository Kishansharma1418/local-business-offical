<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoatedTabletProductionForm extends Model
{
    protected $table = 'coated_tablet_production_forms';
    
    protected $fillable = [
        'production_batch_id',
        'bom_master_id',
        'batch_no',
        'production_flow_id',
        'product_name',
        'thickness',
        'weight',
        'hardness',
        'average_thickness',
        'average_weight',
        'average_hardness',
        'tablets_inspected_date',
        'total_weight_coated_tablets',
        'total_weight_rejected_tablets',
        'production_chemist',
        'production_date',
        'qa_incharge',
        'qa_date'
    ];
}
