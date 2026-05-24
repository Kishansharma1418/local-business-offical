@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Add Document</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('documents.index') }}" class="text-decoration-none">Documents List</a>
                    </li>
                    <li class="breadcrumb-item active">Add Document</li>
                </ol>
            </nav>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation"
            novalidate>
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Document Information</h3>
                        <div class="row">

                            {{-- Employee --}}
                            {{-- <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select form-control" required>
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                            {{-- Document Type --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Document Type <span class="text-danger">*</span></label>
                                <select name="document_type" class="form-select form-control" required>
                                    <option value="">Select Document Type</option>
                                    @foreach (['Aadhaar', 'PAN', 'Passport', 'OfferLetter', 'AppointmentLetter', 'ExperienceLetter', 'RelievingLetter', 'Resume', 'EducationCertificate', 'AddressProof', 'Other'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('document_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>




                            {{-- Upload Document --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Upload Document <span class="text-danger">*</span></label>
                                <input type="file" name="document_file" class="form-control" required>
                            </div>

                            {{-- Status --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select form-control" required>
                                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            {{-- Notes --}}
                            {{-- <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('remark') }}</textarea>
                        </div> --}}

                            {{-- Actions --}}
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-normal text-white">+ Add
                                        Document</button>
                                    <a href="{{ route('documents.index') }}"
                                        class="btn btn-danger fw-normal text-white">Cancel</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
