<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderApprovel extends Model
{
    protected $table = 'purchase_order_approvels';

    protected $fillable = [
        'purchase_order_id',
        'approver_id',
        'status',
        'comments',
        'created_by',
        'updated_by',
    ];
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
