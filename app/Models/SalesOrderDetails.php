<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use App\Traits\Loggable;

    class SalesOrderDetails extends Model
    {
        use HasFactory ,Loggable;

        protected $table = 'sales_order_details';

        protected $fillable = [
            'sales_order_id',
            'product_id',
            'product_name',
            'batch_id',
            'quantity_ordered',
            'quantity_delivered',
            'quantity_pending',
            'unit_price',
            'discount_percent',
            'discount_amount',
            'gst_percent',
            'gst_amount',
            'total_amount',
            'expiry_date',
            'manufacturing_date',
            'status',
            'created_by',
            'updated_by',
            'overall_bill_discount_amount'
        ];

    
        public function createdBy()
        {
            return $this->belongsTo(User::class, 'created_by');
        }

        public function updatedBy()
        {
            return $this->belongsTo(User::class, 'updated_by');
        }
    
        public function product()
        {
            return $this->belongsTo(FinishedGood::class, 'product_id');
        }

    }
