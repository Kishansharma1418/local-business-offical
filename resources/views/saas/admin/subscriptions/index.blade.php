@extends('saas.layouts.admin')
@section('title', 'UPI Payments')

@section('content')
<style>
    .tab-pill{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:999px;font-size:.85rem;font-weight:600;color:#475569;background:#fff;border:1px solid #eef0f5;text-decoration:none;transition:.2s;}
    .tab-pill:hover{border-color:#c7d2fe;color:#6c5ce7;}
    .tab-pill.active{background:linear-gradient(135deg,#6366f1,#7c3aed);color:#fff;border-color:transparent;box-shadow:0 2px 6px -2px rgba(99,102,241,.35);}
    .tab-pill .cnt{background:rgba(255,255,255,.2);padding:1px 8px;border-radius:999px;font-size:.7rem;}
    .tab-pill:not(.active) .cnt{background:#f1f5f9;color:#475569;}
</style>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h4 class="mb-1 fw-bold">UPI Subscription Payments</h4>
        <div class="text-muted small">Verify client payments received on UPI ID <code>{{ config('saas.upi.id') }}</code></div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="d-flex flex-wrap gap-2 mb-3">
    @php
        $tabs = [
            'pending_verification' => ['Awaiting verification', $counts['pending_verification']],
            'verified'             => ['Verified',              $counts['verified']],
            'rejected'             => ['Rejected',              $counts['rejected']],
            'initiated'            => ['Initiated (no UTR yet)',$counts['initiated']],
            'all'                  => ['All',                   array_sum($counts)],
        ];
    @endphp
    @foreach($tabs as $k => [$label, $c])
        <a href="{{ route('admin.subscriptions.index', ['status' => $k]) }}" class="tab-pill {{ $status===$k ? 'active' : '' }}">
            {{ $label }} <span class="cnt">{{ $c }}</span>
        </a>
    @endforeach
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Tenant</th>
                        <th>Plan</th>
                        <th class="text-end">Amount</th>
                        <th>UTR</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($payments as $p)
                    <tr>
                        <td><code class="small">{{ $p->reference }}</code></td>
                        <td>
                            <div class="fw-semibold">{{ $p->tenant->business_name }}</div>
                            <div class="small text-muted">{{ $p->tenant->phone }}</div>
                        </td>
                        <td>{{ $p->plan->name }}</td>
                        <td class="text-end fw-semibold">₹{{ number_format($p->amount, 0) }}</td>
                        <td>@if($p->transaction_id)<code class="small">{{ $p->transaction_id }}</code>@else<span class="text-muted">—</span>@endif</td>
                        <td><span class="badge bg-{{ $p->statusBadge() }}">{{ str_replace('_',' ', ucfirst($p->status)) }}</span></td>
                        <td class="small text-muted">{{ $p->created_at->diffForHumans() }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.subscriptions.show', $p) }}" class="btn btn-sm btn-outline-primary">Review</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No payments in this bucket.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $payments->links() }}</div>
@endsection
