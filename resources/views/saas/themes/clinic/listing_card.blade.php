@php
    $meta = $p->meta ?? [];
    $ctype = $meta['consultation_type'] ?? '';
    $badgeClass = strtolower($ctype) === 'online' ? 'online' : '';
@endphp
<div class="col-md-6 col-lg-4">
    <article class="clinic-card h-100">
        <div class="clinic-card-top">
            @if(!empty($meta['specialty']))
                <span class="clinic-card-badge {{ $badgeClass }}">{{ $meta['specialty'] }}</span>
            @elseif($ctype)
                <span class="clinic-card-badge {{ $badgeClass }}">{{ $ctype }}</span>
            @endif
            <h3 class="clinic-card-title">
                <a href="{{ route('tenant.product.show', [$tenant->slug, $p->slug]) }}">{{ $p->name }}</a>
            </h3>
            <div class="clinic-card-meta">
                @if($ctype)<span><i class="fa fa-video"></i>{{ $ctype }}</span>@endif
                @if(!empty($meta['duration']))<span><i class="fa fa-clock"></i>{{ $meta['duration'] }}</span>@endif
                @if($p->is_featured)<span><i class="fa fa-star" style="color:#f59e0b;"></i>Popular</span>@endif
            </div>
            @if($p->short_description)
                <p class="small text-muted mb-0">{{ Str::limit($p->short_description, 80) }}</p>
            @endif
        </div>
        <div class="clinic-card-foot">
            <div class="clinic-card-price">
                ₹{{ number_format($p->price, 0) }}
                <small>consultation</small>
            </div>
            @if($tenant->isShopMode())
            <form method="POST" action="{{ route('tenant.cart.add', $tenant->slug) }}" class="m-0">
                @csrf
                <input type="hidden" name="product_id" value="{{ $p->id }}">
                <button type="submit" class="clinic-card-book" title="Book"><i class="fa fa-calendar-plus"></i></button>
            </form>
            @else
            <a href="{{ route('tenant.product.show', [$tenant->slug, $p->slug]) }}" class="clinic-card-book d-inline-flex align-items-center justify-content-center text-decoration-none"><i class="fa fa-arrow-right"></i></a>
            @endif
        </div>
    </article>
</div>
