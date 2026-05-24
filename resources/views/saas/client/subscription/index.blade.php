@extends('saas.layouts.client')
@section('title', 'Subscription')

@section('content')
<style>
    .plan-card{border:1px solid rgba(15,23,42,.08);border-radius:16px;padding:26px 22px;background:#fff;transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease;position:relative;height:100%;box-shadow:0 1px 2px rgba(15,23,42,.04);}
    .plan-card:hover{transform:translateY(-2px);box-shadow:0 1px 3px rgba(15,23,42,.05),0 12px 28px -12px rgba(15,23,42,.12);border-color:rgba(99,102,241,.25);}
    .plan-card.popular{border-color:transparent;background:linear-gradient(#fff,#fff) padding-box,linear-gradient(135deg,#6366f1,#8b5cf6) border-box;border:2px solid transparent;}
    .plan-card .popular-badge{position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:linear-gradient(135deg,#6c5ce7,#ff6b9d);color:#fff;padding:5px 14px;border-radius:999px;font-size:.72rem;font-weight:700;letter-spacing:.04em;}
    .plan-card h3{font-size:1.6rem;margin-bottom:4px;}
    .plan-card .price{font-size:2.2rem;font-weight:800;color:#0b1020;}
    .plan-card .price small{font-size:.9rem;color:#64748b;font-weight:500;}
    .plan-card ul{list-style:none;padding:0;margin:18px 0;}
    .plan-card ul li{padding:6px 0;color:#475569;font-size:.9rem;}
    .plan-card ul li i{color:#059669;margin-right:6px;}
    .status-card{background:linear-gradient(145deg,#0f172a,#1e293b);color:#fff;border-radius:16px;padding:26px;margin-bottom:22px;box-shadow:0 1px 3px rgba(15,23,42,.08),0 8px 24px -8px rgba(15,23,42,.15);}
    .status-card .plan-pill{display:inline-block;background:rgba(255,255,255,.1);padding:4px 14px;border-radius:999px;font-size:.78rem;margin-bottom:10px;}
    .status-card h3{font-weight:800;font-size:1.5rem;}
    .status-card .expiry-box{background:rgba(255,255,255,.07);padding:14px 18px;border-radius:12px;margin-top:18px;display:flex;justify-content:space-between;align-items:center;}
    .history-row{border:1px solid #eef0f5;border-radius:12px;padding:14px 18px;margin-bottom:10px;background:#fff;}
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@php
    $isExpired = $tenant && $tenant->isExpired();
    $daysLeft  = $tenant ? (int) $tenant->daysLeft() : 0;
@endphp

<div class="status-card">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <div class="plan-pill">Current Plan</div>
            <h3 class="mb-1">{{ $tenant?->plan?->name ?? 'No plan assigned' }}</h3>
            <div style="opacity:.75;">{{ $tenant?->business_name }}</div>
        </div>
        <div class="text-end">
            <div style="opacity:.75;font-size:.85rem;">Status</div>
            @if($isExpired)
                <div class="badge bg-danger fs-6">Expired</div>
            @elseif($tenant?->status === 'active')
                <div class="badge bg-success fs-6">Active</div>
            @else
                <div class="badge bg-warning text-dark fs-6">{{ ucfirst($tenant?->status ?? 'inactive') }}</div>
            @endif
        </div>
    </div>
    <div class="expiry-box">
        <div>
            <small style="opacity:.7;">Valid till</small>
            <div class="fw-bold">{{ $tenant?->expiry_date ? $tenant->expiry_date->format('d M Y') : '—' }}</div>
        </div>
        <div class="text-end">
            <small style="opacity:.7;">Days remaining</small>
            <div class="fw-bold">{{ $isExpired ? 0 : $daysLeft }}</div>
        </div>
    </div>
    @if($isExpired)
        <div class="alert alert-warning mt-3 mb-0">Your plan has expired. Your public website is temporarily disabled. Choose a plan below to reactivate.</div>
    @elseif($daysLeft <= 7)
        <div class="alert alert-info mt-3 mb-0">Only {{ $daysLeft }} days left. Renew now to avoid downtime.</div>
    @endif
</div>

<h5 class="fw-bold mb-3">Choose a plan</h5>
<div class="row g-3 mb-4">
    @foreach($plans as $i => $plan)
        <div class="col-md-4">
            <div class="plan-card {{ $i === 1 ? 'popular' : '' }}">
                @if($i === 1) <span class="popular-badge">Most Popular</span> @endif
                <h3>{{ $plan->name }}</h3>
                <div class="text-muted small mb-3">{{ $plan->duration_days ?? 30 }} days validity</div>
                <div class="price">₹{{ number_format($plan->price, 0) }}<small> / {{ $plan->duration_days ?? 30 }}d</small></div>
                <ul>
                    <li><i class="ri-check-line"></i> Up to {{ $plan->max_products ?? 'unlimited' }} products</li>
                    @foreach(($plan->features ?? []) as $f)
                        <li><i class="ri-check-line"></i> {{ $f }}</li>
                    @endforeach
                    <li><i class="ri-check-line"></i> WhatsApp order notifications</li>
                </ul>
                <form method="POST" action="{{ route('client.subscription.pay', $plan) }}">
                    @csrf
                    <button class="btn {{ $i === 1 ? 'btn-primary' : 'btn-outline-primary' }} w-100 fw-semibold">
                        <i class="ri-secure-payment-line me-1"></i> Pay with UPI
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>

@if($payments->count())
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h6 class="fw-bold mb-3">Payment history</h6>
        @foreach($payments as $pm)
            <div class="history-row d-flex flex-wrap align-items-center gap-3 justify-content-between">
                <div>
                    <div class="fw-semibold">{{ $pm->reference }} — {{ $pm->plan->name }}</div>
                    <div class="small text-muted">
                        {{ $pm->created_at->format('d M Y, h:i A') }}
                        @if($pm->transaction_id) · UTR: <code>{{ $pm->transaction_id }}</code> @endif
                    </div>
                    @if($pm->status === 'rejected' && $pm->admin_note)
                        <div class="small text-danger mt-1"><i class="ri-error-warning-line"></i> {{ $pm->admin_note }}</div>
                    @endif
                </div>
                <div class="text-end">
                    <div class="fw-bold">₹{{ number_format($pm->amount, 0) }}</div>
                    <span class="badge bg-{{ $pm->statusBadge() }}">{{ str_replace('_',' ', ucfirst($pm->status)) }}</span>
                    @if($pm->status === 'initiated')
                        <div class="mt-1"><a href="{{ route('client.subscription.show', $pm) }}" class="btn btn-sm btn-outline-primary">Continue</a></div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
@endsection
