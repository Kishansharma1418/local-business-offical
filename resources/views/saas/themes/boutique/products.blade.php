@extends('saas.themes._shared.layout')
@section('title', 'Shop · ' . $tenant->business_name)

@section('content')
<section style="background:linear-gradient(135deg,var(--brand-soft),#fff);padding:70px 0 40px;">
    <div class="container text-center">
        <span class="sec-eyebrow">Shop</span>
        <h1 class="display-serif" style="font-size:clamp(2rem,4vw,3.2rem);">Our Collection</h1>
        <p class="text-muted">Every piece handpicked with love in Jaipur.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <form method="GET" class="row g-2 mb-5 p-3 rounded-lux" style="background:#fff;box-shadow:0 8px 30px -12px rgba(0,0,0,.08);">
            <div class="col-md-7">
                <div class="position-relative">
                    <i class="fa fa-search position-absolute" style="left:16px;top:50%;transform:translateY(-50%);color:#9ca3af;"></i>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-0" placeholder="Search products…" style="padding-left:44px;background:#f7f8fa;border-radius:10px;">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select border-0" style="background:#f7f8fa;border-radius:10px;">
                    <option value="">All categories</option>
                    @foreach($categories as $cat)<option value="{{ $cat }}" @selected(request('category')===$cat)>{{ $cat }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-brand w-100">Filter</button></div>
        </form>

        @if($products->count())
            <div class="mb-3 text-muted small">Showing {{ $products->count() }} of {{ $products->total() }} products</div>
            <div class="row g-4">
                @foreach($products as $p)
                    @include('saas.themes._shared.product_card', ['p' => $p])
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">{{ $products->links() }}</div>
        @else
            <div class="text-center py-5">
                <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                <h5>No products found</h5>
                <p class="text-muted">Try a different search or category.</p>
                <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-brand-outline">Clear filters</a>
            </div>
        @endif
    </div>
</section>
@endsection
