@extends('saas.themes._shared.layout')
@section('title', $product->name)

@php $meta = $product->meta ?? []; @endphp

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
@include('saas.themes.property._styles')
@endpush

@section('content')
<div class="prop-theme">
<section class="prop-detail-hero">
    <div class="container">
        <nav class="prop-breadcrumb">
            <a href="{{ route('tenant.home', $tenant->slug) }}">Home</a><span>/</span>
            <a href="{{ route('tenant.products', $tenant->slug) }}">Properties</a><span>/</span>
            <span>{{ Str::limit($product->name, 40) }}</span>
        </nav>
    </div>
</section>

<section class="prop-section" style="padding-top:40px;">
    <div class="container">
        <div class="prop-detail-grid">
            <div class="prop-detail-gallery">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
                @else
                    <div class="prop-card-placeholder" style="height:100%;"><i class="fa fa-building"></i></div>
                @endif
            </div>
            <div>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @if(!empty($meta['purpose']))
                        <span class="prop-tag prop-tag-{{ strtolower($meta['purpose']) === 'rent' ? 'rent' : 'sale' }}" style="position:static;display:inline-block;">{{ $meta['purpose'] }}</span>
                    @endif
                    @if(!empty($meta['property_type']))<span class="badge rounded-pill bg-light text-dark border px-3 py-2">{{ $meta['property_type'] }}</span>@endif
                    @if(!empty($meta['bhk']))<span class="badge rounded-pill bg-light text-dark border px-3 py-2">{{ $meta['bhk'] }} BHK</span>@endif
                </div>
                <h1 class="display-serif" style="font-size:2.2rem;">{{ $product->name }}</h1>
                @if(!empty($meta['location']))
                    <p class="text-muted mb-3"><i class="fa fa-location-dot text-brand me-2"></i>{{ $meta['location'] }}@if($tenant->city), {{ $tenant->city }}@endif</p>
                @endif
                <div class="prop-detail-price mb-2">
                    ₹{{ number_format($product->price, 0) }}
                    @if(($meta['purpose'] ?? '') === 'Rent')<span class="fs-5 text-muted fw-normal"> / month</span>@endif
                </div>

                <div class="prop-detail-specs">
                    @if(!empty($meta['area_sqft']))
                        <div class="prop-spec"><div class="k">Built-up area</div><div class="v">{{ $meta['area_sqft'] }} sq.ft</div></div>
                    @endif
                    @if(!empty($meta['bhk']))
                        <div class="prop-spec"><div class="k">Configuration</div><div class="v">{{ $meta['bhk'] }} BHK</div></div>
                    @endif
                    @if(!empty($meta['property_type']))
                        <div class="prop-spec"><div class="k">Type</div><div class="v">{{ $meta['property_type'] }}</div></div>
                    @endif
                    @if(!empty($meta['purpose']))
                        <div class="prop-spec"><div class="k">Listing</div><div class="v">For {{ $meta['purpose'] }}</div></div>
                    @endif
                </div>

                @if($product->description)
                    <div class="mb-4" style="line-height:1.8;color:#475569;">{!! nl2br(e($product->description)) !!}</div>
                @endif

                <div class="d-flex flex-column gap-2">
                    @if($tenant->isShopMode())
                    <form method="POST" action="{{ route('tenant.cart.add', $tenant->slug) }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button class="prop-btn-primary w-100 justify-content-center py-3"><i class="fa fa-calendar-days me-2"></i>Book site visit</button>
                    </form>
                    @endif
                    @if($tenant->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/\D/','',$tenant->whatsapp) }}?text={{ urlencode('Interested in: '.$product->name) }}" target="_blank" class="btn btn-lg w-100" style="background:#25d366;color:#fff;border-radius:12px;">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp agent
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
