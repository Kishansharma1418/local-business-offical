<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;


class PaymentTerms extends Model
{
    use Loggable;
    protected $fillable = [
        'name',
        'days',
        'status',
        'created_by',
        'updated_by'
    ];
}
