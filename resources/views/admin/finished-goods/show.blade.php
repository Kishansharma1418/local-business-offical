@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Finished Good Details</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('finished-good.index') }}" class="text-decoration-none">Finished Goods List</a>
                </li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4">

        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">General Information</h5>
                <p><strong>Product Code:</strong> {{ $finishedGood->code ?? '-' }}</p>
                <p><strong>Name:</strong> {{ $finishedGood->name ?? '-' }}</p>
                <p><strong>HSN Code:</strong> {{ $finishedGood->hsn_code ?? '-' }}</p>
                <p><strong>Status:</strong>
                    @if($finishedGood->status == '1')
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Category Details</h5>
                <p><strong>Category:</strong> {{ $finishedGood->category?->category_name ?? '-' }}</p>
                <p><strong>Sub Category:</strong> {{ $finishedGood->subCategory?->category_name ?? '-' }}</p>
                <p><strong>Branch:</strong> {{ $finishedGood->branches?->branch_name ?? '-' }}</p>
                <p><strong>UOM:</strong> {{ $finishedGood->uoms?->name ?? '-' }}</p>
               
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Product Details</h5>
                @if($finishedGood->productDetail)
                    <p><strong>Composition:</strong> {{ $finishedGood->productDetail->composition ?? '-' }}</p>
                    <p><strong>Strength / Specification:</strong> {{ $finishedGood->productDetail->strength_specification ?? '-' }}</p>
                    <p><strong>Packing Type:</strong> {{ $finishedGood->productDetail->packing_type ?? '-' }}</p>
                    <p><strong>Pack Size:</strong> {{ $finishedGood->productDetail->pack_size ?? '-' }}</p>
                    <p><strong>Brand:</strong> {{ $finishedGood->productDetail->brand ?? '-' }}</p>
                    <p><strong>Country of Origin:</strong> {{ $finishedGood->productDetail->country_origin ?? '-' }}</p>
                    <p><strong>Storage Condition:</strong> {{ $finishedGood->productDetail->storage_condation ?? '-' }}</p>
                    <p><strong>Shelf Life (months):</strong> {{ $finishedGood->productDetail->shelf_life_months ?? '-' }}</p>
                @else
                    <p>No product details available.</p>
                @endif
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Description</h5>
                <p>{{ $finishedGood->description ?? '-' }}</p>
            </div>
        </div>

    </div>
</div>
@endsection
