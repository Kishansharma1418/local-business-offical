<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
class BatchManagement extends Model
{
    use HasFactory,Loggable,SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'batch_number',
        'manufacturing_date',
        'expiry_date',
        'warehouse_id',
        'available_quantity',
        'unit_cost',
        'base_price',
        'gst_percent',
        'mrp',
        'created_by',
        'updated_by',
    ];

    public function product()
    {
        return $this->belongsTo(FinishedGood::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function createdBy()
    {   
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
