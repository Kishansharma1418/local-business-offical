@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Asset Details</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}"
                        class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('employee.index') }}">
                        Employee List
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('employee.assets.index', encrypt($asset->employee_id)) }}">
                        Asset Management
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    View Asset
                </li>
            </ol>
        </nav>
    </div>

    <div class="card bg-white p-20 rounded-10 border border-white mb-4">

        <h3 class="mb-20">Asset Information</h3>

        <div class="row">

            <div class="col-lg-4 mb-20">
                <label class="label fs-14 text-muted">Asset Name</label>
                <div class="fw-semibold">{{ $asset->name }}</div>
            </div>

            <div class="col-lg-4 mb-20">
                <label class="label fs-14 text-muted">Asset Code</label>
                <div class="fw-semibold">{{ $asset->code }}</div>
            </div>

            <div class="col-lg-4 mb-20">
                <label class="label fs-14 text-muted">Asset Type</label>
                <div class="fw-semibold">{{ $asset->asset_type ?? '-' }}</div>
            </div>

            <div class="col-lg-4 mb-20">
                <label class="label fs-14 text-muted">Serial Number</label>
                <div class="fw-semibold">{{ $asset->serial_number ?? '-' }}</div>
            </div>

            <div class="col-lg-4 mb-20">
                <label class="label fs-14 text-muted">IMEI Number</label>
                <div class="fw-semibold">{{ $asset->imei_number ?? '-' }}</div>
            </div>

            <div class="col-lg-4 mb-20">
                <label class="label fs-14 text-muted">Start Date</label>
                <div class="fw-semibold">
                    {{ $asset->start_date ? \Carbon\Carbon::parse($asset->start_date)->format('d M Y') : '-' }}
                </div>
            </div>

            <div class="col-lg-4 mb-20">
                <label class="label fs-14 text-muted">End Date</label>
                <div class="fw-semibold">
                    {{ $asset->end_date ? \Carbon\Carbon::parse($asset->end_date)->format('d M Y') : '-' }}
                </div>
            </div>

            <div class="col-lg-4 mb-20">
                <label class="label fs-14 text-muted">Status</label>
                <div>
                    @if($asset->status == 'available')
                        <span class="badge bg-success">Available</span>
                    @elseif($asset->status == 'assigned')
                        <span class="badge bg-primary">Assigned</span>
                    @elseif($asset->status == 'under_maintenance')
                        <span class="badge bg-warning text-dark">Under Maintenance</span>
                    @elseif($asset->status == 'inactive')
                        <span class="badge bg-secondary">Inactive</span>
                    @else
                        <span class="badge bg-danger">Scrap</span>
                    @endif
                </div>
            </div>

        </div>

        {{-- Footer Buttons --}}
        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('employee.assets.edit', [$asset->id]) }}"
                class="btn btn-primary text-white">
                Edit Asset
            </a>

            <a href="{{ route('employee.assets.index', encrypt($asset->employee_id)) }}"
                class="btn btn-danger text-white">
                Back
            </a>
        </div>

    </div>
</div>
@endsection