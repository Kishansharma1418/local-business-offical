<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;
class PurchaseOrder extends Model
{  
    use SoftDeletes,Loggable;
    protected $fillable = [
        'po_number',
        'po_date',
        'vendor_id',
        'broker_id',
        'branch_id',
        'currency_id',
        'delivery_date',
        'payment_term_id',
        'delivery_term',
        'status',
        'total_amount',
        'tax_amount',
        'discount_amount',
        'discount_percent',
        'net_amount',
        'notes',
        'created_by',
        'updated_by',
        'approved_by',
        'expected_delivery_date',
        'invoice_file',
        'invoice_number',
    ];


     const DRAFT = 'draft';
    const APPROVED = 'approved';
    const SENT = 'sent';
    const ACCEPTED = 'accepted';
    const PARTIAL = 'partialreceived';
    const COMPLETED = 'completed';
    const REJECTED = 'rejected';

    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function broker()
    {
        return $this->belongsTo(Broker::class, 'broker_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
    
    public function paymentTerm()
    {
        return $this->belongsTo(PaymentTerms::class, 'payment_term_id');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'purchase_order_id');
    }
}
