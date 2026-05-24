<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'price',
        'mrp',
        'image',
        'short_description',
        'description',
        'category',
        'meta',
        'stock',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'mrp' => 'decimal:2',
        'meta' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function metaValue(string $key, mixed $default = null): mixed
    {
        return $this->meta[$key] ?? $default;
    }

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . strtolower(Str::random(5));
            }
        });
    }
}
