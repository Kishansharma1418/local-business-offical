@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Batch Details</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('batch-management.index') }}" class="text-decoration-none">Batch List</a>
                    </li>
                    <li class="breadcrumb-item active">Batch Details</li>
                </ol>
            </nav>
        </div>

        <div class="row g-4">

            {{-- General Batch Information --}}
            <div class="col-lg-6">
                <div class="card bg-white border rounded-10 p-4 shadow-sm">
                    <h5 class="mb-3 fw-semibold">General Information</h5>
                    <p><strong>Batch Number:</strong> {{ $batch->batch_number ?? '-' }}</p>
                    <p><strong>Product:</strong> {{ $batch->product?->name ?? '-' }}</p>
                    <p><strong>Warehouse:</strong> {{ $batch->warehouse?->warehouse_name ?? '-' }}</p>

                </div>
            </div>

            {{-- Dates --}}
            <div class="col-lg-6">
                <div class="card bg-white border rounded-10 p-4 shadow-sm">
                    <h5 class="mb-3 fw-semibold">Batch Dates</h5>
                    <p><strong>Manufacturing Date:</strong>
                        {{ $batch->manufacturing_date ? \Carbon\Carbon::parse($batch->manufacturing_date)->format('d M Y') : '-' }}
                    </p>
                    <p><strong>Expiry Date:</strong>
                        {{ $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('d M Y') : '-' }}
                    </p>
                    @php
                        $expiry = \Carbon\Carbon::parse($batch->expiry_date);
                        $today = \Carbon\Carbon::today();
                        $expired = $expiry->lt($today);
                    @endphp
                    <p><strong>Expiry Status:</strong>
                        @if ($expired)
                            <span class="badge bg-danger">Expired</span>
                        @else
                            <span class="badge bg-success">Valid</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Quantity & Pricing --}}
            <div class="col-lg-6">
                <div class="card bg-white border rounded-10 p-4 shadow-sm">
                    <h5 class="mb-3 fw-semibold">Stock & Pricing</h5>
                    <p><strong>Available Quantity:</strong> {{ $batch->available_quantity ?? '-' }}</p>
                   
                </div>
            </div>

            {{-- Audit Info --}}
            <div class="col-lg-6">
                <div class="card bg-white border rounded-10 p-4 shadow-sm">
                    <h5 class="mb-3 fw-semibold">Audit Information</h5>
                    <p><strong>Created By:</strong> {{ $batch->createdBy?->full_name ?? '-' }}</p>
                    <p><strong>Updated By:</strong> {{ $batch->updatedBy?->full_name ?? '-' }}</p>
                    <p><strong>Created At:</strong>
                        {{ $batch->created_at ? \Carbon\Carbon::parse($batch->created_at)->format('d M Y') : '-' }}
                    </p>
                    <p><strong>Last Updated:</strong>
                        {{ $batch->updated_at ? \Carbon\Carbon::parse($batch->updated_at)->format('d M Y') : '-' }}
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection
