@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Branch Details</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('branches.index') }}" class="text-decoration-none">Branch List</a>
                    </li>
                    <li class="breadcrumb-item active">Branch Details</li>
                </ol>
            </nav>
        </div>

        {{-- Branch Details Cards --}}
        <div class="row g-3">

            {{-- General Information --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4 shadow-sm">
                    <h5 class="mb-3">General Information</h5>
                    <hr>
                    <p><strong>Branch Code:</strong> {{ $branch->code ?? '-' }}</p>
                    <p><strong>Branch Name:</strong> {{ $branch->branch_name ?? '-' }}</p>
                    <p><strong>Branch Type:</strong> {{ $branch->branch_type ?? '-' }}</p>
                    <p><strong>Status:</strong>
                        @if ($branch->status === 'Active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </p>
                    <p><strong>Created At:</strong> {{ formatDate($branch->created_at) }}</p>
                    <p><strong>Created By:</strong> {{ $branch->createdBy?->full_name ?? '-' }}</p>
                 <p><strong>Updated At:</strong> {{ formatDate($branch->updated_at) }}</p>
                    <p><strong>Updated By:</strong> {{ $branch->updatedBy?->full_name ?? '-' }}</p>
                </div>
            </div>

            {{-- Address Information --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4 shadow-sm">
                    <h5 class="mb-3">Address Information</h5>
                    <hr>
                    <p><strong>Street / Area:</strong> {{ $branch->address_line1 ?? '-' }}</p>
                    <p><strong>Landmark:</strong> {{ $branch->address_line2 ?? '-' }}</p>
                    <p><strong>City:</strong> {{ $branch->city?->name ?? '-' }}</p>
                    <p><strong>State:</strong> {{ $branch->state?->name ?? '-' }}</p>
                    <p><strong>Country:</strong> {{ $branch->country?->name ?? '-' }}</p>
                    <p><strong>Pincode:</strong> {{ $branch->pin_code ?? '-' }}</p>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4 shadow-sm">
                    <h5 class="mb-3">Contact Information</h5>
                    <hr>
                    <p><strong>Mobile:</strong> {{ $branch->mobile ?? '-' }}</p>
                    <p><strong>Landline:</strong> {{ $branch->phone ?? '-' }}</p>
                    <p><strong>Email:</strong> {{ $branch->email ?? '-' }}</p>
                </div>
            </div>

            {{-- Tax & IDs --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4 shadow-sm">
                    <h5 class="mb-3">Tax & IDs</h5>
                    <hr>
                    <p><strong>GST Number:</strong> {{ $branch->gst_number ?? '-' }}</p>
                    <p><strong>PAN Number:</strong> {{ $branch->pan_number ?? '-' }}</p>
                    {{-- <p><strong>Manager Employee ID:</strong> {{ $branch->manager_employee_id ?? '-' }}</p>
                <p><strong>Parent Branch ID:</strong> {{ $branch->parent_branch_id ?? '-' }}</p>
                <p><strong>Currency ID:</strong> {{ $branch->currency_id ?? '-' }}</p> --}}
                </div>
            </div>

            {{-- Notes --}}
            <div class="col-lg-12">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4 shadow-sm">
                    <h5 class="mb-3">Notes</h5>
                    <hr>
                    <p>{{ $branch->notes ?? '-' }}</p>
                </div>
            </div>

        </div>
    </div>
@endsection
