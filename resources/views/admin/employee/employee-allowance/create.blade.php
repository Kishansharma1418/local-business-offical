@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Employee Allowance</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('employee.index') }}" class="text-decoration-none text-body fs-14 hover">
                            Employee List
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="text-secondary">Employee Allowance</span>
                    </li>
                </ol>
            </nav>
        </div>

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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('employee.allowance.store') }}"
            class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee_id }}">

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <h3 class="mb-3">Allowance Information - {{ $employee->full_name ?? 'N/A' }}</h3>
                <div class="row">

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">HQ Allowance</label>
                        <input type="number" step="0.01" min="0"
                            class="form-control @error('hq') is-invalid @enderror"
                            name="hq"
                            value="{{ old('hq', $allowance->hq ?? '') }}"
                            placeholder="Enter HQ amount">
                        @error('hq')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Ex-Station Allowance</label>
                        <input type="number" step="0.01" min="0"
                            class="form-control @error('exst') is-invalid @enderror"
                            name="exst"
                            value="{{ old('exst', $allowance->exst ?? '') }}"
                            placeholder="Enter Ex-Station amount">
                        @error('exst')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Out-Station Allowance</label>
                        <input type="number" step="0.01" min="0"
                            class="form-control @error('outst') is-invalid @enderror"
                            name="outst"
                            value="{{ old('outst', $allowance->outst ?? '') }}"
                            placeholder="Enter Out-Station amount">
                        @error('outst')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Phone Allowance</label>
                        <input type="number" step="0.01" min="0"
                            class="form-control @error('phone') is-invalid @enderror"
                            name="phone"
                            value="{{ old('phone', $allowance->phone ?? '') }}"
                            placeholder="Enter Phone amount">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Hotel Allowance</label>
                        <input type="number" step="0.01" min="0"
                            class="form-control @error('hotel') is-invalid @enderror"
                            name="hotel"
                            value="{{ old('hotel', $allowance->hotel ?? '') }}"
                            placeholder="Enter Hotel amount">
                        @error('hotel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-normal text-white">
                                {{ $allowance ? 'Update Allowance' : '+ Add Allowance' }}
                            </button>
                            <a href="{{ route('employee.index') }}"
                                class="btn btn-danger fw-normal text-white">Cancel</a>
                        </div>
                    </div>

                </div>
            </div>
        </form>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(e) {
                let form = $(this)[0];
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).addClass('was-validated');
                    return false;
                }
                $(this).find('button[type="submit"]')
                    .prop('disabled', true)
                    .text('Processing...');
            });
        });
    </script>
@endpush