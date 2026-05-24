@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Department Details</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('departments.index') }}" class="text-decoration-none">Department List</a>
                </li>
                <li class="breadcrumb-item active">Department Details</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4">

        {{-- General Information --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">General Information</h5>
                <p><strong>Department Code:</strong> {{ $department->code ?? '-' }}</p>
                <p><strong>Department Name:</strong> {{ $department->department_name ?? '-' }}</p>
                <p><strong>Status:</strong>
                    @if($department->status == 'Active')
                    <span class="badge bg-success">Active</span>
                    @else
                    <span class="badge bg-danger">Inactive</span>
                    @endif
                </p>
               <p><strong>Created At:</strong> {{ formatDate($department->created_at) }}</p>
                <p><strong>Created By:</strong> {{ $department->createdBy?->full_name ?? '-' }}</p>
           <p><strong>Updated At:</strong> {{ formatDate($department->updated_at) }}</p>
                <p><strong>Updated By:</strong> {{ $department->updatedBy?->full_name ?? '-' }}</p>

            </div>
        </div>  

        {{-- Hierarchy --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Hierarchy</h5>
                <p><strong>Branch:</strong> {{ $department->branch?->branch_name ?? '-' }}</p>
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Contact Information</h5>
                <p><strong>Email:</strong> {{ $department->email ?? '-' }}</p>
                <p><strong>Phone:</strong> {{ $department->phone ?? '-' }}</p>
            </div>
        </div>

        {{-- Description --}}
        <div class="col-lg-6">
            <div class="card bg-white border rounded-10 p-4 shadow-sm">
                <h5 class="mb-3 fw-semibold">Description</h5>
                <p>{{ $department->description ?? '-' }}</p>
            </div>
        </div>



    </div>
</div>
@endsection