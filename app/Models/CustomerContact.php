<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;
class CustomerContact extends Model
{
    use SoftDeletes,Loggable;
    protected $table="customer_contacts";
    protected $fillable=[
        'customer_id',
        'contact_name',
        'designation',
        'mobile_no',
        'email',
        'is_default',
        'created_by',
    ];
    
}
