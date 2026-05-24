@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Add Asset</h3>

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
                    <a href="{{ route('employee.index') }}"
                        class="d-flex align-items-center text-decoration-none">
                        <span class="text-body fs-14 hover">Employee List</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('employee.assets.index', encrypt($employee->id)) }}">
                        Asset Management
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    Add Asset
                </li>
            </ol>
        </nav>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Success --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST"
        action="{{ route('employee.assets.store', encrypt($employee->id)) }}"
        class="needs-validation"
        novalidate>
        @csrf

        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
            <h3 class="mb-20">Asset Information</h3>

            <div class="row">

                {{-- Asset Name --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">
                        Asset Name <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        name="name"
                        value="{{ old('name') }}"
                        required>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Asset Code --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">
                        Asset Code <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                        class="form-control @error('code') is-invalid @enderror"
                        name="code"
                        value="{{ old('code') }}"
                        required>
                    @error('code')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Asset Type --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">Asset Type</label>
                    <input type="text"
                        class="form-control"
                        name="asset_type"
                        value="{{ old('asset_type') }}">
                </div>

                {{-- Serial Number --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">Serial Number</label>
                    <input type="text"
                        class="form-control"
                        name="serial_number"
                        value="{{ old('serial_number') }}">
                </div>

                {{-- IMEI --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">IMEI Number</label>
                    <input type="text"
                        class="form-control"
                        name="imei_number"
                        value="{{ old('imei_number') }}">
                </div>

                {{-- Start Date --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">Start Date  <span class="text-danger">*</span></label>
                    <input type="date"
                        class="form-control"
                        name="start_date"
                        value="{{ old('start_date') }}" required>
                </div>

                {{-- End Date --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">End Date  <span class="text-danger">*</span></label>
                    <input type="date"
                        class="form-control"
                        name="end_date"
                        value="{{ old('end_date') }}" required>
                </div>

                {{-- Status --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">
                        Status <span class="text-danger">*</span>
                    </label>
                    <select name="status"
                        class="form-control @error('status') is-invalid @enderror"
                        required>
                        <option value="">Select Status</option>
                        <option value="available">Available</option>
                        <option value="assigned">Assigned</option>
                        <option value="under_maintenance">Under Maintenance</option>
                        <option value="inactive">Inactive</option>
                        <option value="scrap">Scrap</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="col-lg-12 mt-3">
                    <div class="d-flex gap-2">
                        <button type="submit"
                            class="btn btn-primary fw-normal text-white">
                            + Add Asset
                        </button>
                        <a href="{{ route('employee.assets.index', encrypt($employee->id)) }}"
                            class="btn btn-danger fw-normal text-white">
                            Cancel
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>
@endsection