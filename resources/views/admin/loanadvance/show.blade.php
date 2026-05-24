@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Advance Salary Details</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('loan-advances.index') }}" class="text-decoration-none">Advance Salary List</a>
                    </li>
                    <li class="breadcrumb-item active">Advance Salary Details</li>
                </ol>
            </nav>
        </div>

        <div class="row g-3">

            {{-- Employee Information --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold mb-0">General Information</h5>

                        <!-- Change Status Button -->
                        @if (Auth::check() && Auth::user()->user_type == 'admin')
                            <button type="button" class="btn btn-primary fw-normal text-white" data-bs-toggle="modal"
                                data-bs-target="#statusModal">
                                Change Status
                            </button>
                        @endif
                    </div>
                    <hr>
                    <p><strong>Employee Name:</strong> {{ $loan->employee->full_name ?? '-' }}</p>
                    <p><strong>Employee Code:</strong> {{ $loan->employee->code ?? '-' }}</p>
                    <p><strong>Employee Type:</strong> {{ $loan->employee->role ?? '-' }}</p>
                    <p><strong>Branch:</strong> {{ $loan->employee->branches->branch_name ?? '-' }}</p>
                    <p><strong>Mobile:</strong> {{ $loan->employee->mobile_no ?? '-' }}</p>
                </div>
            </div>

            {{-- Loan Information --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4 shadow-sm">
                    <h5 class="mb-3">Advance Salary Information</h5>
                    <hr>
                    <p><strong>Advance Salary Amount:</strong> ₹ {{ number_format($loan->loan_amount, 2) }}</p>
                    <p><strong>Deduction Amount (EMI):</strong> ₹ {{ number_format($loan->deduction_amount, 2) }}</p>
                    <p><strong>Advance Salary Month:</strong> {{ $loan->month ?? '-' }}</p>
                    <p><strong>Start Month:</strong>
                        {{ $loan->start_month ? \Carbon\Carbon::parse($loan->start_month)->format('M Y') : '-' }}
                    </p>
                    <p><strong>Status:</strong>
                        @if ($loan->status === 'Active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Audit Information --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4 shadow-sm">
                    <h5 class="mb-3">Audit Information</h5>
                    <hr>
                    <p><strong>Created At:</strong>
                     {{ formatDate($loan->created_at) }}
                    </p>
                    <p><strong>Created By:</strong> {{ $loan->creator?->full_name ?? '-' }}</p>

    
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
            <form class="modal-content bg-white" method="POST"
                action="{{ route('loan-advances.updateStatus', $loan->id) }}">
                @csrf
                @method('PUT')

                <div class="modal-header border-border-color-40 p-20">
                    <h1 class="modal-title fs-18 fw-medium mb-0" id="statusModalLabel">Update Leave Status</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-20 pb-0">
                    <div class="row">
                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Change Status</label>
                            <select class="form-select form-control" name="status">
                                <option value="Active" {{ $loan->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ $loan->status == 'Inactive' ? 'selected' : '' }}>Inactive
                                </option>

                            </select>
                        </div>


                    </div>
                </div>

                <div class="modal-footer border-0 p-20 pt-0">
                    <button type="button" class="btn btn-danger fw-normal text-white"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-normal text-white">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
