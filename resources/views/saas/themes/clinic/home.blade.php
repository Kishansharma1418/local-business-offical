@extends('saas.themes._shared.layout')
@section('title', $tenant->business_name)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@include('saas.themes.clinic._styles')
@endpush

@section('content')
<div class="clinic-theme">
<section class="clinic-hero">
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="chip mb-3" style="background:#ccfbf1;color:#0f766e;border-color:#99f6e4;">
                    <i class="fa fa-heart-pulse"></i> {{ $tenant->city ?? 'Jaipur' }} · Patient-first care
                </span>
                <h1 class="display-round mt-2">{{ $tenant->business_name }}</h1>
                <p class="clinic-hero-lead mt-3">{{ $tenant->tagline ?: 'OPD, specialist visits & online consultations — book in minutes, not hours.' }}</p>
                <div class="d-flex gap-2 flex-wrap mt-4">
                    <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-brand btn-lg px-4">View services</a>
                    <a href="{{ route('tenant.contact', $tenant->slug) }}" class="btn btn-brand-outline btn-lg px-4">Contact clinic</a>
                </div>
                <div class="clinic-pill-row">
                    <a href="{{ route('tenant.products', [$tenant->slug, 'specialty' => 'General']) }}" class="clinic-pill"><i class="fa fa-user-doctor"></i>General</a>
                    <a href="{{ route('tenant.products', [$tenant->slug, 'specialty' => 'Dental']) }}" class="clinic-pill"><i class="fa fa-tooth"></i>Dental</a>
                    <a href="{{ route('tenant.products', [$tenant->slug, 'consultation_type' => 'Online']) }}" class="clinic-pill"><i class="fa fa-video"></i>Online</a>
                    <a href="{{ route('tenant.products', [$tenant->slug, 'consultation_type' => 'OPD']) }}" class="clinic-pill"><i class="fa fa-hospital"></i>OPD</a>
                </div>
                <div class="clinic-trust">
                    <div><i class="fa fa-shield-heart"></i> Verified doctors</div>
                    <div><i class="fa fa-clock"></i> Same-day slots</div>
                    <div><i class="fa fa-file-medical"></i> Digital records</div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="clinic-appt-card">
                    <div class="head">
                        <div class="avatar"><i class="fa fa-calendar-check"></i></div>
                        <div>
                            <div class="fw-bold">Book appointment</div>
                            <div class="small text-muted">Next available slots</div>
                        </div>
                    </div>
                    <div class="clinic-slot"><span>Today · OPD</span><strong>10:30 AM</strong></div>
                    <div class="clinic-slot"><span>Today · Online</span><strong>2:00 PM</strong></div>
                    <div class="clinic-slot"><span>Tomorrow · General</span><strong>9:00 AM</strong></div>
                    <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-brand w-100 mt-3 py-3">Choose a service →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="clinic-section clinic-section-alt">
    <div class="container">
        <span class="sec-eyebrow">Specialties</span>
        <h2 class="clinic-section-title">Care for every need</h2>
        <div class="clinic-specialty-grid mt-4">
            <a href="{{ route('tenant.products', [$tenant->slug, 'specialty' => 'General']) }}" class="clinic-spec-tile">
                <div class="ico g"><i class="fa fa-stethoscope"></i></div>
                <strong>General</strong>
            </a>
            <a href="{{ route('tenant.products', [$tenant->slug, 'specialty' => 'Dental']) }}" class="clinic-spec-tile">
                <div class="ico d"><i class="fa fa-tooth"></i></div>
                <strong>Dental</strong>
            </a>
            <a href="{{ route('tenant.products', [$tenant->slug, 'specialty' => 'Pediatric']) }}" class="clinic-spec-tile">
                <div class="ico p"><i class="fa fa-baby"></i></div>
                <strong>Pediatric</strong>
            </a>
            <a href="{{ route('tenant.products', [$tenant->slug, 'consultation_type' => 'Online']) }}" class="clinic-spec-tile">
                <div class="ico c"><i class="fa fa-laptop-medical"></i></div>
                <strong>Online</strong>
            </a>
        </div>
    </div>
</section>

<section class="clinic-section">
    <div class="container">
        <span class="sec-eyebrow">How it works</span>
        <h2 class="clinic-section-title">Three steps to your visit</h2>
        <div class="clinic-steps">
            <div class="clinic-step"><div class="clinic-step-num">1</div><strong>Pick a service</strong><p class="small text-muted mb-0 mt-2">Browse specialties, OPD or online consult.</p></div>
            <div class="clinic-step"><div class="clinic-step-num">2</div><strong>Book & pay</strong><p class="small text-muted mb-0 mt-2">Secure your slot with consultation fee.</p></div>
            <div class="clinic-step"><div class="clinic-step-num">3</div><strong>Visit or video</strong><p class="small text-muted mb-0 mt-2">Come to clinic or join from home.</p></div>
        </div>
    </div>
</section>

@if($featured->count())
<section class="clinic-section clinic-section-alt">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <span class="sec-eyebrow">Popular</span>
                <h2 class="clinic-section-title mb-0">Top services</h2>
            </div>
            <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-brand-outline">All services →</a>
        </div>
        <div class="row g-4">
            @foreach($featured->take(6) as $p)
                @include('saas.themes.clinic.listing_card', ['p' => $p])
            @endforeach
        </div>
    </div>
</section>
@endif
</div>

@include('saas.themes._shared.enquiry_section')
@endsection
