<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class SalesOrderApproval extends Model
{
    use HasFactory , Loggable;

    protected $table = 'sales_order_approval';

    protected $fillable = [
        'name',
        'sales_order_id',
        'approved_id',
        'approval_status',
        'remark',
        'action_date',
        'approval_level',
        'created_by',
        'updated_by'
    ];
 
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
   
   
  
}
