@extends('saas.layouts.client')
@section('title', 'Activate Subscription')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0">
            <div class="card-body p-4 p-md-5 text-center">
                <div class="mb-3" style="font-size:3.5rem;line-height:1;">🔒</div>
                <h2 class="fw-bold mb-2">Dashboard locked</h2>
                <p class="text-muted mb-4" style="max-width:480px;margin:0 auto;">
                    Until your subscription payment is verified, you cannot add products, edit pages, or change settings.
                    Your public website also stays offline.
                </p>

                @if(session('warning'))
                    <div class="alert alert-warning text-start">{{ session('warning') }}</div>
                @endif

                @if($pending)
                    <div class="alert alert-info text-start">
                        <strong>Payment submitted.</strong> Reference <code>{{ $pending->reference }}</code>
                        @if($pending->status === 'pending_verification')
                            — waiting for admin to verify your UTR. You will get full access within a few hours.
                        @else
                            — complete payment and submit your UTR.
                        @endif
                        <div class="mt-2">
                            <a href="{{ route('client.subscription.show', $pending) }}" class="btn btn-sm btn-outline-primary">Continue payment</a>
                        </div>
                    </div>
                @endif

                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="{{ route('client.subscription.index') }}" class="btn btn-primary px-4">
                        <i class="ri-secure-payment-line me-1"></i> Pay with UPI &amp; activate
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
