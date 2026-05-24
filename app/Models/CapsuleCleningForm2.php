<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapsuleCleningForm2 extends Model
{
    protected $table = 'capsule_equipment_cleanings';

    protected $fillable = [
        'capsule_form1_id',
        'production_flow_start_id',
        'bom_master_id',
        'product_id',
        'product_name',
        'equipment_name',
        'equipment_id',
        'previous_product_name',
        'previous_batch_no',
        'cleaned_by',
         'rows',
        'cleaned_date',
        'verified_by',
        'verified_date',
        'line_clierence_given_by',
        'date',
    ];

    protected $casts = [
        'cleaned_date' => 'date',
        'verified_date' => 'date',
        'date' => 'date',
         'rows' => 'array',
          'line_clearance_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Capsule Form1 (Parent Form)
    public function capsuleForm()
    {
        return $this->belongsTo(CapsuleForm1::class, 'capsule_form1_id');
    }

    // Production Flow
    public function productionFlow()
    {
        return $this->belongsTo(ProductionFlowStart::class, 'production_flow_start_id');
    }

    // BOM Master
    public function bom()
    {
        return $this->belongsTo(BomMaster::class, 'bom_master_id');
    }

    // Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}