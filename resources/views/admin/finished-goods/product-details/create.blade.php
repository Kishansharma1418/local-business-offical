@extends('include.master')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">{{ $productDetails ? 'Edit' : 'Add' }} Product Details</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('finished-good.index') }}" class="text-decoration-none text-body fs-14 hover">
                        Finished Goods List
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    <span class="text-secondary">{{ $productDetails ? 'Edit' : 'Add' }} Product Details</span>
                </li>
            </ol>
        </nav>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('product-details.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
       
        <input type="hidden" name="finished_goods_id" value="{{ $product_id }}">

        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
            <h3 class="mb-3">Product Details</h3>
            <div class="row">

                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Composition</label>
                    <input type="text" name="composition" class="form-control" value="{{ old('composition', $productDetails->composition ?? '') }}">
                </div>

                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Strength / Specification</label>
                    <input type="text" name="strength_specification" class="form-control" value="{{ old('strength_specification', $productDetails->strength_specification ?? '') }}">
                </div>

                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Packing Type</label>
                    <input type="text" name="packing_type" class="form-control" value="{{ old('packing_type', $productDetails->packing_type ?? '') }}">
                </div>

                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Pack Size</label>
                    <input type="text" name="pack_size" class="form-control"  required onkeydown="return event.key !== '-'"
                                    oninput="this.value = this.value < 0 ? 0 : this.value" value="{{ old('pack_size', $productDetails->pack_size ?? '') }}">
                </div>

                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', $productDetails->brand ?? '') }}">
                </div>

                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Country of Origin</label>
                    <input type="text" name="country_origin" class="form-control" value="{{ old('country_origin', $productDetails->country_origin ?? '') }}">
                </div>

                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Storage Condition</label>
                    <input type="text" name="storage_condation" class="form-control" value="{{ old('storage_condation', $productDetails->storage_condation ?? '') }}">
                </div>

                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Shelf Life (Months)</label>
                    <input type="number" name="shelf_life_months" min='0' class="form-control" value="{{ old('shelf_life_months', $productDetails->shelf_life_months ?? '') }}">
                </div>

                <div class="col-lg-12 mt-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-normal text-white">
                            {{ $productDetails ? 'Update Product Details' : '+ Add Product Details' }}
                        </button>
                        <a href="{{ route('finished-good.index') }}" class="btn btn-danger fw-normal text-white">Cancel</a>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('form').on('submit', function(e) {
        let form = $(this)[0];
        if(!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('was-validated');
            return false;
        }
        $(this).find('button[type="submit"]')
            .prop('disabled', true)
            .text('Processing...');
    });
});
</script>
@endpush
