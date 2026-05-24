@extends('saas.themes._shared.layout')
@section('title', 'Services · ' . $tenant->business_name)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@include('saas.themes.clinic._styles')
@endpush

@section('content')
<div class="clinic-theme">
<section class="clinic-page-head">
    <div class="container text-center">
        <span class="sec-eyebrow">Our services</span>
        <h1 class="display-round">Treatments & consultations</h1>
        <p class="text-muted mb-0">Filter by specialty, type and session length.</p>
    </div>
</section>

<section class="clinic-section" style="padding-top:40px;">
    <div class="container">
        @include('saas.themes.clinic.listing_filters')

        @if($products->count())
            <p class="text-muted mb-4"><strong>{{ $products->total() }}</strong> services available</p>
            <div class="row g-4">
                @foreach($products as $p)
                    @include('saas.themes.clinic.listing_card', ['p' => $p])
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">{{ $products->links() }}</div>
        @else
            <div class="clinic-empty">
                <i class="fa fa-stethoscope fa-3x mb-3" style="color:var(--brand);opacity:.5;"></i>
                <h4>No services match</h4>
                <p class="text-muted">Try clearing filters or browse all services.</p>
                <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-brand mt-2">View all</a>
            </div>
        @endif
    </div>
</section>
</div>
@endsection
