@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Add Employee Salary</h3>

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
                        <span class="text-secondary">Add Employee Salary</span>
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

        <form method="POST" action="{{ route('employee.salary.store') }}" class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee_id }}">

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <h3 class="mb-3">Employee Salary Information</h3>
                <div class="row">
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Salary Component <span class="text-danger">*</span></label>
                        <select name="component_id" class="form-control" required>
                            <option value="">Select Component</option>
                            @foreach ($components as $component)
                                <option value="{{ $component->id }}"
                                    {{ old('component_id', $employeSalary->component_id ?? '') == $component->id ? 'selected' : '' }}>
                                    {{ $component->component_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Amount</label>
                        <input type="number" class="form-control" name="amount"
                            value="{{ old('amount', $employeSalary->amount ?? '') }}" step="0.01">
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Percentage</label>
                        <input type="number" class="form-control" name="percentage"
                            value="{{ old('percentage', $employeSalary->percentage ?? '') }}" step="0.01">
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Effective From <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="effactive_from"
                            value="{{ old('effactive_from', $employeSalary->effactive_from ?? '') }}" required>
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Effective To</label>
                        <input type="date" class="form-control" name="effactive_to"
                            value="{{ old('effactive_to', $employeSalary->effactive_to ?? '') }}">
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Status</label>
                        <select name="status" class="form-control">
                            <option value="1"
                                {{ old('status', $employeSalary->status ?? '') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0"
                                {{ old('status', $employeSalary->status ?? '') == '0' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-12 mb-20">
                        <label class="label fs-16 mb-2">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $employeSalary->remarks ?? '') }}</textarea>
                    </div>

                    <div class="col-lg-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-normal text-white">
                                {{ $employeSalary ? 'Update Employee Salary' : '+ Add Employee Salary' }}
                            </button>
                            <a href="{{ route('employee.index') }}" class="btn btn-danger fw-normal text-white">Cancel</a>
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
                $(this).find('button[type="submit"]').prop('disabled', true).text('Processing...');
            });
        });
    </script>
@endpush
