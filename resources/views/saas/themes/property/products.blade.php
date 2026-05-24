@extends('saas.themes._shared.layout')
@section('title', 'Properties · ' . $tenant->business_name)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
@include('saas.themes.property._styles')
@endpush

@section('content')
<div class="prop-theme">
<section class="prop-page-hero">
    <div class="container">
        <nav class="prop-breadcrumb">
            <a href="{{ route('tenant.home', $tenant->slug) }}">Home</a>
            <span>/</span>
            <span>Properties</span>
        </nav>
        <h1 class="display-serif">Properties in {{ $tenant->city ?? 'Jaipur' }}</h1>
        <p class="prop-page-lead">Sale & rent — filter by BHK, locality and type.</p>
    </div>
</section>

<section class="prop-section" style="padding-top:48px;">
    <div class="container">
        @include('saas.themes.property.listing_filters')

        @if($products->count())
            <p class="prop-results-count"><strong>{{ $products->total() }}</strong> properties match your search</p>
            <div class="row g-4">
                @foreach($products as $p)
                    @include('saas.themes.property.listing_card', ['p' => $p])
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">{{ $products->links() }}</div>
        @else
            <div class="prop-empty">
                <div class="prop-empty-icon"><i class="fa fa-building"></i></div>
                <h3>No properties found</h3>
                <p>Try changing filters or browse all listings.</p>
                <a href="{{ route('tenant.products', $tenant->slug) }}" class="prop-btn-primary">View all properties</a>
            </div>
        @endif
    </div>
</section>
</div>
@endsection
