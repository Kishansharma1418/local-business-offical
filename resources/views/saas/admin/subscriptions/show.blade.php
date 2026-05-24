@extends('saas.layouts.admin')
@section('title', 'Review Payment')

@section('content')
<style>
    .info-card{background:#fff;border-radius:14px;padding:20px;box-shadow:0 1px 2px rgba(15,23,42,.04),0 6px 16px -8px rgba(15,23,42,.1);border:1px solid rgba(15,23,42,.08);height:100%;}
    .info-card h6{letter-spacing:.04em;text-transform:uppercase;font-size:.7rem;color:#64748b;}
    .kv{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px dashed #eef0f5;}
    .kv:last-child{border-bottom:0;}
    .kv .k{color:#64748b;font-size:.85rem;}
    .kv .v{font-weight:600;}
    .screenshot-thumb{max-width:100%;border-radius:12px;border:1px solid #eef0f5;}
</style>

<div class="mb-3"><a href="{{ route('admin.subscriptions.index') }}" class="small text-decoration-none"><i class="ri-arrow-left-line"></i> Back to payments</a></div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex flex-wrap align-items-center justify-content-between mb-3 gap-2">
    <div>
        <h4 class="fw-bold mb-1">Payment {{ $payment->reference }}</h4>
        <div class="text-muted small">Submitted {{ $payment->created_at->format('d M Y, h:i A') }}</div>
    </div>
    <span class="badge bg-{{ $payment->statusBadge() }} fs-6 px-3 py-2">{{ str_replace('_',' ', ucfirst($payment->status)) }}</span>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="info-card">
            <h6 class="mb-3">Tenant</h6>
            <div class="d-flex align-items-center gap-3 mb-3">
                <div style="width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#6c5ce7,#a855f7);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">
                    {{ strtoupper(substr($payment->tenant->business_name,0,2)) }}
                </div>
                <div>
                    <div class="fw-bold">{{ $payment->tenant->business_name }}</div>
                    <div class="small text-muted">{{ $payment->tenant->slug }}</div>
                </div>
            </div>
            <div class="kv"><span class="k">Phone</span><span class="v">{{ $payment->tenant->phone ?: '—' }}</span></div>
            <div class="kv"><span class="k">Email</span><span class="v">{{ $payment->tenant->email ?: '—' }}</span></div>
            <div class="kv"><span class="k">Current plan</span><span class="v">{{ $payment->tenant->plan?->name ?: '—' }}</span></div>
            <div class="kv"><span class="k">Expires on</span><span class="v">{{ $payment->tenant->expiry_date?->format('d M Y') ?: '—' }}</span></div>
            <div class="kv"><span class="k">Status</span><span class="v">{{ ucfirst($payment->tenant->status) }}</span></div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="info-card">
            <h6 class="mb-3">Payment details</h6>
            <div class="kv"><span class="k">Reference</span><span class="v"><code>{{ $payment->reference }}</code></span></div>
            <div class="kv"><span class="k">Plan</span><span class="v">{{ $payment->plan->name }} ({{ $payment->plan->duration_days }}d)</span></div>
            <div class="kv"><span class="k">Amount</span><span class="v">₹{{ number_format($payment->amount, 2) }}</span></div>
            <div class="kv"><span class="k">UPI ID</span><span class="v"><code>{{ $payment->upi_id }}</code></span></div>
            <div class="kv"><span class="k">App used</span><span class="v">{{ $payment->upi_app ?: '—' }}</span></div>
            <div class="kv"><span class="k">UTR / Txn ID</span><span class="v">@if($payment->transaction_id)<code>{{ $payment->transaction_id }}</code>@else<span class="text-muted">Not submitted</span>@endif</span></div>
            @if($payment->client_note)
                <div class="mt-2"><small class="text-muted">Client note:</small><div class="small">{{ $payment->client_note }}</div></div>
            @endif
            @if($payment->verified_at)
                <div class="kv mt-2"><span class="k">Processed at</span><span class="v small">{{ $payment->verified_at->format('d M Y, h:i A') }}</span></div>
                <div class="kv"><span class="k">By</span><span class="v small">{{ $payment->verifier?->name ?: '—' }}</span></div>
            @endif
            @if($payment->admin_note)
                <div class="mt-2"><small class="text-muted">Admin note:</small><div class="small">{{ $payment->admin_note }}</div></div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="info-card">
            <h6 class="mb-3">Proof / Screenshot</h6>
            @if($payment->screenshot)
                <a href="{{ asset('storage/'.$payment->screenshot) }}" target="_blank">
                    <img src="{{ asset('storage/'.$payment->screenshot) }}" alt="proof" class="screenshot-thumb">
                </a>
                <div class="small text-muted mt-2">Click image to view full size.</div>
            @else
                <div class="text-muted small">No screenshot uploaded by the client.</div>
            @endif

            <hr>
            <h6 class="mt-3 mb-2">How to verify</h6>
            <ol class="small text-muted ps-3">
                <li>Open your UPI app / bank statement.</li>
                <li>Search for UTR <code>{{ $payment->transaction_id ?: '—' }}</code>.</li>
                <li>Match amount <strong>₹{{ number_format($payment->amount, 0) }}</strong>.</li>
                <li>Verify to extend tenant plan, or reject with reason.</li>
            </ol>
        </div>
    </div>
</div>

@if(!in_array($payment->status, ['verified','rejected']))
<div class="row g-3 mt-2">
    <div class="col-md-6">
        <div class="info-card" style="border:1px solid #a7f3d0;background:#f0fdf4;">
            <h6 style="color:#065f46;">Approve &amp; activate</h6>
            <form method="POST" action="{{ route('admin.subscriptions.verify', $payment) }}">
                @csrf
                <div class="mb-2">
                    <label class="form-label small">Internal note (optional)</label>
                    <textarea name="admin_note" rows="2" class="form-control" placeholder="e.g. UTR matched on 23 Apr"></textarea>
                </div>
                <button class="btn btn-success w-100 fw-semibold" onclick="return confirm('Approve this payment and extend plan by {{ $payment->plan->duration_days }} days?')">
                    <i class="ri-checkbox-circle-line"></i> Verify &amp; extend plan
                </button>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <div class="info-card" style="border:1px solid #fecaca;background:#fef2f2;">
            <h6 style="color:#991b1b;">Reject</h6>
            <form method="POST" action="{{ route('admin.subscriptions.reject', $payment) }}">
                @csrf
                <div class="mb-2">
                    <label class="form-label small">Reason (shown to client) *</label>
                    <textarea name="admin_note" rows="2" class="form-control" required placeholder="e.g. UTR not found in bank, please reshare"></textarea>
                </div>
                <button class="btn btn-danger w-100 fw-semibold">
                    <i class="ri-close-circle-line"></i> Reject payment
                </button>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
