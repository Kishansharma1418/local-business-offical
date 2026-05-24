<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreIssurance extends Model
{
    protected $table = 'store_issurances';

    protected $fillable = [
           'requisition_production_batch_id',
           'bom_master_id',
           'branch_id',
        'pack_config_id',
            'batch_number', 
            'mfg_date',
            'expiry_date',
            'quantity',
            'rate',
            'total_amount',
            'status',
            'batch_size_qty',
            'packing_type',
            'product_type',
            'pack_size',
            'box_size',
            'no_of_boxes',
            'material_requisition_no',
            'line_clearance_given_by',
            'raw_material_issued_on',
            'created_by',
            'updated_by',
        ];

    public function bomMaster()
    {
        return $this->belongsTo(BomMaster::class, 'bom_master_id');
    }

    public function items()
    {
        return $this->hasMany(StoreIssuranceItem::class, 'store_issurance_id');
    }

    public function approvals()
    {
        return $this->hasMany(StoreIssuranceApprovel::class, 'store_issurance_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

}