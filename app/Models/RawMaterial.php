<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;

class RawMaterial extends Model
{
    use SoftDeletes, Loggable;
    protected $table = "rawmaterial";

    protected $fillable = [
        'code',
        'name',
        'hsn_code',
        'branch_id',
        'raw_category_id',
        'sub_rawcategory_id',
        'uom_id',
        'description',
        'lead_time_days',
        'status',
        'created_by',
        'updated_by',
        'stock_new',
        'stock_old',
        'stock_all',
        'specification'
    ];

    public function category()
    {
        return $this->belongsTo(RawCategory::class, 'raw_category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(RawCategory::class, 'sub_rawcategory_id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }
    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
