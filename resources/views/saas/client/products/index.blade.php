@extends('saas.layouts.client')
@section('title', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div><h4 class="mb-0">Products</h4><small class="text-muted">Manage your catalog</small></div>
    <a href="{{ route('client.products.create') }}" class="btn btn-primary"><i class="ri-add-line"></i> Add Product</a>
</div>

<div class="card shadow-sm border-0 mb-3"><div class="card-body">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search products...">
        <button class="btn btn-primary">Search</button>
        @if(request('search'))<a href="{{ route('client.products.index') }}" class="btn btn-light">Clear</a>@endif
    </form>
</div></div>

<div class="row g-3">
    @forelse($products as $p)
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                @if($p->image)
                    <img src="{{ asset('storage/' . $p->image) }}" class="card-img-top" style="height:180px;object-fit:cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light" style="height:180px;"><i class="ri-image-line fs-2 text-muted"></i></div>
                @endif
                <div class="card-body">
                    <h6 class="mb-1">{{ $p->name }}</h6>
                    @if($p->category)<small class="text-muted">{{ $p->category }}</small>@endif
                    <div class="mt-2">
                        <span class="fs-5 fw-bold text-primary">₹{{ number_format($p->price,0) }}</span>
                        @if($p->mrp && $p->mrp > $p->price)<small class="text-muted text-decoration-line-through">₹{{ number_format($p->mrp,0) }}</small>@endif
                    </div>
                    <div class="mt-1">
                        <small><i class="ri-archive-line"></i> Stock: {{ $p->stock }}</small>
                        @if($p->is_featured)<span class="badge badge-soft-warning">Featured</span>@endif
                        @if(!$p->is_active)<span class="badge badge-soft-danger">Inactive</span>@endif
                    </div>
                </div>
                <div class="card-footer bg-white border-0 d-flex gap-2">
                    <a href="{{ route('client.products.edit', $p) }}" class="btn btn-sm btn-light flex-fill"><i class="ri-edit-line"></i> Edit</a>
                    <form action="{{ route('client.products.destroy', $p) }}" method="POST" class="flex-fill" onsubmit="return confirm('Delete?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-light text-danger w-100"><i class="ri-delete-bin-line"></i></button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="alert alert-info">No products yet. <a href="{{ route('client.products.create') }}">Add your first product</a>.</div></div>
    @endforelse
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
