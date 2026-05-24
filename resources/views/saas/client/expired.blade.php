@extends('saas.layouts.client')
@section('title', 'Subscription Expired')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0">
            <div class="card-body p-4 p-md-5 text-center">
                <div class="mb-3" style="font-size:3.5rem;line-height:1;">⚠️</div>
                <h2 class="fw-bold mb-2">Plan expired</h2>
                <p class="text-muted mb-3">
                    Your subscription has ended. Products, orders and your public website are paused until you renew.
                </p>
                @if($tenant && $tenant->expiry_date)
                    <p class="small text-muted">Expired on <strong>{{ $tenant->expiry_date->format('d M Y') }}</strong></p>
                @endif
                <div class="d-flex flex-wrap gap-2 justify-content-center mt-4">
                    <a href="{{ route('client.subscription.index') }}" class="btn btn-primary px-4">
                        <i class="ri-refresh-line me-1"></i> Renew with UPI
                    </a>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button type="submit" class="btn btn-outline-secondary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
