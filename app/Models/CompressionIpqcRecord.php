<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompressionIpqcRecord extends Model
{
    protected $table = 'compression_ipqc_records';
    
    protected $fillable = [
        'production_flow_id',
        'bom_master_id',
        'product_id',
        'product_name',
        'batch_no',
        'datetime',
        'weight20',
        'dt',
        'hardness',
        'friability',
        'thickness',
        'sign_date',
        'remarks',
        'inspected_by',
        'total_weight_uncoated',
        'total_weight_rejected'
    ];

    protected $casts = [
        'datetime' => 'array',
        'weight20' => 'array',
        'dt' => 'array',
        'hardness' => 'array',
        'friability' => 'array',
        'thickness' => 'array',
        'sign_date' => 'array',
        'inspected_by' => 'array',
        'remarks' => 'array'
    ];
}
