<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class FinishedGood extends Model
{
    use SoftDeletes,Loggable;
    protected $table ="finished_goods";

    protected $fillable=[
            'code',
            'name',
            'hsn_code',
            'category_id',
            'sub_category_id',
            'uom_id',
            'description',
            'record_level',
            'total_qty',
            'lead_time_days',
            'status',
            'unit_cost',
            'base_price',
            'gst_percent',
            'mrp',
            'created_by',
            'updated_by',
            'branch_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class,'sub_category_id');
    }
    

    public function productDetail()
    {
        return $this->hasOne(ProductDetail::class, 'finished_goods_id');
    }

     public function uoms()
    {
        return $this->belongsTo(Uom::class,'uom_id');
    }
    public function batchDetail()
    {
        return $this->hasOne(BatchManagement::class, 'finished_goods_id');
    }

    public function batchManagements()
    {
        return $this->hasMany(BatchManagement::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    protected static function booted()
    {
        static::created(function ($product) {
            $overallDiscounts = \App\Models\CustomerProductDiscount::where('discount_type', 'overall')->get();

            foreach ($overallDiscounts as $discount) {
                \App\Models\CustomerProductDiscount::create([
                    'customer_id' => $discount->customer_id,
                    'finish_goods_id' => $product->id,
                    'discount_percent' => $discount->discount_percent,
                    'discount_type' => 'specific',
                ]);
            }
        });
    }

    public function getDiscounts()
    {
        return $this->hasMany(CustomerProductDiscount::class, 'finish_goods_id');
    }

     public function branches()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
    
    public function gatrates()
    {
        return $this->belongsTo(GstRate::class,'gst_rate_name');
    }
}