@extends('saas.layouts.admin')
@section('title', 'Plans')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0">Subscription Plans</h4><small class="text-muted">Manage pricing tiers</small></div>
    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary"><i class="ri-add-line"></i> New Plan</a>
</div>

<div class="row g-3">
    @forelse($plans as $plan)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1">{{ $plan->name }}</h5>
                            <small class="text-muted">{{ $plan->slug }}</small>
                        </div>
                        <span class="badge badge-soft-{{ $plan->is_active?'success':'danger' }}">{{ $plan->is_active?'Active':'Inactive' }}</span>
                    </div>
                    <div class="my-3">
                        <span class="fs-2 fw-bold text-primary">₹{{ number_format($plan->price,0) }}</span>
                        <small class="text-muted">/ {{ $plan->duration_days }} days</small>
                    </div>
                    <div class="mb-2"><i class="ri-shopping-bag-line"></i> Up to {{ $plan->max_products }} products</div>
                    @if($plan->features)
                        <ul class="list-unstyled small text-muted">
                            @foreach((array)$plan->features as $f)<li><i class="ri-check-line text-success"></i> {{ $f }}</li>@endforeach
                        </ul>
                    @endif
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-sm btn-light flex-fill"><i class="ri-edit-line"></i> Edit</a>
                        <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="flex-fill" onsubmit="return confirm('Delete this plan?');">@csrf @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger w-100"><i class="ri-delete-bin-line"></i> Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-info">No plans yet. <a href="{{ route('admin.plans.create') }}">Create your first plan</a>.</div></div>
    @endforelse
</div>
<div class="mt-3">{{ $plans->links() }}</div>
@endsection
