<?php

namespace App\Http\Controllers\Saas\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $q = Order::withoutGlobalScopes()->with(['tenant', 'items'])->latest();

        if ($s = $request->get('search')) {
            $q->where(function ($qq) use ($s) {
                $qq->where('order_number', 'like', "%$s%")
                   ->orWhere('customer_name', 'like', "%$s%")
                   ->orWhere('phone', 'like', "%$s%");
            });
        }
        if ($status = $request->get('status')) {
            $q->where('order_status', $status);
        }
        if ($tenantId = $request->get('tenant_id')) {
            $q->where('tenant_id', $tenantId);
        }

        $orders = $q->paginate(20)->withQueryString();
        return view('saas.admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::withoutGlobalScopes()->with(['items', 'tenant'])->findOrFail($id);
        return view('saas.admin.orders.show', compact('order'));
    }
}
