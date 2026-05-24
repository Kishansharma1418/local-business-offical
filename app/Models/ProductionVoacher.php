<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionVoacher extends Model
{
    protected $table = 'production_voachers';
    protected $fillable = [
        'store_issurance_id',
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
        'verified_by_production',
        'verified_notes_production',
        'created_by',
        'updated_by',
    ];

    public function bomMaster()
    {
        return $this->belongsTo(BomMaster::class, 'bom_master_id');
    }

    public function items()
    {
        return $this->hasMany(ProductionVoacherItem::class, 'production_voacher_id');
    }

    public function verifiedByProduction()
    {
        return $this->belongsTo(User::class, 'verified_by_production');
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