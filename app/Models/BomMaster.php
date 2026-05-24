<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class BomMaster extends Model
{
    use Loggable;
    protected $table = 'bom_masters';
    
    protected $fillable = [
        'bom_number',
        'bom_version',
        'batch_size',
        'batch_uom',
        'bom_date',
        'finished_good_id',
        'quantity',
        'remarks',
        'status',
        'created_by',
        'updated_by',
        'packing_type',
        'pack_size',
        'box_size',
        'no_of_boxes',
        'product_type',
        'pack_config_id',
        'branch_id'
    ];

    public function finishedGood()
    {
        return $this->belongsTo(FinishedGood::class, 'finished_good_id');
    }

    public function items()
    {
        return $this->hasMany(BomItem::class, 'bom_master_id');
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type');
    }

    public function packConfig()
    {
        return $this->belongsTo(PackgingType::class, 'pack_config_id');
    }

    public function processes()
    {
        return $this->hasMany(ProductionProcess::class, 'bom_master_id');
    }
    public function branches()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
}
