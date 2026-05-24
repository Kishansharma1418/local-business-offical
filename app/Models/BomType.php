<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;


class BomType extends Model
{
    use HasFactory, Loggable;

    protected $table = 'bom_types';

    protected $fillable = [
        'name',
        'code',
        'description',
        'order_no',
        'status',
        'created_by',
        'updated_by',
    ];
}