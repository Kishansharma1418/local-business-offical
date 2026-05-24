@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Last Month Adjustment </h3>
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
                    <li class="breadcrumb-item active">Edit Adjustment </li>
                </ol>
            </nav>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>
        @endif

        <form method="POST" action="{{ route('last-adjustments.update', $adjustment->id) }}" class="needs-validation"
            novalidate>
            @csrf
            @method('PUT')

            <div class="card bg-white p-20 rounded-10 border mb-4">
                <h4 class="mb-3">Adjustment Info</h4>

                <div class="row">

                    {{-- Employee ID --}}
                    @auth
                        @if (auth()->user()->user_type === 'admin')
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Employee <span class="text-danger">*</span></label>
                                <select name="employee_id" class="form-control" required>
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ old('employee_id', $adjustment->employee_id) == $employee->id ? 'selected' : '' }}>
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
                            value="{{ old('adjustment_month', $adjustment->adjustment_month) }}" required>
                    </div>

                    {{-- Current Month --}}
                    <div class="col-lg-6 mb-20">
                        <label class="label">Current Month <span class="text-danger">*</span></label>
                        <input type="month" class="form-control" name="current_month"
                            value="{{ old('current_month', $adjustment->current_month) }}" required>
                    </div>

                    {{-- Amount --}}
                    <div class="col-lg-6 mb-20">
                        <label class="label">Adjustment Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="adjustment_amount"
                            value="{{ old('adjustment_amount', $adjustment->adjustment_amount) }}" required min='0'>
                    </div>

                    {{-- Status --}}
                    <div class="col-lg-6 mb-20">
                        <label class="label">Status</label>
                        <select class="form-control" name="status">
                            <option value="Credit" {{ $adjustment->status == 'Credit' ? 'selected' : '' }}>Credit</option>
                            <option value="Debit" {{ $adjustment->status == 'Debit' ? 'selected' : '' }}>Debit</option>
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="col-lg-6 mb-20">
                        <label class="label">Description</label>
                        <textarea class="form-control" rows="2" name="description">{{ old('description', $adjustment->description) }}</textarea>
                    </div>

                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary fw-normal text-white">Update Adjustment</button>
                        <a href="{{ route('last-adjustments.index') }}"
                            class="btn btn-danger f-normal text-white">Cancel</a>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection
