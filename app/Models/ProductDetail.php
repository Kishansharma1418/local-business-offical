<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class ProductDetail extends Model
{
    use Loggable;

    protected $table="product_details";

    protected $fillable=[
        'finished_goods_id',
        'composition',
        'strength_specification',
        'packing_type',
        'pack_size',
        'brand',
        'country_origin',
        'storage_condation',
        'shelf_life_months',
    ];


    public function finishedGoods()
    {
        return $this->belongsTo(FinishedGood::class,'finished_goods_id');
    }


}
