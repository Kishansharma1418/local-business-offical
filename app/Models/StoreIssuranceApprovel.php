<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreIssuranceApprovel extends Model
{
    protected $table = 'store_issurance_approvels';
    protected $fillable = [
        'store_issurance_id',
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
