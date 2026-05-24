<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionProcess extends Model
{
    protected $table = 'production_processes';
    protected $fillable = [
        'production_id',
        'bom_master_id',
        'bom_type_id',
        'bom_type_name',
        'process_step',
        'description',
        'sequence',
        'status',
        'created_by',
        'updated_by',

    ];

    public function items()
    {
        return $this->hasMany(ProductionProcessItem::class);
    }

    public function bomType()
    {
        return $this->belongsTo(BomType::class);
    }


}
