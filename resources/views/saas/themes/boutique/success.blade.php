@extends('saas.themes._shared.layout')
@section('title', 'Order Placed · ' . $tenant->business_name)

@section('content')
<section class="py-5">
    <div class="container" style="max-width:720px;">
        <div class="bg-white rounded-lux shadow-lux p-5 text-center" style="position:relative;overflow:hidden;">
            <div style="position:absolute;top:-100px;right:-100px;width:300px;height:300px;background:radial-gradient(circle,var(--brand-soft),transparent 70%);border-radius:50%;"></div>
            <div style="width:100px;height:100px;border-radius:50%;background:#d1fae5;color:#059669;display:inline-flex;align-items:center;justify-content:center;margin-bottom:20px;position:relative;">
                <i class="fa fa-check fa-3x"></i>
            </div>
            <span class="sec-eyebrow text-success">Order confirmed</span>
            <h1 class="display-serif mt-2" style="font-size:2.4rem;">Thank you, {{ $order->customer_name }}!</h1>
            <p class="lead text-muted">Your order <b class="text-brand">#{{ $order->order_number }}</b> has been placed successfully.</p>
            <p class="small text-muted">We'll reach out to you on <b>{{ $order->phone }}</b> shortly to confirm.</p>

            <div class="text-start mt-4 pt-4 border-top position-relative">
                <h6 class="fw-bold mb-3">Order summary</h6>
                @foreach($order->items as $item)
                    @php
                        $line = (float) $item->subtotal > 0
                            ? (float) $item->subtotal
                            : round((float) $item->price * (int) $item->quantity, 2);
                    @endphp
                    <div class="d-flex justify-content-between py-2 small border-bottom">
                        <span>
                            {{ $item->product_name }}
                            <span class="text-muted">× {{ $item->quantity }}</span>
                            <span class="d-block text-muted" style="font-size:.75rem;">₹{{ number_format($item->price, 0) }} each</span>
                        </span>
                        <span class="fw-semibold">₹{{ number_format($line, 0) }}</span>
                    </div>
                @endforeach
                <div class="d-flex justify-content-between pt-3 fs-5 fw-bold">
                    <span>Total</span>
                    <span class="text-brand">₹{{ number_format($itemsTotal ?? $order->total_amount, 0) }}</span>
                </div>
                <div class="d-flex justify-content-between small mt-2">
                    <span class="text-muted">Payment method</span>
                    <span class="fw-semibold">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</span>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2 justify-content-center flex-wrap position-relative">
                <a href="{{ route('tenant.home', $tenant->slug) }}" class="btn btn-brand-outline"><i class="fa fa-arrow-left me-1"></i> Back to home</a>
                @if($tenant->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $tenant->whatsapp) }}?text=Hi%2C%20I%20just%20placed%20order%20{{ urlencode($order->order_number) }}" target="_blank" class="btn" style="background:#25d366;color:#fff;"><i class="fab fa-whatsapp me-1"></i> Message seller on WhatsApp</a>
                @endif
            </div>
        </div>

        <div class="text-center mt-4 text-muted small">
            <i class="fa fa-envelope me-1"></i> A confirmation has been saved to your order history.
        </div>
    </div>
</section>
@endsection
