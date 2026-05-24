@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Leave Details</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('leaves.index') }}" class="text-decoration-none">Leave List</a>
                </li>
                <li class="breadcrumb-item active">Leave Details</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4">

        {{-- General Information --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm position-relative">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold mb-0">General Information</h5>

                    <!-- Change Status Button -->
                    @if(Auth::check() && Auth::user()->user_type == "admin")
                    <button type="button" class="btn btn-primary fw-normal text-white"
                        data-bs-toggle="modal" data-bs-target="#statusModal">
                        Change Status
                    </button>
                    @endif
                </div>

                {{-- <p><strong>Leave Category:</strong> {{ ucfirst($leave->leave_category ?? '-') }}</p> --}}
                <p><strong>Leave Type:</strong> {{ ucfirst($leave->leave_type ?? '-') }}</p>

                <p><strong>Status:</strong>
                    @if ($leave->status == 'Pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($leave->status == 'Verified')
                        <span class="badge bg-success">Verified</span>
                    @elseif($leave->status == 'Rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-secondary">Expired</span>
                    @endif
                </p>

                <p><strong>Total Days:</strong> {{ $leave->total_days ?? '0' }}</p>

                <p><strong>Created By:</strong> {{ $leave->creator?->full_name ?? '-' }}</p>
                {{-- <p><strong>Updated By:</strong> {{ $leave->updater?->full_name ?? '-' }}</p> --}}
            </div>
        </div>

        {{-- Date Details --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Date Details</h5>

                <p><strong>Start Date:</strong>
                     {{ formatDate($leave->start_date) }}
                </p>

                <p><strong>End Date:</strong>
                     {{ formatDate($leave->end_date) }}
                </p>

                <p><strong>Created At:</strong>
                     {{ formatDate($leave->created_at, 'd-m-Y h:i A') }}
                </p>
                
                <p><strong>Updated At:</strong>
                   {{ formatDate($leave->updated_at, 'd-m-Y h:i A') }}
                </p>
            </div>
        </div>

        {{-- Description --}}
        <div class="col-lg-12">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Description</h5>
                <p>{{ $leave->description ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Change Status -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
        <form class="modal-content bg-white" method="POST" action="{{ route('leaves.updateStatus', $leave->id) }}">
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
                            <option value="Pending" {{ $leave->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Verified" {{ $leave->status == 'Verified' ? 'selected' : '' }}>Verified</option>
                            <option value="Rejected" {{ $leave->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="Expired" {{ $leave->status == 'Expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>

                    <div class="col-lg-12 mb-20">
                        <label class="label fs-16 mb-2">Reason</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Enter reason or note here...">{{ $leave->reason ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 p-20 pt-0">
                <button type="button" class="btn btn-danger fw-normal text-white" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary fw-normal text-white">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
