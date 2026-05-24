@extends('saas.layouts.admin')
@section('title', 'Tenants')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0">Tenants (Client Businesses)</h4><small class="text-muted">Manage all businesses using your platform</small></div>
    <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary"><i class="ri-add-line"></i> New Tenant</a>
</div>

<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">
        <form class="row g-2" method="GET">
            <div class="col-md-6"><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search business, phone, email..."></div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" @selected(request('status')==='active')>Active</option>
                    <option value="inactive" @selected(request('status')==='inactive')>Inactive</option>
                    <option value="suspended" @selected(request('status')==='suspended')>Suspended</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-fill">Filter</button>
                <a href="{{ route('admin.tenants.index') }}" class="btn btn-light">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Business</th><th>Slug / Website</th><th>Plan</th><th>Theme</th><th>Expiry</th><th>Status</th><th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $t)
                    <tr>
                        <td><b>{{ $t->business_name }}</b><br><small class="text-muted">{{ $t->phone }}</small></td>
                        <td>
                            <code>{{ $t->slug }}</code>
                            <a href="{{ route('tenant.home', $t->slug) }}" target="_blank" class="ms-1"><i class="ri-external-link-line"></i></a>
                        </td>
                        <td>{{ optional($t->plan)->name ?? '—' }}</td>
                        <td><span class="badge bg-light text-dark">{{ ucfirst($t->theme) }}</span></td>
                        <td>
                            @if($t->expiry_date)
                                @if($t->isExpired())
                                    <span class="badge badge-soft-danger">Expired</span>
                                @else
                                    {{ $t->expiry_date->format('d M Y') }}<br><small class="text-muted">({{ $t->daysLeft() }} days left)</small>
                                @endif
                            @else — @endif
                        </td>
                        <td>
                            <span class="badge badge-soft-{{ $t->status==='active'?'success':($t->status==='suspended'?'warning':'danger') }}">{{ ucfirst($t->status) }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.tenants.edit', $t) }}" class="btn btn-sm btn-light" title="Edit"><i class="ri-edit-line"></i></a>
                            <form action="{{ route('admin.tenants.toggle', $t) }}" method="POST" class="d-inline">@csrf
                                <button class="btn btn-sm btn-light" title="Toggle Active"><i class="ri-toggle-line"></i></button>
                            </form>
                            <form action="{{ route('admin.tenants.extend', $t) }}" method="POST" class="d-inline" onsubmit="return confirm('Extend by 30 days?');">@csrf
                                <input type="hidden" name="days" value="30">
                                <button class="btn btn-sm btn-light" title="+30 days"><i class="ri-calendar-line"></i></button>
                            </form>
                            <form action="{{ route('admin.tenants.destroy', $t) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this tenant and all its data?');">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger" title="Delete"><i class="ri-delete-bin-line"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No tenants yet. <a href="{{ route('admin.tenants.create') }}">Create your first tenant</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white">{{ $tenants->links() }}</div>
</div>
@endsection
