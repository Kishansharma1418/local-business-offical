@extends('saas.themes._shared.layout')

@push('styles')
<style>
.furn-hero{position:relative;padding:100px 0;background:linear-gradient(135deg,#fdf6ec 0%,#f5e6d3 60%,#fff 100%);overflow:hidden;}
.furn-hero::before{content:'';position:absolute;top:-150px;right:-150px;width:500px;height:500px;background:radial-gradient(circle,rgba(141,110,99,.15),transparent 60%);border-radius:50%;}
.furn-hero h1{font-family:'Playfair Display',serif;font-weight:800;font-size:clamp(2.5rem,5.5vw,4.6rem);line-height:1.05;letter-spacing:-.02em;}
.furn-hero .lead{font-size:1.1rem;color:#5d4e44;max-width:520px;}
.furn-visual{position:relative;height:520px;border-radius:28px;overflow:hidden;background:linear-gradient(135deg,#8d6e63 0%,#3e2723 100%);box-shadow:0 30px 80px -20px rgba(0,0,0,.3);}
.furn-visual::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cpath d='M0 0h100v100H0z' fill='none'/%3E%3Cpath d='M20 20h60v60H20z' stroke='%23fff' stroke-opacity='0.05' fill='none'/%3E%3Cpath d='M0 50h100M50 0v100' stroke='%23fff' stroke-opacity='0.05'/%3E%3C/svg%3E");}
.furn-visual .wood-icon{font-family:'DM Serif Display',serif;font-size:9rem;color:#fff;opacity:.2;}
.furn-badge{position:absolute;background:#fff;padding:14px 18px;border-radius:14px;box-shadow:0 14px 40px rgba(0,0,0,.18);display:flex;align-items:center;gap:12px;}
.furn-badge.b1{top:50px;left:-30px;}
.furn-badge.b2{bottom:50px;right:-30px;}
.furn-badge .ico{width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,#8d6e63,#5d4037);color:#fff;display:flex;align-items:center;justify-content:center;}
.value-card{background:#fff;border:1px solid var(--line);border-radius:18px;padding:28px 24px;height:100%;transition:all .3s;}
.value-card:hover{transform:translateY(-6px);box-shadow:0 22px 50px -15px rgba(0,0,0,.1);border-color:transparent;}
.value-card .ico{width:58px;height:58px;border-radius:14px;background:linear-gradient(135deg,#8d6e63,#5d4037);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:18px;}
.wood-section{background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cpath d='M0 0h80v80H0z' fill='%238d6e63' fill-opacity='0.02'/%3E%3C/svg%3E"),#fbf7f1;}
.process-row{position:relative;padding-left:40px;}
.process-row::before{content:'';position:absolute;left:20px;top:0;bottom:0;width:2px;background:linear-gradient(180deg,var(--brand),transparent);}
.process-step{position:relative;padding:18px 0 18px 20px;}
.process-step::before{content:'';position:absolute;left:-24px;top:24px;width:12px;height:12px;border-radius:50%;background:var(--brand);box-shadow:0 0 0 4px rgba(141,110,99,.2);}
</style>
@endpush

@section('content')
<section class="furn-hero">
    <div class="container position-relative">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="chip mb-3"><i class="fa fa-tree text-brand"></i> Solid wood · Since 2003</span>
                <h1 class="mt-3">Built by hand.<br>Made to <span style="font-style:italic;color:var(--brand);">last.</span></h1>
                <p class="lead mt-4">{{ $tenant->tagline ?: 'Premium sheesham, teak and mango wood furniture — crafted in our Jaipur workshop, priced direct-from-factory.' }}</p>
                <div class="d-flex gap-2 mt-4 flex-wrap">
                    <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-brand"><i class="fa fa-couch me-2"></i>Shop Furniture</a>
                    <a href="{{ route('tenant.contact', $tenant->slug) }}" class="btn btn-brand-outline"><i class="fa fa-phone me-2"></i>Book a visit</a>
                </div>
                <div class="d-flex gap-4 mt-5">
                    <div><div class="fs-3 fw-bold">20yr</div><small class="text-muted">Of craftsmanship</small></div>
                    <div><div class="fs-3 fw-bold">5yr</div><small class="text-muted">Warranty</small></div>
                    <div><div class="fs-3 fw-bold">2000+</div><small class="text-muted">Happy homes</small></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="furn-visual">
                    <i class="fa fa-couch wood-icon"></i>
                    <div class="furn-badge b1"><div class="ico"><i class="fa fa-award"></i></div><div><small class="text-muted">5-year</small><div class="fw-bold">Warranty</div></div></div>
                    <div class="furn-badge b2"><div class="ico"><i class="fa fa-truck"></i></div><div><small class="text-muted">Free delivery</small><div class="fw-bold">Across India</div></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="py-5 wood-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-eyebrow">Why Royal Wood</span>
            <h2 class="sec-title">Built different. Built to last.</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="value-card">
                    <div class="ico"><i class="fa fa-tree"></i></div>
                    <h5 class="fw-bold">Premium wood only</h5>
                    <p class="text-muted mb-0">Grade-A sheesham, teak and mango wood — seasoned for 6 months before any build touches it.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <div class="ico"><i class="fa fa-hammer"></i></div>
                    <h5 class="fw-bold">Workshop direct</h5>
                    <p class="text-muted mb-0">No middlemen. No showroom markup. You get factory prices with heirloom-quality craftsmanship.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card">
                    <div class="ico"><i class="fa fa-shield-halved"></i></div>
                    <h5 class="fw-bold">5-year warranty</h5>
                    <p class="text-muted mb-0">Every piece comes with a 5-year structural warranty. If it breaks from normal use, we fix it free.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@if($featured->count())
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <span class="sec-eyebrow">Signature pieces</span>
                <h2 class="sec-title mt-1">Bestsellers</h2>
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

@if($categories->count())
<section class="py-5 wood-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-eyebrow">Shop by room</span>
            <h2 class="sec-title">Find your piece</h2>
        </div>
        <div class="row g-3 justify-content-center">
            @foreach($categories->take(6) as $cat)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('tenant.products', $tenant->slug) }}?category={{ urlencode($cat) }}" class="d-block text-center p-4 rounded-lux text-decoration-none" style="background:#fff;border:1px solid var(--line);transition:all .3s;color:var(--ink);">
                        <i class="fa fa-chair fa-2x text-brand mb-2"></i>
                        <div class="fw-semibold">{{ $cat }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Process -->
<section class="py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <span class="sec-eyebrow">How we build</span>
                <h2 class="sec-title mt-2">From raw wood to your living room.</h2>
                <p class="text-muted mt-3">Every piece goes through 6 stages of care — so by the time it reaches you, there's nothing left to chance.</p>
            </div>
            <div class="col-lg-7">
                <div class="process-row">
                    <div class="process-step"><h5 class="fw-bold mb-1">1. Wood sourcing</h5><p class="text-muted mb-0">Grade-A wood hand-picked from Rajasthan sawmills.</p></div>
                    <div class="process-step"><h5 class="fw-bold mb-1">2. Kiln seasoning</h5><p class="text-muted mb-0">6-month drying cycle to prevent warping and cracks.</p></div>
                    <div class="process-step"><h5 class="fw-bold mb-1">3. Master carpenter build</h5><p class="text-muted mb-0">Hand-built by craftsmen with 20+ years of experience.</p></div>
                    <div class="process-step"><h5 class="fw-bold mb-1">4. Quality check</h5><p class="text-muted mb-0">3-stage QC — structural, finish, hardware.</p></div>
                    <div class="process-step"><h5 class="fw-bold mb-1">5. Eco-friendly finish</h5><p class="text-muted mb-0">Melamine + natural wax for lasting shine.</p></div>
                    <div class="process-step"><h5 class="fw-bold mb-1">6. White-glove delivery</h5><p class="text-muted mb-0">Delivered, assembled and placed in your home.</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($latest->count())
<section class="py-5 wood-section">
    <div class="container">
        <div class="text-center mb-5">
            <span class="sec-eyebrow">New drops</span>
            <h2 class="sec-title">Fresh from the workshop</h2>
        </div>
        <div class="row g-4">
            @foreach($latest as $p)
                @include('saas.themes._shared.product_card', ['p' => $p])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('saas.themes._shared.enquiry_section')
@endsection
