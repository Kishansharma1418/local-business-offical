<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\loggable;

class ProductionBatchItem extends Model
{

    use HasFactory, loggable;

   protected $table = 'production_batch_items';

   protected $fillable = [
        'production_batch_id',
        'material_id',
        'warehouse_id',
        'base_quantity',
        'final_quantity',
        'uom',
        'overage_percent',
        'status',
        'created_by',
        'updated_by',
        'specfication',
        'control_ref_no',
        'analytical_report_no',
        'weight_by',
        'recevied_checked_by',
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
