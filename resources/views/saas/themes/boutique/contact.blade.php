@extends('saas.themes._shared.layout')
@section('title', 'Contact · ' . $tenant->business_name)

@section('content')
<section style="background:linear-gradient(135deg,var(--brand-soft),#fff);padding:70px 0 50px;">
    <div class="container text-center">
        <span class="sec-eyebrow">Say hello</span>
        <h1 class="display-serif" style="font-size:clamp(2rem,4vw,3.4rem);">We'd love to hear from you</h1>
        <p class="text-muted mx-auto" style="max-width:560px;">Questions, custom orders, bulk enquiries — or just want to chat about our collection? Drop us a message.</p>
    </div>
</section>

@include('saas.themes._shared.enquiry_section')
@endsection
