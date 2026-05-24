<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;


class ProductType extends Model
{
    use HasFactory, Loggable;

    protected $table = 'product_types';

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];
}