<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubscriptionPayment extends Model
{
    protected $fillable = [
        'reference',
        'tenant_id',
        'plan_id',
        'amount',
        'upi_id',
        'upi_app',
        'transaction_id',
        'screenshot',
        'client_note',
        'admin_note',
        'status',
        'new_expiry_date',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'new_expiry_date' => 'date',
        'verified_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($m) {
            if (empty($m->reference)) {
                $m->reference = 'SUB-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'initiated' => 'warning',
            'pending_verification' => 'info',
            'verified' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }
}
