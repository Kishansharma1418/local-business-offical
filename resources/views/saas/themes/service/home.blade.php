@extends('saas.themes._shared.layout')

@push('styles')
<style>
.svc-hero{position:relative;padding:110px 0 90px;background:linear-gradient(180deg,#f0f7ff 0%,#fff 100%);overflow:hidden;}
.svc-hero::before{content:'';position:absolute;top:-200px;right:-200px;width:600px;height:600px;background:radial-gradient(circle,rgba(30,136,229,.15),transparent 60%);border-radius:50%;}
.svc-hero::after{content:'';position:absolute;bottom:-150px;left:-150px;width:400px;height:400px;background:radial-gradient(circle,rgba(0,172,193,.1),transparent 60%);border-radius:50%;}
.svc-hero h1{font-weight:800;font-size:clamp(2.4rem,5vw,4.2rem);line-height:1.05;letter-spacing:-.025em;}
.svc-visual{position:relative;height:480px;border-radius:24px;overflow:hidden;background:linear-gradient(135deg,var(--brand) 0%,#0d47a1 100%);box-shadow:0 30px 70px -20px rgba(0,0,0,.3);}
.svc-visual::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Ccircle cx='20' cy='20' r='1.5' fill='%23fff' fill-opacity='0.15'/%3E%3C/svg%3E");}
.svc-visual .icon{font-size:8rem;color:#fff;opacity:.2;}
.stat-card{background:#fff;padding:20px;border-radius:16px;box-shadow:0 10px 30px rgba(13,20,60,.08);text-align:center;border:1px solid var(--line);transition:all .3s;}
.stat-card:hover{transform:translateY(-4px);box-shadow:0 20px 50px rgba(13,20,60,.12);}
.stat-card .n{font-size:2.4rem;font-weight:800;color:var(--brand);letter-spacing:-.02em;}
.stat-card .l{color:#6b7280;font-size:.9rem;font-weight:500;}

.svc-card{background:#fff;border:1px solid var(--line);border-radius:20px;padding:32px 26px;height:100%;transition:all .3s;}
.svc-card:hover{transform:translateY(-6px);box-shadow:0 24px 50px rgba(13,20,60,.1);border-color:transparent;}
.svc-card .ico{width:60px;height:60px;border-radius:14px;background:linear-gradient(135deg,var(--brand),#0d47a1);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:20px;}

.process-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;}
.process-card{background:#fff;padding:28px 24px;border-radius:18px;border:1px solid var(--line);position:relative;}
.process-card::before{content:attr(data-num);position:absolute;top:-16px;left:24px;width:36px;height:36px;border-radius:10px;background:var(--brand);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;box-shadow:0 8px 20px -6px var(--brand);}
.cta-band{background:linear-gradient(135deg,var(--brand),#0d47a1);border-radius:24px;padding:60px 40px;color:#fff;position:relative;overflow:hidden;}
.cta-band::before{content:'';position:absolute;top:-100px;right:-100px;width:300px;height:300px;background:radial-gradient(circle,rgba(255,255,255,.15),transparent 60%);border-radius:50%;}
</style>
@endpush

@section('content')
<section class="svc-hero">
    <div class="container position-relative">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="chip mb-3"><i class="fa fa-bolt text-brand"></i> Trusted by 500+ customers in {{ $tenant->city ?? 'Jaipur' }}</span>
                <h1 class="mt-3">Professional services, <span class="text-brand">on demand.</span></h1>
                <p class="lead mt-4 text-muted" style="max-width:500px;">{{ $tenant->tagline ?: 'Skilled technicians, same-day service, transparent pricing. Book online, track in real time, pay only when satisfied.' }}</p>
                <div class="d-flex gap-2 mt-4 flex-wrap">
                    <a href="{{ route('tenant.contact', $tenant->slug) }}" class="btn btn-brand btn-lg"><i class="fa fa-calendar-check me-2"></i>Book a Service</a>
                    <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-brand-outline btn-lg">Browse Services</a>
                </div>
                <div class="d-flex align-items-center gap-3 mt-5 text-muted small">
                    <div style="color:#f59e0b;"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <span><b class="text-dark">4.9/5</b> from 500+ reviews</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="svc-visual d-flex align-items-center justify-content-center">
                    <i class="fa fa-tools icon"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="py-5">
    <div class="container">
        <div class="row g-3">
            <div class="col-6 col-md-3"><div class="stat-card"><div class="n">500+</div><div class="l">Happy customers</div></div></div>
            <div class="col-6 col-md-3"><div class="stat-card"><div class="n">24/7</div><div class="l">Support available</div></div></div>
            <div class="col-6 col-md-3"><div class="stat-card"><div class="n">30m</div><div class="l">Avg response time</div></div></div>
            <div class="col-6 col-md-3"><div class="stat-card"><div class="n">100%</div><div class="l">Satisfaction guarantee</div></div></div>
        </div>
    </div>
</section>

<!-- Services -->
@if($featured->count())
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-eyebrow">What we offer</span>
            <h2 class="sec-title">Our most-booked services</h2>
        </div>
        <div class="row g-4">
            @foreach($featured as $p)
                @include('saas.themes._shared.product_card', ['p' => $p])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Why choose -->
<section class="py-5" style="background:linear-gradient(180deg,#f9fbfd,#fff);">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-eyebrow">Why us</span>
            <h2 class="sec-title">Service, done right.</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="svc-card">
                    <div class="ico"><i class="fa fa-bolt"></i></div>
                    <h5 class="fw-bold">Fast response</h5>
                    <p class="text-muted mb-0">Average 30-min response time. Same-day service for urgent requests.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="svc-card">
                    <div class="ico"><i class="fa fa-user-gear"></i></div>
                    <h5 class="fw-bold">Trained pros</h5>
                    <p class="text-muted mb-0">Background-verified technicians with years of hands-on experience.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="svc-card">
                    <div class="ico"><i class="fa fa-tag"></i></div>
                    <h5 class="fw-bold">Transparent pricing</h5>
                    <p class="text-muted mb-0">No hidden fees. Quote before we start work, pay only once you approve.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="svc-card">
                    <div class="ico"><i class="fa fa-shield-halved"></i></div>
                    <h5 class="fw-bold">Service warranty</h5>
                    <p class="text-muted mb-0">30-day workmanship guarantee on every job, no questions asked.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How it works -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-eyebrow">How it works</span>
            <h2 class="sec-title">Book in 4 simple steps</h2>
        </div>
        <div class="process-grid mt-5">
            <div class="process-card" data-num="1">
                <h5 class="fw-bold">Pick a service</h5>
                <p class="text-muted mb-0">Browse our services and pick what you need.</p>
            </div>
            <div class="process-card" data-num="2">
                <h5 class="fw-bold">Fill details</h5>
                <p class="text-muted mb-0">Tell us your address, time slot and issue.</p>
            </div>
            <div class="process-card" data-num="3">
                <h5 class="fw-bold">Pro arrives</h5>
                <p class="text-muted mb-0">A verified technician reaches on time.</p>
            </div>
            <div class="process-card" data-num="4">
                <h5 class="fw-bold">Done & paid</h5>
                <p class="text-muted mb-0">Pay only after the job is completed to your liking.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5">
    <div class="container">
        <div class="cta-band">
            <div class="row align-items-center position-relative">
                <div class="col-md-8">
                    <h2 class="fw-bold">Need help today? We're one call away.</h2>
                    <p class="mb-0 opacity-90">Book now and get ₹100 off on your first service.</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('tenant.contact', $tenant->slug) }}" class="btn btn-light btn-lg fw-bold"><i class="fa fa-phone me-2"></i>Book now</a>
                </div>
            </div>
        </div>
    </div>
</section>

@include('saas.themes._shared.enquiry_section')
@endsection
