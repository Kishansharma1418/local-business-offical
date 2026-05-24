<?php

namespace App\Http\Controllers\Saas\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
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

    public function show(Request $request, $slug)
    {
        $tenant = $request->attributes->get('tenant');
        if ($r = $this->guardShopMode($tenant)) {
            return $r;
        }

        $resolved = $this->cart->resolve($tenant);
        if ($resolved['items']->isEmpty()) {
            return redirect()->route('tenant.products', $tenant->slug);
        }

        $products = $resolved['items'];
        $total = $resolved['total'];

        $theme = $tenant->theme ?: 'boutique';
        $view = "saas.themes.$theme.checkout";
        if (!view()->exists($view)) {
            $view = 'saas.themes.boutique.checkout';
        }

        return view($view, compact('tenant', 'products', 'total'));
    }

    public function place(Request $request, $slug)
    {
        $tenant = $request->attributes->get('tenant');
        if ($r = $this->guardShopMode($tenant)) {
            return $r;
        }

        $data = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'email'          => 'nullable|email',
            'address'        => 'required|string',
            'payment_method' => 'required|in:cod,online',
            'notes'          => 'nullable|string|max:1000',
        ]);

        $resolved = $this->cart->resolve($tenant);
        if ($resolved['items']->isEmpty()) {
            return redirect()->route('tenant.home', $tenant->slug);
        }

        $order = DB::transaction(function () use ($tenant, $resolved, $data) {
            $lineItems = [];
            $total = 0.0;

            foreach ($resolved['items'] as $p) {
                $qty = (int) $p->qty;
                $unitPrice = (float) $p->price;
                $subtotal = round($unitPrice * $qty, 2);
                $total += $subtotal;
                $lineItems[] = [
                    'product_id'   => $p->id,
                    'product_name' => $p->name,
                    'quantity'     => $qty,
                    'price'        => $unitPrice,
                    'subtotal'     => $subtotal,
                ];
            }

            $total = round($total, 2);

            $order = Order::create([
                'tenant_id'      => $tenant->id,
                'customer_name'  => $data['customer_name'],
                'phone'          => $data['phone'],
                'email'          => $data['email'] ?? null,
                'address'        => $data['address'],
                'total_amount'   => $total,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'order_status'   => 'new',
                'notes'          => $data['notes'] ?? null,
            ]);

            foreach ($lineItems as $row) {
                OrderItem::create(array_merge(['order_id' => $order->id], $row));
            }

            return $order;
        });

        $this->cart->clear($tenant);

        return redirect()->route('tenant.order.success', [$tenant->slug, $order->id]);
    }

    public function success(Request $request, $slug, Order $order)
    {
        $tenant = $request->attributes->get('tenant');
        abort_if($order->tenant_id !== $tenant->id, 404);
        $order->load('items');

        $itemsTotal = round((float) $order->items->sum(fn ($i) => (float) $i->subtotal), 2);

        $theme = $tenant->theme ?: 'boutique';
        $view = "saas.themes.$theme.success";
        if (!view()->exists($view)) {
            $view = 'saas.themes.boutique.success';
        }

        return view($view, compact('tenant', 'order', 'itemsTotal'));
    }
}
