@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    {{-- Header & Breadcrumb --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Vendor Details</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('vendor.index') }}" class="text-decoration-none">Vendor</a>
                </li>
                <li class="breadcrumb-item active">View Vendor</li>
            </ol>
        </nav>
    </div>

    {{-- Vendor Details Card --}}
    <div class="card bg-white p-4 rounded-10 border border-light shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0">
                    <i class="ri-user-3-line me-2 text-primary"></i>
                    {{ $vendor->name }}
                </h4>
                <span class="badge {{ $vendor->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                    {{ $vendor->status == 'active' ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <div class="row g-4">
                {{-- Basic Info --}}
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h5 class="mb-3 text-primary">Personal Information</h5>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Vendor Code:</strong> {{ $vendor->code }}</li>
                            <li><strong>Vendor Type:</strong> {{ $vendor->vendor_type }}</li>
                            <li><strong>Contact Person:</strong> {{ $vendor->contact_person }}</li>
                            <li><strong>GST Number:</strong> {{ $vendor->gst_no ?? '-' }}</li>
                            <li><strong>PAN Number:</strong> {{ $vendor->pan_no ?? '-' }}</li>
                            <li><strong>Payment Terms:</strong> {{ optional($vendor->paymentTerm)->days ?? '-' }} {{ optional($vendor->paymentTerm)->name ?? '-' }}</li>

                        </ul>

                    </div>
                </div>

                {{-- Contact Info --}}
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h5 class="mb-3 text-primary">Contact Information</h5>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Official Email:</strong> {{ $vendor->email }}</li>
                            <li><strong>Mobile No:</strong> {{ $vendor->phone }}</li>

                        </ul>
                    </div>
                </div>


                {{-- Address Details --}}
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h5 class="mb-3 text-primary">Address Details</h5>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Address Line 1:</strong> {{ $vendor->address_line1 ?? '-' }}</li>
                            <li><strong>Address Line 2:</strong> {{ $vendor->address_line2 ?? '-' }}</li>
                            <li><strong>City:</strong> {{ optional($vendor->cities)->name ?? '-' }}</li>
                            <li><strong>State:</strong> {{ optional($vendor->states)->name ?? '-' }}</li>
                            <li><strong>Country:</strong> {{ optional($vendor->countries)->name ?? '-' }}</li>
                            <li><strong>Pincode:</strong> {{ $vendor->pincode ?? '-' }}</li>
                        </ul>
                    </div>
                </div>


            </div>



            <div class="col-lg-12 mt-3">
                <div class="d-flex gap-2">

                    <a href="{{ route('vendor.index') }}" class="btn btn-danger fw-normal text-white">
                        <i class="ri-arrow-left-line me-1"></i> Back
                    </a>
                    <a href="{{ route('vendor.edit', encrypt($vendor->id)) }}"
                        class="btn btn-primary fw-normal text-white">
                        <i class="ri-edit-2-line me-1"></i> Edit Vendor
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection