@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Raw Material Details</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('rawmaterial.index') }}" class="text-decoration-none">
                            Raw Material List
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
        </div>

        <div class="row g-4">

            <!-- General Info -->
            <div class="col-lg-6">
                <div class="card bg-white border rounded-10 p-4 shadow-sm">
                    <h5 class="mb-3 fw-semibold">General Information</h5>

                    <p><strong>Material Code:</strong> {{ $rawMaterial->code ?? '-' }}</p>
                    <p><strong>Name:</strong> {{ $rawMaterial->name ?? '-' }}</p>
                    <p><strong>HSN Code:</strong> {{ $rawMaterial->hsn_code ?? '-' }}</p>

                    <p><strong>Status:</strong>
                        @if ($rawMaterial->status == 1)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Category Info -->
            <div class="col-lg-6">
                <div class="card bg-white border rounded-10 p-4 shadow-sm">
                    <h5 class="mb-3 fw-semibold">Category Information</h5>

                    <p><strong>Raw Category:</strong>
                        {{ $rawMaterial->category?->name ?? '-' }}
                    </p>

                    <p><strong>Sub Category:</strong>
                        {{ $rawMaterial->subCategory?->name ?? '-' }}
                    </p>

                    <p><strong>UOM:</strong>
                        {{ $rawMaterial->uom?->name ?? '-' }}
                    </p>

                    <p><strong>Lead Time (Days):</strong>
                        {{ $rawMaterial->lead_time_days ?? '-' }}
                    </p>
                </div>
            </div>

            <!-- Description -->
            <div class="col-lg-12">
                <div class="card bg-white border rounded-10 p-4 shadow-sm">
                    <h5 class="mb-3 fw-semibold">Description</h5>
                    <p>{{ $rawMaterial->description ?? '-' }}</p>
                </div>
            </div>

            <!-- Audit Info -->
            <div class="col-lg-12">
                <div class="card bg-white border rounded-10 p-4 shadow-sm">
                    <h5 class="mb-3 fw-semibold">Audit Information</h5>

                    <p><strong>Created At:</strong>
                        {{ $rawMaterial->created_at?->format('d M, Y') ?? '-' }}
                    </p>

                    <p><strong>Updated At:</strong>
                        {{ $rawMaterial->updated_at?->format('d M, Y') ?? '-' }}
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection
