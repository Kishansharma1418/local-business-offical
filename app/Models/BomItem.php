<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
class BomItem extends Model
{
    use Loggable;
    
    protected $table = 'bom_items';
    protected $fillable = [
        'bom_master_id',
        'item_type',
        'material_id',
        'uom',
        'quantity',
        'per_unit_qty',
        'status',
        'overage',
        'warehouse_id',
        'created_by',
        'updated_by',
    ];

    public function material()
    {
        return $this->belongsTo(RawMaterial::class, 'material_id');
    }

     public function uoms()
    {
        return $this->belongsTo(Uom::class, 'uom');
    }

    public function bomMaster()
    {
        return $this->belongsTo(BomMaster::class, 'bom_master_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
}
