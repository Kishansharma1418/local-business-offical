@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Broker Details</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('brokers.index') }}" class="text-decoration-none">
                            Broker List
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Broker Details</li>
                </ol>
            </nav>
        </div>

        <div class="row g-3">

            {{-- General Information --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 shadow-sm">
                    <h5 class="mb-3">General Information</h5>
                    <hr>
                    <p><strong>Broker Code:</strong> {{ $broker->code ?? '-' }}</p>
                    <p><strong>Broker Name:</strong> {{ $broker->broker_name ?? '-' }}</p>
                    <p><strong>Contact Person:</strong> {{ $broker->contact_person ?? '-' }}</p>

                    <p><strong>Status:</strong>
                        @if ($broker->status === 'Active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </p>



                </div>
            </div>

            {{-- Contact Information --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 shadow-sm">
                    <h5 class="mb-3">Contact Information</h5>
                    <hr>
                    <p><strong>Mobile:</strong> {{ $broker->mobile_no ?? '-' }}</p>
                    <p><strong>Email:</strong> {{ $broker->email ?? '-' }}</p>
                    <p><strong>Created At:</strong>
                       {{ formatDate($broker->created_at) }}
                    </p>
                    <p><strong>Updated At:</strong>
                        {{ formatDate($broker->updated_at) }}
                    </p>
                </div>
            </div>

            {{-- Address Information --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 shadow-sm">
                    <h5 class="mb-3">Address Information</h5>
                    <hr>
                    <p><strong>Address Line 1:</strong> {{ $broker->address_line1 ?? '-' }}</p>
                    <p><strong>Address Line 2:</strong> {{ $broker->address_line2 ?? '-' }}</p>
                    <p><strong>City:</strong> {{ $broker->city?->name ?? '-' }}</p>
                    <p><strong>State:</strong> {{ $broker->state?->name ?? '-' }}</p>
                    <p><strong>Country:</strong> {{ $broker->country?->name ?? '-' }}</p>
                    <p><strong>Pincode:</strong> {{ $broker->pincode ?? '-' }}</p>
                </div>
            </div>

            {{-- Commission Details --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 shadow-sm">
                    <h5 class="mb-3">Commission Details</h5>
                    <hr>
                    <p><strong>Commission Type:</strong> {{ $broker->commission_type ?? '-' }}</p>
                    <p><strong>Commission Value:</strong>
                        @if ($broker->commission_type === 'Percentage')
                            {{ $broker->commission_value }}%
                        @else
                            ₹ {{ number_format($broker->commission_value, 2) }}
                        @endif
                    </p>
                </div>
            </div>

            {{-- Tax & IDs --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 shadow-sm">
                    <h5 class="mb-3">Tax & IDs</h5>
                    <hr>
                    <p><strong>GST Number:</strong> {{ $broker->gst_number ?? '-' }}</p>
                    <p><strong>PAN Number:</strong> {{ $broker->pan_number ?? '-' }}</p>
                </div>
            </div>

            {{-- Remarks --}}
            <div class="col-lg-12">
                <div class="card bg-white p-20 rounded-10 shadow-sm">
                    <h5 class="mb-3">Remarks</h5>
                    <hr>
                    <p>{{ $broker->remarks ?? '-' }}</p>
                </div>
            </div>

        </div>
    </div>
@endsection
