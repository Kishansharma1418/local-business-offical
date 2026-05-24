<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{
    protected $fillable = [
        'business_name',
        'slug',
        'phone',
        'email',
        'whatsapp',
        'address',
        'city',
        'logo',
        'banner',
        'tagline',
        'about',
        'theme',
        'website_mode',
        'primary_color',
        'background_color',
        'text_color',
        'accent_color',
        'plan_id',
        'status',
        'expiry_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function ($tenant) {
            if (empty($tenant->slug)) {
                $tenant->slug = Str::slug($tenant->business_name);
            }
        });
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function subscriptionPayments()
    {
        return $this->hasMany(SubscriptionPayment::class)->latest();
    }

    public function isShopMode(): bool
    {
        return ($this->website_mode ?? 'shop') === 'shop';
    }

    /** At least one admin-verified subscription payment exists. */
    public function hasVerifiedPayment(): bool
    {
        return $this->subscriptionPayments()
            ->where('status', 'verified')
            ->exists();
    }

    /** Payment submitted, waiting for admin to verify UTR. */
    public function hasPendingPaymentVerification(): bool
    {
        return $this->subscriptionPayments()
            ->whereIn('status', ['initiated', 'pending_verification'])
            ->exists();
    }

    /**
     * Full client panel: products, pages, settings, orders management, etc.
     */
    public function canManageContent(): bool
    {
        return $this->isActive() && $this->hasVerifiedPayment();
    }

    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }
        if ($this->expiry_date && $this->expiry_date->isPast()) {
            return false;
        }
        return true;
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function daysLeft(): int
    {
        if (!$this->expiry_date) {
            return 0;
        }
        return max(0, now()->startOfDay()->diffInDays($this->expiry_date, false));
    }
}
