@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">User Login Details</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('users.index') }}" class="text-decoration-none">User Login List</a>
                </li>
                <li class="breadcrumb-item active">User Login Details</li>
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
                    <button type="button" class="btn btn-primary fw-normal text-white"
                        data-bs-toggle="modal" data-bs-target="#statusModal">
                        Change Status    
                    </button>
                </div>

                <p><strong>Full Name:</strong> {{ $user->full_name ?? '-' }}</p>
                <p><strong>Login ID:</strong> {{ $user->username_login_id ?? '-' }}</p>
                <p><strong>User Type:</strong> {{ ucfirst($user->user_type ?? '-') }}</p>

                <p><strong>Status:</strong>
                    @switch($user->status)
                        @case('0')
                            <span class="badge bg-warning">Pending</span>
                            @break
                        @case('1')
                            <span class="badge bg-success">Active</span>
                            @break
                        @case('2')
                            <span class="badge bg-secondary">Inactive</span>
                            @break
                        @case('3')
                            <span class="badge bg-danger">Blocked</span>
                            @break
                        @case('4')
                            <span class="badge bg-dark">Locked</span>
                            @break
                        @default
                            <span class="badge bg-light text-dark">Unknown</span>
                    @endswitch
                </p>

                <p><strong>Remark:</strong> {{ $user->remark ?? '-' }}</p>

                <p><strong>Created At:</strong> {{ $user->created_at ? $user->created_at->format('d M, Y h:i A') : '-' }}</p>
                <p><strong>Updated At:</strong> {{ $user->updated_at ? $user->updated_at->format('d M, Y h:i A') : '-' }}</p>
            </div>
        </div>

        {{-- Login Details --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Login Details</h5>
                <p><strong>Last Login:</strong> {{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('d M, Y h:i A') : '-' }}</p>
                <p><strong>Last IP:</strong> {{ $user->last_ip ?? '-' }}</p>
                <p><strong>Failed Login Attempts:</strong> {{ $user->failed_login_attempts ?? '0' }}</p>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Contact Information</h5>
                <p><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
                <p><strong>Phone:</strong> {{ $user->phone ?? '-' }}</p>
            </div>
        </div>

        {{-- Reference & Security --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Reference & Security</h5>
                <p><strong>Reference ID:</strong> {{ $user->reference_id ?? '-' }}</p>
            </div>
        </div>

       

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
        <form class="modal-content bg-white" method="POST" action="{{ route('users.updateStatus', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="modal-header border-border-color-40 p-20">
                <h1 class="modal-title fs-18 fw-medium mb-0" id="statusModalLabel">Update User Status</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-20 pb-0">
                <div class="row">
                    <div class="col-lg-12 mb-20">
                        <label class="label fs-16 mb-2">Change Status</label>
                        <select class="form-select form-control" name="status">
                            <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="2" {{ $user->status == 2 ? 'selected' : '' }}>Inactive</option>
                            <option value="3" {{ $user->status == 3 ? 'selected' : '' }}>Blocked</option>
                            <option value="4" {{ $user->status == 4 ? 'selected' : '' }}>Locked</option>
                        </select>
                    </div>

                    <div class="col-lg-12 mb-20">
                        <label class="label fs-16 mb-2">Reason</label>
                        <textarea name="remark" class="form-control" rows="3" placeholder="Enter Reason here...">{{ $user->remark ?? '' }}</textarea>
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
