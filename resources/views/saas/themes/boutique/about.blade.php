@extends('saas.themes._shared.layout')
@section('title', 'About · ' . $tenant->business_name)

@section('content')
<section style="background:linear-gradient(135deg,var(--brand-soft),#fff);padding:70px 0 50px;">
    <div class="container text-center">
        <span class="sec-eyebrow">Our story</span>
        <h1 class="display-serif" style="font-size:clamp(2rem,4vw,3.4rem);">{{ $page?->title ?? 'About ' . $tenant->business_name }}</h1>
        <p class="lead text-muted mx-auto" style="max-width:600px;">{{ $tenant->tagline }}</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-md-5">
                <div class="rounded-lux shadow-lux" style="aspect-ratio:4/5;background:linear-gradient(135deg,var(--brand),#4a148c);display:flex;align-items:center;justify-content:center;color:#fff;">
                    <span style="font-family:'DM Serif Display',serif;font-size:8rem;opacity:.25;">{{ strtoupper(substr($tenant->business_name,0,1)) }}</span>
                </div>
            </div>
            <div class="col-md-7">
                @if($page && $page->content)
                    <div style="line-height:1.85;color:#4b5563;">{!! $page->content !!}</div>
                @else
                    <p class="lead">{{ $tenant->about ?: 'We are ' . $tenant->business_name . ', based in ' . ($tenant->city ?: 'Jaipur') . '.' }}</p>
                @endif
                <div class="row g-3 mt-4">
                    <div class="col-6">
                        <div class="p-3 rounded-lux" style="background:var(--brand-soft);">
                            <i class="fa fa-heart text-brand fa-2x mb-2"></i>
                            <div class="fw-bold">Handcrafted</div>
                            <small class="text-muted">Every piece made with love</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded-lux" style="background:var(--brand-soft);">
                            <i class="fa fa-medal text-brand fa-2x mb-2"></i>
                            <div class="fw-bold">Authentic</div>
                            <small class="text-muted">Traditional techniques</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('saas.themes._shared.enquiry_section')
@endsection
