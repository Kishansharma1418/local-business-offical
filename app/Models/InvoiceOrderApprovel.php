<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class InvoiceOrderApprovel extends Model
{
    use Loggable;
    protected $table = "invoice_order_approvels";

    protected $fillable = [
        'invoice_order_id',
        'approver_id',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];
}
