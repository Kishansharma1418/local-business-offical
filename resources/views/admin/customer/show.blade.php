@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        {{-- Header & Breadcrumb --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Customer Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('customers.index') }}" class="text-decoration-none">Customers</a>
                    </li>
                    <li class="breadcrumb-item active">View Customer</li>
                </ol>
            </nav>
        </div>

        {{-- Main Card --}}
        <div class="card bg-white p-4 rounded-10 border border-light shadow-sm mb-4">
            <div class="card-body">

                {{-- Header Title Section --}}
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="mb-0">
                        <i class="ri-user-3-line me-2 text-primary"></i>
                        {{ $customer->name }}
                    </h4>

                    <span class="badge {{ $customer->status ? 'bg-success' : 'bg-danger' }}">
                        {{ $customer->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="row g-4">

                    {{-- Basic Info --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="mb-3 text-primary">Customer Basic Information</h5>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Customer Code:</strong> {{ $customer->code }}</li>
                                <li><strong>Customer Type:</strong> {{ ucfirst($customer->customer_type) }}</li>
                                <li><strong>Contact Person:</strong> {{ $customer->contact_person }}</li>
                                <li><strong>Mobile No:</strong> {{ $customer->mobile_no }}</li>
                                <li><strong>Email:</strong> {{ $customer->email }}</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Finance Info --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="mb-3 text-primary">Financial Details</h5>
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Place of supply:</strong>
                                    {{ $customer->states ? $customer->states->name . ' (' . $customer->states->iso2 . ')' : '-' }}
                                </li>

                                <li><strong>GST Type:</strong> {{ $customer->gst_type ?? '-' }}</li>
                                <li><strong>GST No:</strong> {{ $customer->gst_no ?? '-' }}</li>
                                <li><strong>PAN No:</strong> {{ $customer->pan_no ?? '-' }}</li>
                                <li><strong>Credit Limit:</strong> ₹{{ number_format($customer->credit_limit ?? 0, 2) }}
                                </li>
                                <!-- <li><strong>Credit Days:</strong> {{ $customer->credit_days ?? '—' }} Days</li> -->
                                <li>
                                    <strong>Payment Terms:</strong>

                                    {{ $customer->paymentTerm
                                        ? $customer->paymentTerm->name .
                                            ($customer->paymentTerm->days ? ' (' . $customer->paymentTerm->days . ' Days)' : '')
                                        : '-' }}
                                </li>


                                <li><strong>Login Access:</strong>
                                    @if ($customer->is_login)
                                        <span class="badge bg-success">Enabled</span>
                                    @else
                                        <span class="badge bg-secondary">Disabled</span>
                                    @endif
                                </li>

                                <li><strong>Blocked:</strong>
                                    @if ($customer->is_blocked)
                                        <span class="badge bg-danger">Blocked</span>
                                    @else
                                        <span class="badge bg-success">Not Blocked</span>
                                    @endif
                                </li>

                                @if ($customer->is_blocked && $customer->blocked_reason)
                                    <li><strong>Blocked Reason:</strong> {{ $customer->blocked_reason }}</li>
                                @endif

                            </ul>
                        </div>
                    </div>

                    {{-- Address Details --}}


                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="mb-3 text-primary">Billing Address</h5>

                            @if ($customer->billingAddress)
                                <ul class="list-unstyled mb-0">
                                    <li><strong>Address Line 1:</strong> {{ $customer->billingAddress->address_line1 }}
                                    </li>
                                    <li><strong>Address Line 2:</strong>
                                        {{ $customer->billingAddress->address_line2 ?? '-' }}</li>
                                    <li><strong>City:</strong> {{ $customer->billingAddress->cities?->name ?? '-' }}</li>
                                    <li><strong>State:</strong> {{ $customer->billingAddress->states?->name ?? '-' }}</li>
                                    <li><strong>Country:</strong> {{ $customer->billingAddress->countries?->name ?? '-' }}
                                    </li>
                                    <li><strong>Pincode:</strong> {{ $customer->billingAddress->pincode ?? '-' }}</li>
                                </ul>
                            @else
                                <span class="text-muted">Billing address not available</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="mb-3 text-primary">Shipping Address</h5>

                            @if ($customer->shippingAddress)
                                <ul class="list-unstyled mb-0">
                                    <li><strong>Address Line 1:</strong> {{ $customer->shippingAddress->address_line1 }}
                                    </li>
                                    <li><strong>Address Line 2:</strong>
                                        {{ $customer->shippingAddress->address_line2 ?? '-' }}</li>
                                    <li><strong>City:</strong> {{ $customer->shippingAddress->cities?->name ?? '-' }}</li>
                                    <li><strong>State:</strong> {{ $customer->shippingAddress->states?->name ?? '-' }}</li>
                                    <li><strong>Country:</strong> {{ $customer->shippingAddress->countries?->name ?? '-' }}
                                    </li>
                                    <li><strong>Pincode:</strong> {{ $customer->shippingAddress->pincode ?? '-' }}</li>
                                </ul>
                            @else
                                <span class="text-muted">Shipping address not available</span>
                            @endif
                        </div>
                    </div>


                    {{-- Record Info --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="mb-3 text-primary">Record Details</h5>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Created By:</strong> {{ $customer->createdBy?->full_name ?? '-' }}</li>
                                <li><strong>Updated By:</strong> {{ $customer->updatedBy?->full_name ?? '-' }}</li>
                                <li><strong>Last Updated:</strong>
                                   {{ formatDate($customer->updated_at, 'd-m-Y h:i A') }}</li>
                            </ul>
                        </div>
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="col-lg-12 mt-3">
                    <div class="d-flex gap-2">
                        <a href="{{ route('customers.index') }}" class="btn btn-danger fw-normal text-white">
                            <i class="ri-arrow-left-line me-1"></i> Back
                        </a>

                        <a href="{{ route('customers.edit', encrypt($customer->id)) }}"
                            class="btn btn-primary fw-normal text-white">
                            <i class="ri-edit-2-line me-1"></i> Edit Customer
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
