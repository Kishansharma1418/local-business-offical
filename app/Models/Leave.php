<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;


class Leave extends Model
{
    use HasFactory , Loggable;

    protected $table = 'leaves';

    protected $fillable = [
        'employee_id',
        'leave_category',
        'leave_type',
        'start_date',
        'end_date',
        'total_days',
        'status',
        'description',
         'reason',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_days' => 'integer',
    ];

protected static function booted()
{
    static::saving(function ($leave) {
        if ($leave->start_date && $leave->end_date) {
            $leave->total_days = $leave->start_date->diffInDays($leave->end_date) + 1;
        }
    });
}
  public function getDateRangeAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->format('d M Y') . ' - ' . $this->end_date->format('d M Y');
        }
        return '-';
    }

    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function employee()
{
    return $this->belongsTo(Employee::class, 'employee_id');
}

}
