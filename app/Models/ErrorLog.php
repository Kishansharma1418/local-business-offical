<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $table="error_logs";
    protected $fillable=['module_name','error_code','error_msg','function_name','request_ip','device_info','record_id','user_id','status','record_id','error_url'];


    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
