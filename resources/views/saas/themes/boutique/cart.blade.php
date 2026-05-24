@extends('saas.themes._shared.layout')
@section('title', 'Your Cart · ' . $tenant->business_name)

@section('content')
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-eyebrow">Bag</span>
            <h1 class="display-serif">Your shopping cart</h1>
        </div>

        @if(count($products) === 0)
            <div class="text-center py-5" style="background:#fff;border-radius:20px;box-shadow:0 8px 30px -12px rgba(0,0,0,.08);">
                <i class="fa fa-bag-shopping fa-3x text-muted mb-3" style="opacity:.35;"></i>
                <h4>Your bag is empty</h4>
                <p class="text-muted">Looks like you haven't picked anything yet.</p>
                <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-brand"><i class="fa fa-arrow-left me-2"></i>Browse the collection</a>
            </div>
        @else
            <form method="POST" action="{{ route('tenant.cart.update', $tenant->slug) }}">@csrf
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="bg-white rounded-lux shadow-lux overflow-hidden">
                            @foreach($products as $i => $p)
                                <div class="d-flex align-items-center gap-3 p-3 {{ $i ? 'border-top' : '' }}"
                                     data-cart-row
                                     data-product-id="{{ $p->id }}"
                                     data-unit-price="{{ $p->price }}">
                                    <div style="width:80px;height:100px;border-radius:10px;overflow:hidden;background:var(--brand-soft);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        @if($p->image)
                                            <img src="{{ asset('storage/' . $p->image) }}" style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            <span style="font-family:'DM Serif Display',serif;color:var(--brand);font-size:1.5rem;opacity:.4;">{{ strtoupper(substr($p->name,0,2)) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold">{{ $p->name }}</div>
                                        @if($p->category)<small class="text-muted d-block">{{ $p->category }}</small>@endif
                                        <div class="text-brand fw-bold mt-1">₹{{ number_format($p->price,0) }}</div>
                                    </div>
                                    <div class="d-flex align-items-center border rounded-3 px-2 cart-qty-stepper" style="background:#f7f8fa;">
                                        <button type="button" class="btn btn-sm p-1 cart-qty-minus" aria-label="Decrease quantity"><i class="fa fa-minus" style="font-size:.7rem;"></i></button>
                                        <input type="number" name="qty[{{ $p->id }}]" value="{{ $p->qty }}" min="0"
                                               class="form-control form-control-sm border-0 text-center bg-transparent cart-qty-input"
                                               data-cart-qty style="width:50px;">
                                        <button type="button" class="btn btn-sm p-1 cart-qty-plus" aria-label="Increase quantity"><i class="fa fa-plus" style="font-size:.7rem;"></i></button>
                                    </div>
                                    <div class="text-end" style="min-width:80px;">
                                        <div class="fw-bold cart-line-total" data-cart-line-total>₹{{ number_format($p->line_total,0) }}</div>
                                    </div>
                                    <a href="#" onclick="event.preventDefault();document.getElementById('rm{{ $p->id }}').submit();" class="text-danger ms-2" title="Remove"><i class="fa fa-trash"></i></a>
                                    <form id="rm{{ $p->id }}" method="POST" action="{{ route('tenant.cart.remove', [$tenant->slug, $p->id]) }}" class="d-none">@csrf</form>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-3 d-flex gap-2">
                            <button type="submit" class="btn btn-brand-outline btn-sm"><i class="fa fa-refresh me-1"></i>Update cart</button>
                            <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-sm btn-link text-muted">← Continue shopping</a>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="bg-white rounded-lux shadow-lux p-4 sticky-top" style="top:90px;">
                            <h5 class="fw-bold mb-4">Order summary</h5>
                            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal</span><span class="fw-semibold" id="cart-subtotal">₹{{ number_format($total,0) }}</span></div>
                            <div class="d-flex justify-content-between mb-2"><span class="text-muted">Shipping</span><span class="text-success fw-semibold">FREE</span></div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3"><strong>Total</strong><strong class="text-brand fs-4" id="cart-total">₹{{ number_format($total,0) }}</strong></div>
                            <a href="{{ route('tenant.checkout', $tenant->slug) }}" id="cart-checkout-btn" class="btn btn-brand w-100 btn-lg"><i class="fa fa-lock me-2"></i>Secure Checkout</a>
                            <div class="text-center small text-muted mt-3">
                                <i class="fa fa-shield-halved me-1"></i> Your information is safe with us
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    const updateUrl = @json(route('tenant.cart.update', $tenant->slug));
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || document.querySelector('input[name="_token"]')?.value;

    function formatInr(amount) {
        return '₹' + Math.round(amount).toLocaleString('en-IN');
    }

    function qtyFromInput(input) {
        const v = parseInt(input.value, 10);
        return Number.isFinite(v) && v > 0 ? v : 0;
    }

    function applyServerTotals(data) {
        if (!data || !data.items) return;
        data.items.forEach(function (item) {
            const row = document.querySelector('[data-cart-row][data-product-id="' + item.id + '"]');
            if (!row) return;
            const lineEl = row.querySelector('[data-cart-line-total]');
            if (lineEl) lineEl.textContent = formatInr(item.line_total);
        });
        const subEl = document.getElementById('cart-subtotal');
        const totalEl = document.getElementById('cart-total');
        if (subEl) subEl.textContent = formatInr(data.total);
        if (totalEl) totalEl.textContent = formatInr(data.total);
    }

    function recalcCart() {
        let subtotal = 0;
        document.querySelectorAll('[data-cart-row]').forEach(function (row) {
            const unit = parseFloat(row.getAttribute('data-unit-price')) || 0;
            const input = row.querySelector('[data-cart-qty]');
            const qty = qtyFromInput(input);
            const line = unit * qty;
            const lineEl = row.querySelector('[data-cart-line-total]');
            if (lineEl) lineEl.textContent = formatInr(line);
            subtotal += line;
        });
        const subEl = document.getElementById('cart-subtotal');
        const totalEl = document.getElementById('cart-total');
        if (subEl) subEl.textContent = formatInr(subtotal);
        if (totalEl) totalEl.textContent = formatInr(subtotal);
    }

    let syncTimer = null;
    let syncInFlight = null;

    function buildQtyFormData() {
        const fd = new FormData();
        fd.append('_token', csrf);
        document.querySelectorAll('[data-cart-qty]').forEach(function (input) {
            fd.append(input.getAttribute('name'), input.value);
        });
        return fd;
    }

    function syncCartToServer() {
        return new Promise(function (resolve) {
            clearTimeout(syncTimer);
            syncTimer = setTimeout(function () {
                syncInFlight = fetch(updateUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: buildQtyFormData(),
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        applyServerTotals(data);
                        resolve(data);
                    })
                    .catch(function () { resolve(null); })
                    .finally(function () { syncInFlight = null; });
            }, 350);
        });
    }

    function syncCartNow() {
        clearTimeout(syncTimer);
        if (syncInFlight) return syncInFlight;
        syncInFlight = fetch(updateUrl, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: buildQtyFormData(),
        })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                applyServerTotals(data);
                return data;
            })
            .finally(function () { syncInFlight = null; });
        return syncInFlight;
    }

    function onQtyChange() {
        recalcCart();
        syncCartToServer();
    }

    document.querySelectorAll('.cart-qty-stepper').forEach(function (wrap) {
        const input = wrap.querySelector('.cart-qty-input');
        const minus = wrap.querySelector('.cart-qty-minus');
        const plus = wrap.querySelector('.cart-qty-plus');
        if (!input) return;

        minus && minus.addEventListener('click', function () {
            input.value = Math.max(0, qtyFromInput(input) - 1);
            onQtyChange();
        });
        plus && plus.addEventListener('click', function () {
            input.value = qtyFromInput(input) + 1;
            onQtyChange();
        });
        input.addEventListener('input', onQtyChange);
        input.addEventListener('change', onQtyChange);
    });

    const checkoutBtn = document.getElementById('cart-checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const href = checkoutBtn.getAttribute('href');
            syncCartNow().finally(function () {
                window.location.href = href;
            });
        });
    }

    recalcCart();
})();
</script>
@endpush
