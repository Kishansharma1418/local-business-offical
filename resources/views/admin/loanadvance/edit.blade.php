@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Advance Salary </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('loan-advances.index') }}" class="text-decoration-none">Advance Salary List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Advance Salary </li>
                </ol>
            </nav>
        </div>

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

        <form action="{{ route('loan-advances.update', encrypt($loan->id)) }}" method="POST" class="needs-validation"
            novalidate>
            @csrf
            @method('PUT')
            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <h3 class="mb-20">Advance Salary Information</h3>
                <div class="row">
                    @auth
                        @if (auth()->user()->user_type === 'admin')
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Employee <span class="text-danger">*</span></label>
                                <select name="employee_id" class="form-control" required disabled>
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ old('employee_id', $loan->employee_id) == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @endauth

                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Advance Salary Amount <span class="text-danger">*</span></label>
                        <input type="number" name="loan_amount" min="0"
                            value="{{ old('loan_amount', $loan->loan_amount) }}" class="form-control" required disabled>
                    </div>

                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Month <span class="text-danger">*</span></label>
                        <select name="month" class=" form-control" required disabled>
                            <option value="">Select Month</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}"
                                    {{ old('month', $loan->month) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>


                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Start Month <span class="text-danger">*</span></label>
                        <input type="month" name="start_month" disabled
                            value="{{ old('start_month', isset($loan) ? \Carbon\Carbon::parse($loan->start_month)->format('Y-m') : '') }}"
                            class="form-control" required min="{{ date('Y-m') }}">
                    </div>


                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Deduction Amount <span class="text-danger">*</span></label>
                        <input type="number" name="deduction_amount" min="0" step="0.01" disabled
                            value="{{ old('deduction_amount', $loan->deduction_amount) }}" class="form-control" required>
                    </div>

                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select form-control" required>
                            <option value="Active" {{ old('status', $loan->status) == 'Active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="Inactive" {{ old('status', $loan->status) == 'Inactive' ? 'selected' : '' }}>
                                Inactive</option>
                        </select>
                    </div>

                    <div class="col-lg-12 mt-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-normal text-white">Update Advance Salary </button>
                        <a href="{{ route('loan-advances.index') }}" class="btn btn-danger fw-normal text-white">Cancel</a>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        function calculateDeduction() {
            let loanAmount = parseFloat(document.querySelector('input[name="loan_amount"]').value);
            let months = parseInt(document.querySelector('select[name="month"]').value);

            if (!isNaN(loanAmount) && !isNaN(months) && months > 0) {
                let deduction = loanAmount / months;
                document.querySelector('input[name="deduction_amount"]').value = deduction.toFixed(2);
            } else {
                document.querySelector('input[name="deduction_amount"]').value = '';
            }
        }

        // Event listeners
        document.querySelector('input[name="loan_amount"]').addEventListener('input', calculateDeduction);
        document.querySelector('select[name="month"]').addEventListener('change', calculateDeduction);

        // Page load me pehle calculation
        window.addEventListener('load', calculateDeduction);
    </script>
@endpush
