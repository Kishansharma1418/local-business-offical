<div class="col-6 col-md-4 col-lg-3">
    <div class="product-card">
        <a href="{{ route('tenant.product.show', [$tenant->slug, $p->slug]) }}" class="p-img">
            @if($p->image)
                <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}">
            @else
                <span class="p-placeholder">{{ strtoupper(substr($p->name,0,2)) }}</span>
            @endif
            @if($p->is_featured)
                <span class="p-badge"><i class="fa fa-star me-1" style="color:#f59e0b;"></i>Featured</span>
            @elseif($p->mrp && $p->mrp > $p->price)
                @php $off = round((($p->mrp - $p->price) / $p->mrp) * 100); @endphp
                <span class="p-badge sale">-{{ $off }}%</span>
            @endif
        </a>
        <div class="p-body">
            @if($p->category)<div class="p-cat">{{ $p->category }}</div>@endif
            <h6 class="p-name"><a href="{{ route('tenant.product.show', [$tenant->slug, $p->slug]) }}">{{ $p->name }}</a></h6>
            <div class="p-foot">
                <div>
                    <span class="p-price">₹{{ number_format($p->price,0) }}</span>
                    @if($p->mrp && $p->mrp > $p->price)<span class="p-mrp">₹{{ number_format($p->mrp,0) }}</span>@endif
                </div>
                <form method="POST" action="{{ route('tenant.cart.add', $tenant->slug) }}" class="m-0">@csrf
                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                    <button class="add-btn" title="Add to Cart"><i class="fa fa-plus"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>
