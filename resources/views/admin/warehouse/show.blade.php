@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Warehouse Details</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('warehouse.index') }}" class="text-decoration-none">Warehouse List</a>
                </li>
                <li class="breadcrumb-item active">Warehouse Details</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4">

        {{-- General Information --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">General Information</h5>
                <p><strong>Warehouse Code:</strong> {{ $warehouse->code ?? '-' }}</p>
                <p><strong>Warehouse Name:</strong> {{ $warehouse->warehouse_name ?? '-' }}</p>
                <p><strong>Warehouse Purpose:</strong> {{ $warehouse->warehouse_purpose ?? '-' }}</p>
                <p><strong>Material Type:</strong> {{ $warehouse->material_type ?? '-' }}</p>
                <p><strong>Status:</strong>
                    @if($warehouse->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Branch & Location Info --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Branch & Location</h5>
                <p><strong>Branch:</strong> {{ $warehouse->branch?->branch_name ?? '-' }}</p>
                <p><strong>Country:</strong> {{ $warehouse->country?->name ?? '-' }}</p>
                <p><strong>State:</strong> {{ $warehouse->state?->name ?? '-' }}</p>
                <p><strong>City:</strong> {{ $warehouse->city?->name ?? '-' }}</p>
                <p><strong>Pincode:</strong> {{ $warehouse->pincode ?? '-' }}</p>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Contact Information</h5>
                <p><strong>Contact Person:</strong> {{ $warehouse->contact_person ?? '-' }}</p>
                <p><strong>Contact Number:</strong> {{ $warehouse->contact_number ?? '-' }}</p>
                <p><strong>Email:</strong> {{ $warehouse->email ?? '-' }}</p>
            </div>
        </div>

        {{-- Temperature & Storage --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Temperature & Storage</h5>
                <p><strong>Temperature Controlled:</strong> {{ $warehouse->temperature_controlled ?? '-' }}</p>
                @if($warehouse->temperature_controlled === 'Yes')
                    <p><strong>Min Temperature:</strong> {{ $warehouse->temperature_range_min ?? '-' }} °C</p>
                    <p><strong>Max Temperature:</strong> {{ $warehouse->temperature_range_max ?? '-' }} °C</p>
                @endif
                <p><strong>Storage Conditions:</strong> {{ $warehouse->storage_conditions ?? '-' }}</p>
            </div>
        </div>

        {{-- Address --}}
        <div class="col-lg-12">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Address</h5>
                <p><strong>Street / Area:</strong> {{ $warehouse->address_line1 ?? '-' }}</p>
                <p><strong>Landmark:</strong> {{ $warehouse->address_line2 ?? '-' }}</p>
            </div>
        </div>

    </div>
</div>
@endsection
