@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Salary Component</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('salary-component.index') }}" class="text-decoration-none">Salary Component
                            List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Salary Component</li>
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

        <form method="POST" action="{{ route('salary-component.update', encrypt($salaryComponent->id)) }}"
            class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Salary Component Information</h3>
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="component_name" class="form-label">Component Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="component_name" id="component_name" class="form-control"
                                    placeholder="Enter component name" required
                                    value="{{ old('component_name', $salaryComponent->component_name) }}">
                                <div class="invalid-feedback">Please enter a component name.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="component_type" class="form-label">Component Type <span
                                        class="text-danger">*</span></label>
                                <select name="component_type" id="component_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="Earning"
                                        {{ old('component_type', $salaryComponent->component_type) == 'Earning' ? 'selected' : '' }}>
                                        Earning</option>
                                    <option value="Deduction"
                                        {{ old('component_type', $salaryComponent->component_type) == 'Deduction' ? 'selected' : '' }}>
                                        Deduction</option>
                                </select>
                                <div class="invalid-feedback">Please select a component type.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="calculation_type" class="form-label">Calculation Type <span
                                        class="text-danger">*</span></label>
                                <select name="calculation_type" id="calculation_type" class="form-control" required>
                                    <option value="">Select Calculation Type</option>
                                    <option value="Fixed"
                                        {{ old('calculation_type', $salaryComponent->calculation_type) == 'Fixed' ? 'selected' : '' }}>
                                        Fixed</option>
                                    <option value="Percentage"
                                        {{ old('calculation_type', $salaryComponent->calculation_type) == 'Percentage' ? 'selected' : '' }}>
                                        Percentage</option>
                                </select>
                                <div class="invalid-feedback">Please select a calculation type.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="based_component_id" class="form-label">Based On Component</label>
                                <select name="based_component_id" id="based_component_id" class="form-control">
                                    <option value="">Select Component</option>
                                    @foreach ($components ?? [] as $component)
                                        <option value="{{ $component->id }}"
                                            {{ old('based_component_id', $salaryComponent->based_component_id) == $component->id ? 'selected' : '' }}>
                                            {{ $component->component_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- 
                            <div class="col-md-6 mb-3">
                                <label for="is_taxable" class="form-label">Is Taxable</label>
                                <select name="is_taxable" id="is_taxable" class="form-control">
                                    <option value="0" {{ old('is_taxable', $salaryComponent->is_taxable) == '0' ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('is_taxable', $salaryComponent->is_taxable) == '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div> --}}

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1"
                                        {{ old('status', $salaryComponent->status) == '1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0"
                                        {{ old('status', $salaryComponent->status) == '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-normal text-white">
                                        Update Salary Component
                                    </button>
                                    <a href="{{ route('salary-component.index') }}"
                                        class="btn btn-danger fw-normal text-white">Cancel
                                    </a>
                                </div>
                            </div>

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
