@extends('saas.layouts.client')
@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Order #{{ $order->order_number }}</h4>
    <a href="{{ route('client.orders.index') }}" class="btn btn-light"><i class="ri-arrow-left-line"></i> Back</a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white border-0"><h5 class="mb-0">Items</h5></div>
            <div class="table-responsive"><table class="table mb-0">
                <thead class="table-light"><tr><th>Product</th><th>Qty</th><th>Price</th><th class="text-end">Subtotal</th></tr></thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr><td>{{ $item->product_name }}</td><td>{{ $item->quantity }}</td><td>₹{{ number_format($item->price, 2) }}</td><td class="text-end">₹{{ number_format($item->subtotal, 2) }}</td></tr>
                    @endforeach
                    <tr class="table-light"><th colspan="3" class="text-end">Total</th><th class="text-end">₹{{ number_format($order->total_amount, 2) }}</th></tr>
                </tbody>
            </table></div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0"><h5 class="mb-0">Update Status</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('client.orders.status', $order) }}" class="row g-2">@csrf
                    <div class="col-md-5">
                        <label class="form-label">Order Status</label>
                        <select name="order_status" class="form-select">
                            @foreach(['new','confirmed','shipped','delivered','cancelled'] as $s)
                                <option value="{{ $s }}" @selected($order->order_status===$s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select">
                            @foreach(['pending','paid','failed','refunded'] as $s)
                                <option value="{{ $s }}" @selected($order->payment_status===$s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end"><button class="btn btn-primary w-100">Update</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3"><div class="card-body">
            <h6 class="mb-3">Customer</h6>
            <div><b>{{ $order->customer_name }}</b></div>
            <div>{{ $order->phone }}</div>
            <div>{{ $order->email ?: '—' }}</div>
            <hr>
            <small class="text-muted d-block">Address</small>
            <div>{{ $order->address ?: '—' }}</div>
            <div class="mt-3">
                @php $wa = preg_replace('/\D/', '', $order->phone); @endphp
                <a href="https://wa.me/{{ $wa }}?text=Hi%20{{ urlencode($order->customer_name) }}%2C%20your%20order%20{{ urlencode($order->order_number) }}%20status:%20{{ urlencode(ucfirst($order->order_status)) }}" target="_blank" class="btn btn-success w-100">
                    <i class="ri-whatsapp-line"></i> Message on WhatsApp
                </a>
            </div>
        </div></div>
    </div>
</div>
@endsection
