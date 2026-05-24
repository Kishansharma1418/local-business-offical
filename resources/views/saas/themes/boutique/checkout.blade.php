@extends('saas.themes._shared.layout')
@section('title', 'Checkout · ' . $tenant->business_name)

@push('styles')
<style>
.pay-option{border:2px solid #e5e7eb;padding:16px;border-radius:14px;cursor:pointer;transition:all .22s;height:100%;display:flex;gap:12px;align-items:flex-start;}
.pay-option:hover{border-color:var(--brand);}
.pay-option.sel{border-color:var(--brand);background:var(--brand-soft);}
.pay-option input{margin-top:3px;}
.pay-option .ico{width:40px;height:40px;border-radius:10px;background:#fff;display:flex;align-items:center;justify-content:center;color:var(--brand);flex-shrink:0;}
.check-steps{display:flex;align-items:center;gap:8px;justify-content:center;margin-bottom:40px;color:var(--muted);}
.check-steps .step{display:flex;align-items:center;gap:8px;}
.check-steps .dot{width:28px;height:28px;border-radius:50%;background:#e5e7eb;color:#6b7280;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;}
.check-steps .step.done .dot{background:var(--brand);color:#fff;}
.check-steps .step.done{color:var(--ink);font-weight:600;}
.check-steps .line{width:40px;height:2px;background:#e5e7eb;}
.check-steps .line.done{background:var(--brand);}
</style>
@endpush

@section('content')
<section class="py-5">
    <div class="container">
        <div class="check-steps">
            <div class="step done"><div class="dot"><i class="fa fa-check"></i></div>Cart</div>
            <div class="line done"></div>
            <div class="step done"><div class="dot">2</div>Checkout</div>
            <div class="line"></div>
            <div class="step"><div class="dot">3</div>Confirmation</div>
        </div>

        <form method="POST" action="{{ route('tenant.checkout.place', $tenant->slug) }}">@csrf
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="bg-white rounded-lux shadow-lux p-4 mb-4">
                        <h5 class="fw-bold mb-3"><i class="fa fa-user text-brand me-2"></i>Delivery Details</h5>
                        <div class="row g-3">
                            <div class="col-md-12"><label class="form-label small fw-semibold">Full name *</label>
                                <input type="text" name="customer_name" class="form-control" placeholder="e.g. Aarti Gupta" required></div>
                            <div class="col-md-6"><label class="form-label small fw-semibold">Phone *</label>
                                <input type="text" name="phone" class="form-control" placeholder="+91 98765 43210" required></div>
                            <div class="col-md-6"><label class="form-label small fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Optional"></div>
                            <div class="col-md-12"><label class="form-label small fw-semibold">Delivery address *</label>
                                <textarea name="address" rows="3" class="form-control" placeholder="House no, street, area, city, pincode" required></textarea></div>
                            <div class="col-md-12"><label class="form-label small fw-semibold">Order notes <span class="text-muted">(optional)</span></label>
                                <textarea name="notes" rows="2" class="form-control" placeholder="Anything we should know?"></textarea></div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lux shadow-lux p-4">
                        <h5 class="fw-bold mb-3"><i class="fa fa-credit-card text-brand me-2"></i>Payment Method</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="pay-option sel">
                                    <input type="radio" name="payment_method" value="cod" checked>
                                    <div class="ico"><i class="fa fa-money-bill-wave"></i></div>
                                    <div><b>Cash on Delivery</b><div class="small text-muted mt-1">Pay when you receive the order</div></div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="pay-option">
                                    <input type="radio" name="payment_method" value="online">
                                    <div class="ico"><i class="fa fa-mobile-screen"></i></div>
                                    <div><b>Pay Online</b><div class="small text-muted mt-1">UPI · Cards · Netbanking</div></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="bg-white rounded-lux shadow-lux p-4 sticky-top" style="top:90px;">
                        <h5 class="fw-bold mb-3">Order Summary</h5>
                        @foreach($products as $p)
                            <div class="d-flex justify-content-between py-2 small">
                                <div class="text-truncate" style="max-width:220px;">
                                    <b>{{ $p->name }}</b>
                                    <span class="text-muted">× {{ $p->qty }}</span>
                                    <span class="d-block text-muted" style="font-size:.75rem;">₹{{ number_format($p->price, 0) }} each</span>
                                </div>
                                <span class="fw-semibold">₹{{ number_format($p->line_total, 0) }}</span>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between mb-1 small"><span class="text-muted">Subtotal</span><span>₹{{ number_format($total,0) }}</span></div>
                        <div class="d-flex justify-content-between mb-1 small"><span class="text-muted">Shipping</span><span class="text-success fw-semibold">FREE</span></div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5 mb-3"><span>Total</span><span class="text-brand">₹{{ number_format($total,0) }}</span></div>
                        <button class="btn btn-brand btn-lg w-100"><i class="fa fa-lock me-2"></i>Place Order</button>
                        <div class="text-center small text-muted mt-3">
                            <i class="fa fa-shield-halved text-success me-1"></i> Secure checkout
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
document.querySelectorAll('.pay-option').forEach(el => {
    el.addEventListener('click', () => {
        document.querySelectorAll('.pay-option').forEach(x => x.classList.remove('sel'));
        el.classList.add('sel');
        el.querySelector('input').checked = true;
    });
});
</script>
@endsection
