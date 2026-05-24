<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class GstRate extends Model
{
    use Loggable,SoftDeletes;
    
    protected $table="gst_rates";
    protected $fillable=['type',
    'gst_rate_name',
    'cgst_rate',
    'sgst_rate',
    'igst_rate',
    'created_by',
];
}
