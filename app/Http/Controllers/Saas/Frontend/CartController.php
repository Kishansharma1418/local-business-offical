<?php

namespace App\Http\Controllers\Saas\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

/**
 * Session-based shopping cart scoped per tenant.
 * Cart key: cart_{tenant_id}
 */
class CartController extends Controller
{
    public function __construct(protected CartService $cart)
    {
    }

    protected function guardShopMode($tenant)
    {
        if ($tenant && !$tenant->isShopMode()) {
            return redirect()->route('tenant.home', $tenant->slug);
        }
        return null;
    }

    public function view(Request $request, $slug)
    {
        $tenant = $request->attributes->get('tenant');
        if ($r = $this->guardShopMode($tenant)) {
            return $r;
        }

        $resolved = $this->cart->resolve($tenant);
        $products = $resolved['items'];
        $total = $resolved['total'];

        $theme = $tenant->theme ?: 'boutique';
        $view = "saas.themes.$theme.cart";
        if (!view()->exists($view)) {
            $view = 'saas.themes.boutique.cart';
        }

        return view($view, compact('tenant', 'products', 'total'));
    }

    public function add(Request $request, $slug)
    {
        $tenant = $request->attributes->get('tenant');
        if ($r = $this->guardShopMode($tenant)) {
            return $r;
        }

        $data = $request->validate([
            'product_id' => 'required|integer',
            'qty'        => 'nullable|integer|min:1',
        ]);

        $product = Product::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->findOrFail($data['product_id']);

        $key = $this->cart->sessionKey($tenant);
        $cart = $this->cart->getRaw($tenant);
        $qty = (int) ($data['qty'] ?? 1);
        $pid = (int) $product->id;

        if (isset($cart[$pid])) {
            $cart[$pid]['qty'] += $qty;
        } else {
            $cart[$pid] = ['qty' => $qty];
        }

        session([$key => $cart]);

        return redirect()->route('tenant.cart', $tenant->slug)->with('success', 'Added to cart.');
    }

    public function update(Request $request, $slug)
    {
        $tenant = $request->attributes->get('tenant');
        if ($r = $this->guardShopMode($tenant)) {
            return $r;
        }

        $this->cart->saveQuantities($tenant, $request->input('qty', []));
        $resolved = $this->cart->resolve($tenant);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok'    => true,
                'total' => $resolved['total'],
                'count' => $resolved['count'],
                'items' => $resolved['items']->map(fn ($p) => [
                    'id'         => $p->id,
                    'qty'        => $p->qty,
                    'unit_price' => $p->unit_price,
                    'line_total' => $p->line_total,
                ])->values(),
            ]);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request, $slug, $productId)
    {
        $tenant = $request->attributes->get('tenant');
        $key = $this->cart->sessionKey($tenant);
        $cart = $this->cart->getRaw($tenant);
        unset($cart[(int) $productId]);
        session([$key => $cart]);

        if ($request->expectsJson() || $request->ajax()) {
            $resolved = $this->cart->resolve($tenant);
            return response()->json([
                'ok'    => true,
                'total' => $resolved['total'],
                'count' => $resolved['count'],
            ]);
        }

        return back()->with('success', 'Removed from cart.');
    }

    public function clear(Request $request, $slug)
    {
        $tenant = $request->attributes->get('tenant');
        $this->cart->clear($tenant);
        return back();
    }
}
