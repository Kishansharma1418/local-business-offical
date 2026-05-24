<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Support\Collection;

class CartService
{
    public function sessionKey(Tenant $tenant): string
    {
        return 'cart_' . $tenant->id;
    }

    public function getRaw(Tenant $tenant): array
    {
        return session($this->sessionKey($tenant), []);
    }

    public function qtyForProduct(array $cart, int $productId): int
    {
        return max(0, (int) ($cart[$productId]['qty'] ?? $cart[(string) $productId]['qty'] ?? 0));
    }

    /**
     * @return array{items: Collection, total: float, count: int}
     */
    public function resolve(Tenant $tenant): array
    {
        $cart = $this->getRaw($tenant);

        if ($cart === []) {
            return ['items' => collect(), 'total' => 0.0, 'count' => 0];
        }

        $ids = array_map('intval', array_keys($cart));

        $items = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->whereIn('id', $ids)
            ->get()
            ->map(function (Product $p) use ($cart) {
                $qty = $this->qtyForProduct($cart, (int) $p->id);
                $unitPrice = (float) $p->price;

                $p->qty = $qty;
                $p->unit_price = $unitPrice;
                $p->line_total = round($unitPrice * $qty, 2);

                return $p;
            })
            ->filter(fn (Product $p) => $p->qty > 0)
            ->values();

        $total = round((float) $items->sum(fn (Product $p) => (float) $p->line_total), 2);
        $count = (int) $items->sum('qty');

        return compact('items', 'total', 'count');
    }

    public function saveQuantities(Tenant $tenant, array $qtyInput): void
    {
        $cart = [];

        foreach ($qtyInput as $productId => $qty) {
            $qty = (int) $qty;
            if ($qty > 0) {
                $cart[(int) $productId] = ['qty' => $qty];
            }
        }

        session([$this->sessionKey($tenant) => $cart]);
    }

    public function clear(Tenant $tenant): void
    {
        session()->forget($this->sessionKey($tenant));
    }
}
