<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionVoacherItem extends Model
{
    protected $table = 'production_voacher_items';
    protected $fillable = [
        'production_voacher_id',
        'material_id',
        'warehouse_id',
        'base_quantity',
        'final_quantity',
        'uom',
        'overage_percent',
        'status',
        'specfication',
        'control_ref_no',
        'analytical_report_no',
        'weight_by',
        'recevied_checked_by',
        'created_by',
        'updated_by',
    ];

    public function material()
    {
        return $this->belongsTo(RawMaterial::class, 'material_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function uoms()
    {
        return $this->belongsTo(Uom::class, 'uom');
    }
  
}
