<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table="logs";
    
    protected $fillable=[
        'module_name',
        'action',
        'record_id',
        'perform_ip',
        'perform_device',
        'status',
        'old_data',
        'new_data',
        'user_id'
    ];

    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }
}
