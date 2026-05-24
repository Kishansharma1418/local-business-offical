<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    protected $table = 'production_batches';

    protected $fillable = [
        'product_id',
        'branch_id',
        'bom_master_id',
        'batch_number',
        'mfg_date',
        'expiry_date',
        'quantity',
        'rate',
        'total_amount',
        'status',
        'material_requisition_no',
        'line_clearance_given_by',
        'raw_material_issued_on',
        'created_by',
        'updated_by',
        'stage',
        'last_stage', 
        'packing_type',
        'pack_size',
        'box_size',
        'pack_config_id',
        'no_of_boxes',
        'batch_size_qty',
        'product_type',
    ];

    public function bomMaster()
    {
        return $this->belongsTo(BomMaster::class, 'bom_master_id');
    }

    public function items()
    {
        return $this->hasMany(ProductionBatchItem::class, 'production_batch_id');
    }

    public function approvals()
    {
        return $this->hasMany(ProductionApproval::class, 'production_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function packagingType()
{
    return $this->belongsTo(PackgingType::class, 'pack_config_id');
}

}