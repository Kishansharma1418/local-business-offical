<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionApproval extends Model
{
    protected $table = 'production_approvals';
    protected $fillable = [
        'production_id',
        'approver_id',
        'approval_level',
        'decision',
        'remarks',
        'approval_date',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
