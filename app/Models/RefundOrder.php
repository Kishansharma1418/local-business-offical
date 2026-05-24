<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
class RefundOrder extends Model
{    use Loggable;
    protected $table = 'refund_orders';

    protected $fillable = [
        'credit_note_id',
        'refund_order_number',
        'customer_id',
        'branch_id',
        'sales_order_id',
        'invoice_order_id',
        'sales_person_id',
        'refund_order_date',
        'amount',
        'balance',
        'status',
        'payment_method',
        'remarks',
        'created_by',
        'updated_by',
        'reference_number',
        'reason_type',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    public function invoice()
    {
        return $this->belongsTo(InvoiceOrder::class, 'invoice_order_id');
    }
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
    public function refundOrderDetails()
    {
        return $this->hasMany(RefundOrderDetail::class, 'refund_order_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function salesPerson()
    {
        return $this->belongsTo(Employee::class, 'sales_person_id');
    }
}
