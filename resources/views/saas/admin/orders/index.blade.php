@extends('saas.layouts.admin')
@section('title', 'All Orders')

@section('content')
<h4 class="mb-3">All Orders (All Tenants)</h4>

<div class="card shadow-sm border-0 mb-3"><div class="card-body">
    <form class="row g-2" method="GET">
        <div class="col-md-6"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Order #, customer, phone..."></div>
        <div class="col-md-3"><select name="status" class="form-select">
            <option value="">All Statuses</option>
            @foreach(['new','confirmed','shipped','delivered','cancelled'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>@endforeach
        </select></div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary flex-fill">Filter</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-light">Clear</a>
        </div>
    </form>
</div></div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light"><tr><th>Order #</th><th>Tenant</th><th>Customer</th><th>Items</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr></thead>
            <tbody>
                @forelse($orders as $o)
                    <tr>
                        <td><b>{{ $o->order_number }}</b></td>
                        <td><a href="{{ route('tenant.home', $o->tenant->slug) }}" target="_blank">{{ $o->tenant->business_name }}</a></td>
                        <td>{{ $o->customer_name }}<br><small class="text-muted">{{ $o->phone }}</small></td>
                        <td>{{ $o->items->count() }} item(s)</td>
                        <td>₹{{ number_format($o->total_amount,0) }}</td>
                        <td><span class="badge badge-soft-{{ $o->payment_status==='paid'?'success':'warning' }}">{{ ucfirst($o->payment_status) }}</span><br><small>{{ strtoupper($o->payment_method) }}</small></td>
                        <td><span class="badge badge-soft-info">{{ ucfirst($o->order_status) }}</span></td>
                        <td>{{ $o->created_at->format('d M Y') }}<br><small class="text-muted">{{ $o->created_at->diffForHumans() }}</small></td>
                        <td><a href="{{ route('admin.orders.show', $o->id) }}" class="btn btn-sm btn-light"><i class="ri-eye-line"></i></a></td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $orders->links() }}</div>
</div>
@endsection
