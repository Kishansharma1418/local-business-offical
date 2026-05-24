@extends('saas.themes._shared.layout')
@section('title', $product->name)

@php $meta = $product->meta ?? []; @endphp

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@include('saas.themes.clinic._styles')
@endpush

@section('content')
<div class="clinic-theme">
<section class="clinic-section" style="padding-top:48px;">
    <div class="container">
        <nav class="small text-muted mb-4">
            <a href="{{ route('tenant.home', $tenant->slug) }}">Home</a> /
            <a href="{{ route('tenant.products', $tenant->slug) }}">Services</a> /
            <span>{{ $product->name }}</span>
        </nav>

        <div class="clinic-detail-layout">
            <aside class="clinic-detail-side">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="" class="rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;border:4px solid rgba(255,255,255,.3);">
                @else
                    <div class="mb-3" style="width:120px;height:120px;margin:0 auto;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:3rem;"><i class="fa fa-user-doctor"></i></div>
                @endif
                <div class="small opacity-75">Consultation fee</div>
                <div class="fee">₹{{ number_format($product->price, 0) }}</div>
                @if($tenant->isShopMode())
                <form method="POST" action="{{ route('tenant.cart.add', $tenant->slug) }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button class="btn btn-light w-100 py-3 fw-bold" style="border-radius:12px;color:var(--brand);">
                        <i class="fa fa-calendar-plus me-2"></i>Book now
                    </button>
                </form>
                @endif
                <ul class="clinic-detail-facts">
                    @if(!empty($meta['specialty']))<li><i class="fa fa-stethoscope"></i>{{ $meta['specialty'] }}</li>@endif
                    @if(!empty($meta['consultation_type']))<li><i class="fa fa-video"></i>{{ $meta['consultation_type'] }}</li>@endif
                    @if(!empty($meta['duration']))<li><i class="fa fa-clock"></i>{{ $meta['duration'] }}</li>@endif
                </ul>
            </aside>

            <div>
                @if(!empty($meta['specialty']))
                    <span class="clinic-card-badge mb-3 d-inline-block">{{ $meta['specialty'] }}</span>
                @endif
                <h1 class="display-round" style="font-size:2.2rem;">{{ $product->name }}</h1>
                @if($product->short_description)
                    <p class="lead text-muted">{{ $product->short_description }}</p>
                @endif
                @if($product->description)
                    <div class="mb-4" style="line-height:1.85;color:#475569;">{!! nl2br(e($product->description)) !!}</div>
                @endif
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('tenant.contact', $tenant->slug) }}" class="btn btn-brand-outline btn-lg">Ask before booking</a>
                    @if($tenant->phone)
                        <a href="tel:{{ $tenant->phone }}" class="btn btn-outline-secondary btn-lg"><i class="fa fa-phone me-2"></i>Call clinic</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
