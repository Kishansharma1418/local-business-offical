<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;
class Setting extends Model
{    use Loggable;
    protected $table = 'settings';
    protected $fillable = [
        'logo',
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'website',
        'favicon',
        'gst',
        'dl_no_1',
        'dl_no_2',
        'policy_no',
        'start_date',
        'end_date',
        'cbn_registration_no',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
