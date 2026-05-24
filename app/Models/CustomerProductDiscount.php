<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class CustomerProductDiscount extends Model
{
    use Loggable;
    protected $table="customer_product_discounts";

    protected $fillable = [
        'customer_id',
        'finish_goods_id',
        'discount_percent',
        'discount_type',
        'valid_from',
        'valid_to',
        'created_by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function finishedGoods()
    {
        return $this->belongsTo(FinishedGood::class);
    }
}
