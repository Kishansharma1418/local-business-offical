<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Currency extends Model
{
    use Loggable;
    protected $table = 'currencies';

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'country',
        'currency',
        'code',
        'minor_unit',
        'symbol'
    ];
}
