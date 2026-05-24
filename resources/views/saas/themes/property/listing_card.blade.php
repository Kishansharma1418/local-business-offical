@php $meta = $p->meta ?? []; $purpose = $meta['purpose'] ?? ''; @endphp
<div class="col-md-6 col-xl-4">
    <article class="prop-card h-100">
        <a href="{{ route('tenant.product.show', [$tenant->slug, $p->slug]) }}" class="prop-card-media">
            @if($p->image)
                <img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->name }}">
            @else
                <div class="prop-card-placeholder">
                    <i class="fa fa-building"></i>
                </div>
            @endif
            <div class="prop-card-overlay"></div>
            @if($purpose)
                <span class="prop-tag prop-tag-{{ strtolower($purpose) === 'rent' ? 'rent' : 'sale' }}">{{ $purpose }}</span>
            @endif
            @if($p->is_featured)<span class="prop-tag prop-tag-featured"><i class="fa fa-star"></i> Featured</span>@endif
            <div class="prop-card-price">
                <span class="amount">₹{{ number_format($p->price, 0) }}</span>
                @if($purpose === 'Rent')<span class="per">/mo</span>@endif
            </div>
        </a>
        <div class="prop-card-body">
            <h3 class="prop-card-title"><a href="{{ route('tenant.product.show', [$tenant->slug, $p->slug]) }}">{{ $p->name }}</a></h3>
            <div class="prop-card-meta">
                @if(!empty($meta['location']))<span><i class="fa fa-location-dot"></i>{{ $meta['location'] }}</span>@endif
                @if(!empty($meta['bhk']))
    <span>
        <i class="fa fa-door-open"></i>
        {{ $meta['bhk'] }}
        
        @if(!str_contains((string) $meta['bhk'], 'BHK') && $meta['bhk'] !== 'Studio')
            BHK
        @endif
    </span>
@endif
                @if(!empty($meta['area_sqft']))<span><i class="fa fa-ruler-combined"></i>{{ $meta['area_sqft'] }} sq.ft</span>@endif
                @if(!empty($meta['property_type']))<span><i class="fa fa-house"></i>{{ $meta['property_type'] }}</span>@endif
            </div>
            @if($tenant->isShopMode())
            <form method="POST" action="{{ route('tenant.cart.add', $tenant->slug) }}" class="prop-card-action">
                @csrf
                <input type="hidden" name="product_id" value="{{ $p->id }}">
                <button type="submit"><i class="fa fa-calendar-days me-2"></i>Schedule visit</button>
            </form>
            @endif
        </div>
    </article>
</div>
