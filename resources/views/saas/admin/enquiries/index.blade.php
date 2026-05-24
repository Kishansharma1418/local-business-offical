@extends('saas.layouts.admin')
@section('title', 'All Enquiries')

@section('content')
<h4 class="mb-3">All Enquiries</h4>

<div class="card shadow-sm border-0 mb-3"><div class="card-body">
    <form class="row g-2" method="GET">
        <div class="col-md-9"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name, phone, email..."></div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary flex-fill">Filter</button>
            <a href="{{ route('admin.enquiries.index') }}" class="btn btn-light">Clear</a>
        </div>
    </form>
</div></div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light"><tr><th>Name</th><th>Tenant</th><th>Phone</th><th>Email</th><th>Message</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($enquiries as $e)
                    <tr>
                        <td><b>{{ $e->name }}</b></td>
                        <td>{{ $e->tenant->business_name ?? '—' }}</td>
                        <td>{{ $e->phone }}</td>
                        <td>{{ $e->email ?: '—' }}</td>
                        <td><span class="text-muted">{{ \Illuminate\Support\Str::limit($e->message, 80) }}</span></td>
                        <td><span class="badge badge-soft-{{ $e->status==='new'?'info':($e->status==='contacted'?'warning':'success') }}">{{ ucfirst($e->status) }}</span></td>
                        <td>{{ $e->created_at->format('d M') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No enquiries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $enquiries->links() }}</div>
</div>
@endsection
