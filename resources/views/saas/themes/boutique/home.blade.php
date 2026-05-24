@extends('saas.themes._shared.layout')

@push('styles')
<style>
.boutique-hero{position:relative;min-height:86vh;display:flex;align-items:center;overflow:hidden;background:linear-gradient(135deg,#fdf2f8 0%,#fce4ec 40%,#fff 100%);}
.boutique-hero::before{content:'';position:absolute;top:-200px;right:-150px;width:500px;height:500px;background:radial-gradient(circle,var(--brand-soft),transparent 60%);border-radius:50%;}
.boutique-hero .ornament{position:absolute;opacity:.15;color:var(--brand);}
.boutique-hero .ornament.tl{top:40px;left:40px;font-size:3rem;}
.boutique-hero .ornament.br{bottom:40px;right:40px;font-size:4rem;}
.boutique-hero h1{font-family:'Playfair Display',serif;font-size:clamp(2.4rem,5.5vw,4.8rem);font-weight:800;line-height:1.02;letter-spacing:-.02em;}
.boutique-hero .lead{font-size:1.12rem;color:#6b7280;max-width:500px;line-height:1.7;}
.hero-art{position:relative;height:540px;border-radius:28px;background:linear-gradient(135deg,var(--brand) 0%,#4a148c 100%);display:flex;align-items:center;justify-content:center;overflow:hidden;box-shadow:0 30px 80px -20px rgba(0,0,0,.25);}
.hero-art::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 L60 30 L30 60 L0 30 Z' fill='%23fff' fill-opacity='0.06'/%3E%3C/svg%3E");}
.hero-art .hero-icon{font-family:'DM Serif Display',serif;font-size:9rem;color:#fff;opacity:.2;}
.hero-tag{position:absolute;background:#fff;border-radius:16px;padding:16px 22px;box-shadow:0 14px 40px rgba(0,0,0,.15);display:flex;align-items:center;gap:12px;}
.hero-tag.one{top:60px;left:-30px;}
.hero-tag.two{bottom:80px;right:-30px;}
.hero-tag .ico{width:42px;height:42px;border-radius:10px;background:var(--brand-soft);color:var(--brand);display:flex;align-items:center;justify-content:center;font-size:1.2rem;}
.cat-card{position:relative;border-radius:20px;overflow:hidden;height:220px;display:flex;align-items:flex-end;padding:22px;color:#fff;text-decoration:none;background:linear-gradient(180deg,transparent 40%,rgba(0,0,0,.75) 100%);transition:all .3s;}
.cat-card::before{content:'';position:absolute;inset:0;z-index:-1;background:linear-gradient(135deg,var(--brand),#4a148c);transition:transform .5s;}
.cat-card:hover::before{transform:scale(1.1);}
.cat-card:hover{color:#fff;box-shadow:0 22px 50px -10px rgba(0,0,0,.3);}
.cat-card h5{font-family:'Playfair Display',serif;font-weight:700;color:#fff;margin:0;}
.cat-card small{color:rgba(255,255,255,.85);}
.promo-strip{background:var(--ink);color:#fff;padding:16px 0;text-align:center;font-weight:500;font-size:.92rem;letter-spacing:.02em;}
.promo-strip span{color:var(--brand);font-weight:700;}
.testi-lux{background:var(--brand-soft);border-radius:24px;padding:60px 40px;margin:60px 0;text-align:center;}
.testi-lux .quote{font-family:'Playfair Display',serif;font-size:1.8rem;font-style:italic;color:var(--ink);max-width:720px;margin:0 auto;line-height:1.4;}
.testi-lux .mark{font-family:'DM Serif Display',serif;font-size:5rem;color:var(--brand);opacity:.4;line-height:.5;margin-top:.5rem;}
</style>
@endpush

@section('content')
<section class="boutique-hero">
    <i class="fa fa-gem ornament tl"></i>
    <i class="fa fa-gem ornament br"></i>
    <div class="container position-relative">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="chip mb-3"><i class="fa fa-location-dot text-brand"></i> {{ $tenant->city ?? 'Jaipur' }} · Signature Boutique</span>
                <h1 class="mt-3">Crafted with love,<br><span class="text-brand" style="font-style:italic;">worn with pride.</span></h1>
                <p class="lead mt-4">{{ $tenant->tagline ?: 'Handpicked ethnic wear, bridal sarees, and designer kurtis — made by Rajasthani artisans, worn by queens.' }}</p>
                <div class="d-flex gap-2 mt-4 flex-wrap">
                    <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-brand"><i class="fa fa-bag-shopping me-2"></i>Shop the Collection</a>
                    <a href="{{ route('tenant.about', $tenant->slug) }}" class="btn btn-brand-outline">Our Story</a>
                </div>
                <div class="d-flex gap-4 mt-5">
                    <div><div class="fs-3 fw-bold">500+</div><small class="text-muted">Designs</small></div>
                    <div><div class="fs-3 fw-bold">10k+</div><small class="text-muted">Happy customers</small></div>
                    <div><div class="fs-3 fw-bold">10yr</div><small class="text-muted">Of craft</small></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-art">
                    <i class="fa fa-gem hero-icon"></i>
                    <div class="hero-tag one"><div class="ico"><i class="fa fa-truck"></i></div><div><small class="text-muted">Free shipping</small><div class="fw-bold">Over ₹1,999</div></div></div>
                    <div class="hero-tag two"><div class="ico"><i class="fa fa-medal"></i></div><div><small class="text-muted">Made in</small><div class="fw-bold">Jaipur, India</div></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="promo-strip">
    <div class="container d-flex justify-content-center gap-4 flex-wrap small">
        <span><i class="fa fa-shield-halved me-1 text-brand"></i>100% Authentic</span>
        <span><i class="fa fa-truck me-1 text-brand"></i>Pan-India Shipping</span>
        <span><i class="fa fa-rotate-left me-1 text-brand"></i>7-day Easy Returns</span>
        <span><i class="fab fa-whatsapp me-1 text-brand"></i>Chat before you buy</span>
    </div>
</div>

<!-- CATEGORIES -->
@if($categories->count())
<section class="py-5 mt-4">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-eyebrow">Shop by</span>
            <div class="divider-ornament"><i class="fa fa-gem"></i></div>
            <h2 class="sec-title">Categories</h2>
        </div>
        <div class="row g-4">
            @foreach($categories->take(4) as $cat)
                <div class="col-6 col-md-3">
                    <a href="{{ route('tenant.products', $tenant->slug) }}?category={{ urlencode($cat) }}" class="cat-card">
                        <div><small>Explore</small><h5>{{ $cat }}</h5></div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($featured->count())
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <span class="sec-eyebrow">Handpicked</span>
                <h2 class="sec-title mt-1">Featured Collection</h2>
            </div>
            <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-brand-outline">View all <i class="fa fa-arrow-right ms-1"></i></a>
        </div>
        <div class="row g-4">
            @foreach($featured as $p)
                @include('saas.themes._shared.product_card', ['p' => $p])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- TESTIMONIAL LUX -->
<div class="container">
    <div class="testi-lux">
        <div class="mark">"</div>
        <p class="quote">Every piece from {{ $tenant->business_name }} feels like it was made just for me. The bridal lehenga I ordered arrived packaged like a gift — I cried happy tears.</p>
        <div class="mt-4">
            <div class="fw-bold">Aarti Gupta</div>
            <small class="text-muted">Verified buyer · Bandhani Lehenga</small>
        </div>
    </div>
</div>

@if($latest->count())
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-eyebrow">Just in</span>
            <div class="divider-ornament"><i class="fa fa-star"></i></div>
            <h2 class="sec-title">New Arrivals</h2>
            <p class="text-muted">Fresh drops, limited pieces — grab yours before they're gone.</p>
        </div>
        <div class="row g-4">
            @foreach($latest as $p)
                @include('saas.themes._shared.product_card', ['p' => $p])
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-brand btn-lg"><i class="fa fa-bag-shopping me-2"></i>Shop all products</a>
        </div>
    </div>
</section>
@endif

@if($page && $page->content)
<section class="py-5">
    <div class="container">{!! $page->content !!}</div>
</section>
@endif

@include('saas.themes._shared.enquiry_section')
@endsection
