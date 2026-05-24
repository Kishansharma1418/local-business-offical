<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProductionProcessItem extends Model
{
    protected $table = 'production_process_items';
    protected $fillable = [
        'production_process_id',
        'bom_item_id',
        'qty',
        'uom',
        'roles',
        'created_by',
        'updated_by',
    ];

    public function process()
    {
        return $this->belongsTo(ProductionProcess::class, 'production_process_id');
    }

    public function bomItem()
    {
        return $this->belongsTo(BomItem::class, 'bom_item_id');
    }

    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'roles', 'id');
    }
  
}
