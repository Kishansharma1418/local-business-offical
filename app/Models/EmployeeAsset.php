<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeAsset extends Model
{
    use SoftDeletes;
    protected $table = 'employee_asset'; 
    protected $fillable = [
        'name',
        'employee_id',
        'code',
        'asset_type',
        'serial_number',
        'start_date',
        'end_date',
        'imei_number',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function employee()
{
    return $this->belongsTo(Employee::class);
}
}