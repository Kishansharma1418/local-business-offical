<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
use App\Traits\Loggable;

class BankDetail extends Model
{
  use HasFactory, SoftDeletes,Loggable;

    protected $fillable = [
        'name',
        'category',
        'status',
        "created_by",
    ];
}
