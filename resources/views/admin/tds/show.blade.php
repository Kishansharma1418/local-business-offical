@extends('include.master')

@section('content')

<div class="main-content-container overflow-hidden">

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
    <h3 class="mb-0">Employee TDS Details</h3>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb align-items-center mb-0 lh-1">

            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                    <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                    <span class="text-body fs-14 hover">Dashboard</span>
                </a>
            </li>

            <li class="breadcrumb-item">Created At
                <a href="{{ route('tds.index') }}" class="text-decoration-none">
                    TDS List
                </a>
            </li>

            <li class="breadcrumb-item active">
                TDS Details
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

            <p><strong>Employee Name:</strong> {{ $tds->employee->full_name ?? '-' }}</p>

            <p><strong>Employee Code:</strong> {{ $tds->employee->code ?? '-' }}</p>

            <p><strong>Employee Type:</strong> {{ $tds->employee->role ?? '-' }}</p>

            <p><strong>Branch:</strong> {{ $tds->employee->branches->branch_name ?? '-' }}</p>

            <p><strong>Mobile:</strong> {{ $tds->employee->mobile_no ?? '-' }}</p>

        </div>
    </div>



    {{-- TDS Information --}}
    <div class="col-lg-6">
        <div class="card bg-white p-20 rounded-10 border border-white mb-4 shadow-sm">

            <h5 class="mb-3">TDS Information</h5>
            <hr>

            <p><strong>Financial Year:</strong> {{ $tds->financial_year ?? '-' }}</p>

            <p>
                <strong>Month:</strong>
                {{ $tds->month ? \Carbon\Carbon::parse($tds->month)->format('M Y') : '-' }}
            </p>

            <p>
                <strong>Gross Salary:</strong>
                ₹ {{ number_format($tds->gross_salary,2) }}
            </p>

            <p>
                <strong>Taxable Salary:</strong>
                ₹ {{ number_format($tds->taxable_salary,2) }}
            </p>

            <p>
                <strong>TDS %:</strong>
                {{ $tds->tds_percent }} %
            </p>

            <p>
                <strong>TDS Amount:</strong>
                ₹ {{ number_format($tds->tds_amount,2) }}
            </p>

            <p>
                <strong>Remark:</strong>
                {{ $tds->remark ?? '-' }}
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
                 {{ formatDate($tds->created_at) }}
            </p>

            <p>
                <strong>Updated At:</strong>
                {{ formatDate($tds->updated_at) }}
            </p>

        </div>
    </div>


</div>


</div>

@endsection
