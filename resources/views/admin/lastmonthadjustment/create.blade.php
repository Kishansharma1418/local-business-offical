@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Add Last Month Adjustment </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('last-adjustments.index') }}" class="text-decoration-none">Adjustment List</a>
                    </li>
                    <li class="breadcrumb-item active">Add Adjustment </li>
                </ol>
            </nav>
        </div>

        {{-- Validation --}}
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

        <form method="POST" action="{{ route('last-adjustments.store') }}" class="needs-validation" novalidate>
            @csrf

            <div class="card bg-white p-20 rounded-10 border mb-4">
                <h4 class="mb-3">Adjustment Info</h4>

                <div class="row">

                    {{-- Employee ID --}}
                    @auth
                        @if (auth()->user()->user_type === 'admin')
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">
                                    Employee <span class="text-danger">*</span>
                                </label>

                                <select name="employee_id" class="form-control" required>
                                    <option value="">Select Employee</option>

                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    @endauth


                    {{-- Adjustment Month --}}
                    <div class="col-lg-6 mb-20">
                        <label class="label">Adjustment Month <span class="text-danger">*</span></label>
                        <input type="month" class="form-control" name="adjustment_month"
                            value="{{ old('adjustment_month') }}" required>
                    </div>

                    {{-- Current Month --}}
                    <div class="col-lg-6 mb-20">
                        <label class="label">Current Month <span class="text-danger">*</span></label>
                        <input type="month" class="form-control" name="current_month" value="{{ old('current_month') }}"
                            required>
                    </div>

                    {{-- Amount --}}
                    <div class="col-lg-6 mb-20">
                        <label class="label">Adjustment Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="adjustment_amount"
                            value="{{ old('adjustment_amount') }}" required min='0'>
                    </div>

                    {{-- Status --}}
                    <div class="col-lg-6 mb-20">
                        <label class="label">Status</label>
                        <select class="form-control" name="status">
                            <option value="Credit" {{ old('status') == 'Credit' ? 'selected' : '' }}>Credit</option>
                            <option value="Debit" {{ old('status') == 'Debit' ? 'selected' : '' }}>Debit</option>
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="col-lg-6 mb-20">
                        <label class="label">Description</label>
                        <textarea class="form-control" rows="2" name="description">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary fw-normal text-white">+ Add Adjustment</button>
                        <a href="{{ route('last-adjustments.index') }}"
                            class="btn btn-danger fw-normal text-white">Cancel</a>
                    </div>

                </div>
            </div>
        </form>
    </div>

@endsection
