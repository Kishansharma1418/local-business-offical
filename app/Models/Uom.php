<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;


class Uom extends Model 
{
    use SoftDeletes,Loggable;

    protected $table="uoms";
    protected $filable=['name',
    'status',
    'description',
    'created_by',
];

}
