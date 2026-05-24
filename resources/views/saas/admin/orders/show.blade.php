@extends('saas.layouts.admin')
@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Order #{{ $order->order_number }}</h4>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-light"><i class="ri-arrow-left-line"></i> Back</a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0"><h5 class="mb-0">Items</h5></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="table-light"><tr><th>Product</th><th>Qty</th><th>Price</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td class="text-end">₹{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-light"><th colspan="3" class="text-end">Total</th><th class="text-end">₹{{ number_format($order->total_amount, 2) }}</th></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-3"><div class="card-body">
            <h6 class="mb-3">Customer</h6>
            <div><b>{{ $order->customer_name }}</b></div>
            <div>{{ $order->phone }}</div>
            <div>{{ $order->email ?: '—' }}</div>
            <hr>
            <small class="text-muted d-block">Address</small>
            <div>{{ $order->address ?: '—' }}</div>
            @if($order->notes)<hr><small class="text-muted">Notes</small><div>{{ $order->notes }}</div>@endif
        </div></div>
        <div class="card shadow-sm border-0"><div class="card-body">
            <h6 class="mb-3">Meta</h6>
            <div>Tenant: <b>{{ $order->tenant->business_name }}</b></div>
            <div>Payment: <b>{{ strtoupper($order->payment_method) }}</b> ({{ ucfirst($order->payment_status) }})</div>
            <div>Status: <b>{{ ucfirst($order->order_status) }}</b></div>
            <div>Placed: {{ $order->created_at->format('d M Y, h:i A') }}</div>
        </div></div>
    </div>
</div>
@endsection
