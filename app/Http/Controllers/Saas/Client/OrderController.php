<?php

namespace App\Http\Controllers\Saas\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Saas\Client\Concerns\EnsuresClientPaidAccess;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use EnsuresClientPaidAccess;

    public function index(Request $request)
    {
        $q = Order::with('items')->latest();
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
        $orders = $q->paginate(20)->withQueryString();
        return view('saas.client.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('saas.client.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->ensurePaidAccess();
        $data = $request->validate([
            'order_status'   => 'required|in:new,confirmed,shipped,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
        ]);
        $order->update($data);
        return back()->with('success', 'Order status updated.');
    }
}
