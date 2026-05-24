@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0"> Add Customer Contact</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('customers.index') }}" class="text-decoration-none text-body fs-14 hover">
                            Customer List
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="text-secondary"> Customer Contact</span>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('customer.contactstore.store') }}" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="customer_id" value="{{ encrypt($customer_id) }}">


            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <h3 class="mb-3">Customer Contact Information</h3>
                <div class="row">


                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Contact Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('contact_name') is-invalid @enderror"
                            name="contact_name" value="{{ old('contact_name') }}" required>
                        @error('contact_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Mobile No <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="mobile_no" value="{{ old('mobile_no') }}"
                            maxlength="10" required>
                    </div>


                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Designation</label>
                        <input type="text" class="form-control " name="designation" value="{{ old('designation') }}">

                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Is Default</label>
                        <select class="form-control" name="is_default" id="is_default">
                            <option value="0" {{ old('is_default') == '0' ? 'selected' : '' }}>No</option>
                            <option value="1" {{ old('is_default') == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-normal text-white">+ Add
                                Customer Contact</button>
                             <a href="{{ route('customers.index') }}"
                                        class="btn btn-danger fw-normal text-white">Cancel</a>
                        </div>
                    </div>


                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
@endpush
