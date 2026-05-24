<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
class Prefix extends Model
{    
    use Loggable;

    protected $table = 'prefixes';
    protected $fillable = [
        'module',
        'prefix',
        'start_from',
        'current_number',
        'separator',
        'status',
    ];
    
}
  