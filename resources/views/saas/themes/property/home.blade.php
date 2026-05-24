@extends('saas.themes._shared.layout')
@section('title', $tenant->business_name)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@include('saas.themes.property._styles')
<style>
.prop-hero{position:relative;min-height:92vh;display:flex;align-items:center;background:#0c1222;color:#fff;overflow:hidden;}
.prop-hero::before{content:'';position:absolute;inset:0;background:
    radial-gradient(ellipse 80% 60% at 70% 20%, rgba(5,150,105,.35), transparent),
    radial-gradient(ellipse 50% 40% at 10% 80%, rgba(245,158,11,.12), transparent),
    linear-gradient(180deg, #0c1222 0%, #111827 100%);}
.prop-hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);background-size:48px 48px;mask-image:linear-gradient(180deg,#000 30%,transparent);}
.prop-hero-inner{position:relative;z-index:2;}
.prop-hero h1{font-size:clamp(2.6rem,5.5vw,4.5rem);line-height:1.02;margin-bottom:1.25rem;}
.prop-hero h1 em{font-style:italic;color:#6ee7b7;}
.prop-hero-lead{color:#94a3b8;font-size:1.12rem;max-width:480px;line-height:1.75;}
.prop-hero-actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:2rem;}
.prop-btn-primary{background:linear-gradient(135deg,#059669,#10b981);color:#fff;border:0;padding:14px 28px;border-radius:12px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:8px;box-shadow:0 12px 32px -8px rgba(16,185,129,.5);}
.prop-btn-ghost{border:1px solid rgba(255,255,255,.25);color:#fff;padding:14px 28px;border-radius:12px;font-weight:600;text-decoration:none;background:rgba(255,255,255,.05);backdrop-filter:blur(8px);}
.prop-hero-stats{display:flex;gap:2.5rem;margin-top:3rem;padding-top:2rem;border-top:1px solid rgba(255,255,255,.1);}
.prop-hero-stats .n{font-size:1.75rem;font-weight:800;color:#fff;}
.prop-hero-stats .l{font-size:.8rem;color:#64748b;text-transform:uppercase;letter-spacing:.08em;}

.prop-search-float{background:#fff;border-radius:20px;padding:28px;color:#0f172a;box-shadow:0 40px 80px -20px rgba(0,0,0,.5);}
.prop-search-float h3{font-family:'DM Serif Display',serif;font-size:1.5rem;margin-bottom:1rem;}
.prop-quick{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.prop-quick a{padding:12px 14px;border-radius:12px;background:#f8fafc;border:1px solid #e2e8f0;text-decoration:none;color:#0f172a;font-weight:600;font-size:.88rem;transition:.2s;text-align:center;}
.prop-quick a:hover{border-color:var(--brand);background:var(--brand-soft);color:var(--brand);}
.prop-quick a span{display:block;font-size:.72rem;color:#64748b;font-weight:500;margin-top:2px;}

.prop-mock{position:relative;border-radius:20px;overflow:hidden;aspect-ratio:4/5;background:linear-gradient(145deg,#1e293b,#0f172a);border:1px solid rgba(255,255,255,.08);}
.prop-mock-card{position:absolute;background:#fff;color:#0f172a;border-radius:14px;padding:14px 18px;box-shadow:0 20px 50px rgba(0,0,0,.25);max-width:200px;}
.prop-mock-card.c1{bottom:24px;left:20px;}
.prop-mock-card.c2{top:24px;right:20px;}
.prop-mock-card .price{font-size:1.25rem;font-weight:800;color:var(--brand);}
.prop-mock-card .loc{font-size:.8rem;color:#64748b;}

.prop-section{padding:90px 0;}
.prop-section-dark{background:#f8fafc;}
.prop-section-title{font-family:'DM Serif Display',serif;font-size:clamp(2rem,4vw,2.8rem);margin-bottom:.5rem;}
.prop-types{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:16px;margin-top:2rem;}
.prop-type-card{text-align:center;padding:28px 16px;border-radius:16px;background:#fff;border:1px solid #e2e8f0;text-decoration:none;color:inherit;transition:.25s;}
.prop-type-card:hover{transform:translateY(-6px);box-shadow:0 24px 48px -16px rgba(15,23,42,.15);border-color:transparent;}
.prop-type-card i{font-size:2rem;color:var(--brand);margin-bottom:12px;display:block;}
.prop-type-card strong{display:block;font-size:.95rem;}
</style>
@endpush

@section('content')
<div class="prop-theme">
<section class="prop-hero">
    <div class="prop-hero-grid"></div>
    <div class="container prop-hero-inner">
        <div class="row align-items-center g-5 py-5">
            <div class="col-lg-6">
                <span class="chip mb-3" style="background:rgba(255,255,255,.08);color:#6ee7b7;border-color:rgba(255,255,255,.15);">
                    <i class="fa fa-location-dot"></i> {{ $tenant->city ?? 'Jaipur' }} · Premium listings
                </span>
                <h1 class="display-serif">Your next address in <em>{{ $tenant->city ?? 'Jaipur' }}</em></h1>
                <p class="prop-hero-lead">{{ $tenant->tagline ?: 'Curated homes for sale and rent — villas, flats and commercial spaces with transparent pricing.' }}</p>
                <div class="prop-hero-actions">
                    <a href="{{ route('tenant.products', $tenant->slug) }}" class="prop-btn-primary"><i class="fa fa-compass"></i> Explore listings</a>
                    <a href="{{ route('tenant.contact', $tenant->slug) }}" class="prop-btn-ghost"><i class="fa fa-phone"></i> Talk to agent</a>
                </div>
                <div class="prop-hero-stats">
                    <div><div class="n">{{ max($featured->count() + $latest->count(), 1) }}+</div><div class="l">Active listings</div></div>
                    <div><div class="n">24h</div><div class="l">Site visits</div></div>
                    <div><div class="n">100%</div><div class="l">Verified ads</div></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="prop-mock d-none d-lg-block">
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                        <i class="fa fa-city fa-6x" style="color:rgba(255,255,255,.08);"></i>
                    </div>
                    <div class="prop-mock-card c1">
                        <div class="loc">Malviya Nagar</div>
                        <div class="price">₹45 Lac+</div>
                        <div class="loc mt-1">3 BHK · For Sale</div>
                    </div>
                    <div class="prop-mock-card c2">
                        <div class="loc">C-Scheme</div>
                        <div class="price">₹18k/mo</div>
                        <div class="loc mt-1">1 BHK · Rent</div>
                    </div>
                </div>
                <div class="prop-search-float mt-4 mt-lg-0">
                    <h3>Quick search</h3>
                    <div class="prop-quick">
                        <a href="{{ route('tenant.products', [$tenant->slug, 'purpose' => 'Sale']) }}">For Sale<span>Buy property</span></a>
                        <a href="{{ route('tenant.products', [$tenant->slug, 'purpose' => 'Rent']) }}">For Rent<span>Monthly lease</span></a>
                        <a href="{{ route('tenant.products', [$tenant->slug, 'property_type' => 'Flat']) }}">Flats<span>Apartments</span></a>
                        <a href="{{ route('tenant.products', [$tenant->slug, 'property_type' => 'Villa']) }}">Villas<span>Independent</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="prop-section prop-section-dark">
    <div class="container">
        <div class="text-center mb-2">
            <span class="sec-eyebrow">Browse by type</span>
            <h2 class="prop-section-title">What are you looking for?</h2>
        </div>
        <div class="prop-types">
            <a href="{{ route('tenant.products', [$tenant->slug, 'property_type' => 'Flat']) }}" class="prop-type-card"><i class="fa fa-building"></i><strong>Flats</strong></a>
            <a href="{{ route('tenant.products', [$tenant->slug, 'property_type' => 'Villa']) }}" class="prop-type-card"><i class="fa fa-house-chimney"></i><strong>Villas</strong></a>
            <a href="{{ route('tenant.products', [$tenant->slug, 'property_type' => 'Plot']) }}" class="prop-type-card"><i class="fa fa-map"></i><strong>Plots</strong></a>
            <a href="{{ route('tenant.products', [$tenant->slug, 'property_type' => 'Commercial']) }}" class="prop-type-card"><i class="fa fa-store"></i><strong>Commercial</strong></a>
        </div>
    </div>
</section>

@if($featured->count())
<section class="prop-section">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <span class="sec-eyebrow">Handpicked</span>
                <h2 class="prop-section-title mb-0">Featured properties</h2>
            </div>
            <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-brand-outline">View all →</a>
        </div>
        <div class="row g-4">
            @foreach($featured->take(6) as $p)
                @include('saas.themes.property.listing_card', ['p' => $p])
            @endforeach
        </div>
    </div>
</section>
@endif
</div>

@include('saas.themes._shared.enquiry_section')
@endsection
