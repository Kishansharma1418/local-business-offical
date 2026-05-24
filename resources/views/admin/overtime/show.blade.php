@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Overtime Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('overtime.index') }}" class="text-decoration-none">
                            Overtime List
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        Overtime Details
                    </li>

                </ol>
            </nav>
        </div>

        <div class="row g-3">

            {{-- Employee Information --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4 shadow-sm">
                    <h5 class="fw-semibold mb-3">Employee Information</h5>
                    <hr>
                    <p><strong>Employee Name:</strong>
                        {{ $overtime->employee->full_name ?? '-' }}
                    </p>
                    <p><strong>Employee Code:</strong>
                        {{ $overtime->employee->code ?? '-' }}
                    </p>
                    <p><strong>Employee Type:</strong>
                        {{ $overtime->employee->role ?? '-' }}
                    </p>
                    <p><strong>Mobile:</strong>
                        {{ $overtime->employee->mobile_no ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- Overtime Information --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4 shadow-sm">
                    <h5 class="mb-3">Overtime Information</h5>
                    <hr>
                    <p>
                        <strong>Date:</strong>
                        {{ $overtime->date ? \Carbon\Carbon::parse($overtime->date)->format('d M, Y') : '-' }}
                    </p>

                    <p>
                        <strong>Overtime Hours:</strong>
                        {{ $overtime->hours ?? '-' }}
                    </p>

                    <p>
                        <strong>Rate Per Hour:</strong>
                        ₹ {{ number_format($overtime->rate_per_hour, 2) }}
                    </p>

                    <p>
                        <strong>Total Amount:</strong>
                        ₹ {{ number_format($overtime->total_amount, 2) }}
                    </p>

                    <p>
                        <strong>Remark:</strong>
                        {{ $overtime->remark ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- Audit Information --}}
            <div class="col-lg-6">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4 shadow-sm">
                    <h5 class="mb-3">Audit Information</h5>
                    <hr>
                    <p>
                        <strong>Created At:</strong>
                        {{ $overtime->created_at ? $overtime->created_at->format('d M, Y') : '-' }}
                    </p>
                </div>
            </div>

        </div>

    </div>
@endsection
