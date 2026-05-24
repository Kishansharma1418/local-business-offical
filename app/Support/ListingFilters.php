<?php

namespace App\Support;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ListingFilters
{
    public static function themes(): array
    {
        return config('saas.themes', []);
    }

    public static function themeKeys(): array
    {
        return array_keys(self::themes());
    }

    public static function hasListingFilters(string $theme): bool
    {
        return in_array($theme, ['clinic', 'property'], true);
    }

    public static function listingsLabel(string $theme): string
    {
        return self::themes()[$theme]['listings_label'] ?? 'Shop';
    }

    /** @return array<string, array{label: string, options: array<string, string>}> */
    public static function definitions(string $theme): array
    {
        return config("saas.listing_filters.$theme", []);
    }

    public static function apply(Builder $query, Request $request, string $theme): Builder
    {
        foreach (self::definitions($theme) as $key => $def) {
            $value = $request->input($key);
            if ($value === null || $value === '') {
                continue;
            }
            if ($key === 'location') {
                $query->where('meta->location', 'like', '%' . $value . '%');
                continue;
            }
            $query->where("meta->{$key}", $value);
        }

        return $query;
    }

    /**
     * Distinct values per filter from tenant products (for dropdowns).
     */
    public static function optionsForTenant(Tenant $tenant, string $theme): array
    {
        $defs = self::definitions($theme);
        $out = [];

        $products = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->whereNotNull('meta')
            ->get(['meta']);

        foreach (array_keys($defs) as $key) {
            $values = $products
                ->pluck('meta')
                ->map(fn ($m) => is_array($m) ? ($m[$key] ?? null) : null)
                ->filter()
                ->unique()
                ->sort()
                ->values();

            $out[$key] = $values;
        }

        return $out;
    }

    public static function defaultMeta(string $theme): array
    {
        return match ($theme) {
            'property' => [
                'property_type' => '',
                'purpose'       => '',
                'bhk'           => '',
                'location'      => '',
                'area_sqft'     => '',
            ],
            'clinic' => [
                'specialty'          => '',
                'consultation_type'  => '',
                'duration'           => '',
            ],
            default => [],
        };
    }

    public static function mergeMetaFromRequest(Request $request, string $theme): array
    {
        $meta = self::defaultMeta($theme);
        foreach (array_keys($meta) as $key) {
            if ($request->has($key)) {
                $meta[$key] = $request->input($key, '');
            }
        }
        return array_filter($meta, fn ($v) => $v !== null && $v !== '');
    }
}
