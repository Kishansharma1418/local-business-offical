<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreIssuranceItem extends Model
{
    protected $table = 'store_issurance_items';

    protected $fillable = [
        'store_issurance_id',
        'material_id',
        'weight_by',
        'status',
        'created_by',
        'updated_by',
        'warehouse_id',
        'base_quantity',
        'final_quantity',
        'uom',
        'overage_percent',
        'specfication',
        'control_ref_no',
        'analytical_report_no',
        'recevied_checked_by'
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