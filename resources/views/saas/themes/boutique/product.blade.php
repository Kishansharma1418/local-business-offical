@extends('saas.themes._shared.layout')
@section('title', $product->name . ' · ' . $tenant->business_name)

@section('content')
<section class="py-5">
    <div class="container">
        <nav class="small text-muted mb-4">
            <a href="{{ route('tenant.home', $tenant->slug) }}" class="text-muted">Home</a>
            <i class="fa fa-chevron-right mx-2" style="font-size:.65rem;"></i>
            <a href="{{ route('tenant.products', $tenant->slug) }}" class="text-muted">Shop</a>
            <i class="fa fa-chevron-right mx-2" style="font-size:.65rem;"></i>
            <span>{{ $product->name }}</span>
        </nav>
        <div class="row g-5">
            <div class="col-md-6">
                <div class="rounded-lux overflow-hidden shadow-lux position-relative" style="aspect-ratio:4/5;background:linear-gradient(135deg,var(--brand-soft),#f3f4f6);display:flex;align-items:center;justify-content:center;">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <span style="font-family:'DM Serif Display',serif;font-size:7rem;color:var(--brand);opacity:.35;">{{ strtoupper(substr($product->name,0,2)) }}</span>
                    @endif
                    @if($product->mrp && $product->mrp > $product->price)
                        @php $off = round((($product->mrp - $product->price) / $product->mrp) * 100); @endphp
                        <span class="position-absolute top-0 start-0 m-3 badge" style="background:#ef4444;color:#fff;padding:8px 14px;border-radius:20px;font-weight:700;letter-spacing:.05em;">{{ $off }}% OFF</span>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                @if($product->category)<span class="chip mb-3"><i class="fa fa-tag text-brand"></i> {{ $product->category }}</span>@endif
                <h1 class="display-serif" style="font-size:clamp(1.7rem,3vw,2.6rem);line-height:1.15;">{{ $product->name }}</h1>

                <div class="d-flex align-items-center gap-1 mt-2" style="color:#f59e0b;">
                    @for($i=0;$i<5;$i++)<i class="fa fa-star"></i>@endfor
                    <small class="text-muted ms-2">4.9 · 120+ reviews</small>
                </div>

                <div class="my-4">
                    <span class="fs-2 fw-bold text-brand">₹{{ number_format($product->price,0) }}</span>
                    @if($product->mrp && $product->mrp > $product->price)<span class="fs-5 text-muted text-decoration-line-through ms-2">₹{{ number_format($product->mrp,0) }}</span>@endif
                    <small class="d-block text-success mt-1"><i class="fa fa-check-circle me-1"></i>Inclusive of all taxes</small>
                </div>

                @if($product->short_description)<p class="lead text-muted">{{ $product->short_description }}</p>@endif
                @if($product->description)<div class="mb-4" style="color:#4b5563;line-height:1.75;">{!! nl2br(e($product->description)) !!}</div>@endif

                <form method="POST" action="{{ route('tenant.cart.add', $tenant->slug) }}" class="d-flex gap-2 mb-3 flex-wrap">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="d-flex align-items-center border rounded-3 px-2" style="background:#f7f8fa;">
                        <button type="button" class="btn btn-sm p-1" onclick="let i=this.nextElementSibling;i.value=Math.max(1,parseInt(i.value)-1);"><i class="fa fa-minus"></i></button>
                        <input type="number" name="qty" value="1" min="1" class="form-control border-0 text-center bg-transparent" style="width:60px;">
                        <button type="button" class="btn btn-sm p-1" onclick="let i=this.previousElementSibling;i.value=parseInt(i.value)+1;"><i class="fa fa-plus"></i></button>
                    </div>
                    <button class="btn btn-brand btn-lg flex-grow-1"><i class="fa fa-bag-shopping me-2"></i>Add to Cart</button>
                    @if($tenant->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $tenant->whatsapp) }}?text=Hi%2C%20interested%20in%20{{ urlencode($product->name) }}" target="_blank" class="btn btn-lg" style="background:#25d366;color:#fff;"><i class="fab fa-whatsapp"></i></a>
                    @endif
                </form>

                @if($product->stock <= 0)
                    <div class="alert alert-warning"><i class="fa fa-clock me-2"></i>Out of stock — enquire to reserve yours.</div>
                @elseif($product->stock < 5)
                    <div class="alert alert-warning py-2 small"><i class="fa fa-fire me-2"></i>Only <b>{{ $product->stock }}</b> left — selling fast!</div>
                @endif

                <div class="row g-3 mt-4 pt-3 border-top">
                    <div class="col-6 col-md-4 text-center">
                        <i class="fa fa-truck fa-2x text-brand mb-2"></i>
                        <div class="small fw-semibold">Free Shipping</div>
                        <div class="small text-muted">Over ₹1,999</div>
                    </div>
                    <div class="col-6 col-md-4 text-center">
                        <i class="fa fa-shield-halved fa-2x text-brand mb-2"></i>
                        <div class="small fw-semibold">Authentic</div>
                        <div class="small text-muted">100% genuine</div>
                    </div>
                    <div class="col-6 col-md-4 text-center">
                        <i class="fa fa-rotate-left fa-2x text-brand mb-2"></i>
                        <div class="small fw-semibold">Easy Returns</div>
                        <div class="small text-muted">7-day policy</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
