{{-- Listing card for clinic / property themes. Pass $p (product). --}}
@php
    $theme = $tenant->theme ?? 'boutique';
    $meta = $p->meta ?? [];
@endphp
<div class="col-md-6 col-lg-4">
    <div class="product-card h-100">
        <a href="{{ route('tenant.product.show', [$tenant->slug, $p->slug]) }}" class="p-img" style="aspect-ratio:16/10;">
            @if($p->image)
                <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}">
            @else
                <span class="p-placeholder" style="font-size:2.2rem;">
                    @if($theme === 'property')<i class="fa fa-building"></i>@else<i class="fa fa-stethoscope"></i>@endif
                </span>
            @endif
            @if($p->is_featured)<span class="p-badge"><i class="fa fa-star me-1" style="color:#f59e0b;"></i>Featured</span>@endif
        </a>
        <div class="p-body">
            <div class="d-flex flex-wrap gap-1 mb-2">
                @if($theme === 'property')
                    @if(!empty($meta['purpose']))<span class="badge rounded-pill" style="background:var(--brand-soft);color:var(--brand);font-size:.7rem;">{{ $meta['purpose'] }}</span>@endif
                    @if(!empty($meta['property_type']))<span class="badge rounded-pill bg-light text-dark border" style="font-size:.7rem;">{{ $meta['property_type'] }}</span>@endif
                    @if(!empty($meta['bhk']))<span class="badge rounded-pill bg-light text-dark border" style="font-size:.7rem;">{{ $meta['bhk'] }} BHK</span>@endif
                @elseif($theme === 'clinic')
                    @if(!empty($meta['specialty']))<span class="badge rounded-pill" style="background:var(--brand-soft);color:var(--brand);font-size:.7rem;">{{ $meta['specialty'] }}</span>@endif
                    @if(!empty($meta['consultation_type']))<span class="badge rounded-pill bg-light text-dark border" style="font-size:.7rem;">{{ $meta['consultation_type'] }}</span>@endif
                @endif
            </div>
            <h6 class="p-name"><a href="{{ route('tenant.product.show', [$tenant->slug, $p->slug]) }}">{{ $p->name }}</a></h6>
            @if($theme === 'property' && !empty($meta['location']))
                <div class="small text-muted mb-1"><i class="fa fa-location-dot me-1"></i>{{ $meta['location'] }}</div>
            @endif
            @if($theme === 'property' && !empty($meta['area_sqft']))
                <div class="small text-muted mb-1"><i class="fa fa-ruler-combined me-1"></i>{{ $meta['area_sqft'] }} sq.ft</div>
            @endif
            @if($theme === 'clinic' && !empty($meta['duration']))
                <div class="small text-muted mb-1"><i class="fa fa-clock me-1"></i>{{ $meta['duration'] }}</div>
            @endif
            <div class="p-foot">
                <div>
                    <span class="p-price">₹{{ number_format($p->price, 0) }}</span>
                    @if($theme === 'property' && !empty($meta['purpose']) && $meta['purpose'] === 'Rent')
                        <span class="small text-muted">/ month</span>
                    @elseif($theme === 'clinic')
                        <span class="small text-muted d-block">consultation fee</span>
                    @endif
                </div>
                @if($tenant->isShopMode())
                <form method="POST" action="{{ route('tenant.cart.add', $tenant->slug) }}" class="m-0">@csrf
                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                    <button class="add-btn" title="Book / Add"><i class="fa fa-plus"></i></button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
