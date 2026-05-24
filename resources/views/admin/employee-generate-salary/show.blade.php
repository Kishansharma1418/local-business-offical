@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Employee Salary Details</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('salary-generate.index') }}" class="text-decoration-none">Salary List</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Salary Details</li>



                </ol>
            </nav>
        </div>


        <div class="card bg-white rounded-10 border border-white p-4 shadow-sm">
            <!-- Header Section -->
            <div class="row mb-4 border-bottom pb-3">

                <div class="col-md-8">
                    <h5 class="text-primary fw-bold mb-2">Employee Information</h5>
                    <p class="mb-1"><strong>Name:</strong> {{ $salary->employee->full_name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Employee Code:</strong> {{ $salary->employee->code ?? 'N/A' }}</p>
                    <a href="{{ route('salary.pdf', $salary->id) }}" class="btn btn-primary btn-sm text-white mt-2">
                        <i class="bi bi-download"></i> Download PDF
                    </a>
                </div>
                <div class="col-md-4 text-md-end text-center">

                    <h5 class="text-primary fw-bold mb-2">Salary Month</h5>
                    <p class="mb-1"><strong>Month:</strong>
                        {{ \Carbon\Carbon::create()->month($salary->month)->format('F') }}</p>
                    <p class="mb-1"><strong>Year:</strong> {{ $salary->year }}</p>
                    <p class="mb-0"><strong>Status:</strong>
                        <span class="badge bg-{{ $salary->status == 'Paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($salary->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <h5 class="text-primary fw-bold mb-3">Salary Summary</h5>
                    <table class="table table-bordered align-middle mb-0 bg-white">
                        <tbody>
                            <tr>
                                <th width="30%">Basic Salary</th>
                                <td><span class="text-success fs-20 font-weight-bold"> +</span>
                                    ₹{{ number_format($salary->basic_salary, 2) }}</td>
                            </tr>

                            <tr>
                                <th width="30%">HRA Amount</th>
                                <td><span class="text-success fs-20 font-weight-bold"> +</span>
                                    ₹{{ number_format($salary->hra_amount, 2) }}</td>
                            <tr>

                            <tr>
                                <th width="30%">Conveyance Amount</th>
                                <td><span class="text-success fs-20 font-weight-bold"> +</span>
                                    ₹{{ number_format($salary->conveyance_amount, 2) }}</td>

                            </tr>
                            <tr>
                                <th>Gross Salary</th>
                                <td> ₹{{ number_format($salary->gross_salary, 2) }}</td>
                            </tr>
                            <tr>
                                <th width="30%">Leaves Adjustment</th>
                                <td><span class="text-danger fs-20 font-weight-bold"> -</span>
                                    ₹{{ number_format($salary->leave_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th width="30%">TDS Amount</th>
                                <td><span class="text-danger fs-20 font-weight-bold"> -</span>
                                    ₹{{ number_format($salary->tds_amount, 2) }}</td>
                            </tr>

                            <tr>
                                <th width="30%">PF Amount</th>
                                <td><span class="text-danger fs-20 font-weight-bold"> -</span>
                                    ₹{{ number_format($salary->pf_amount, 2) }}</td>
                            </tr>

                            <tr>
                                <th width="30%">ESI Amount</th>
                                <td><span class="text-danger fs-20 font-weight-bold"> -</span>
                                    ₹{{ number_format($salary->esi_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th width="30%">Expense</th>
                                <td><span class="text-success fs-20 font-weight-bold"> +</span>
                                    ₹{{ number_format($salary->expense_total, 2) }}</td>
                            </tr>

                            @if($salary->bounnce_employee && $salary->bounnce_employee > 0)
                            <tr>
                                <th width="30%">Bonus</th>
                                <td><span class="text-success fs-20 font-weight-bold"> +</span>
                                    ₹{{ number_format($salary->bounnce_employee, 2) }}</td>
                            </tr>
                            @endif

                            <tr>
                                <th width="30%">Loan Deduction Amount</th>
                                <td><span class="text-danger fs-20 font-weight-bold"> -</span>
                                    ₹{{ number_format($salary->loan_amount_deduction, 2) }}</td>
                            </tr>
                            <tr>
                                <th width="30%">Last Month Adjustment</th>
                                <td>
                                    @if ($salary->last_month_adjustment >= 0)
                                        <span class="text-success fs-20 font-weight-bold"> +</span>
                                        ₹{{ number_format($salary->last_month_adjustment, 2) }}
                                    @else
                                        <span class="text-danger fs-20 font-weight-bold"> -</span>
                                        ₹{{ number_format(abs($salary->last_month_adjustment), 2) }}
                                    @endif
                                </td>


                            <tr>
                                <th>Net Salary</th>
                                <td class="fw-bold text-success">₹{{ number_format($salary->net_salary, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <h5 class="mb-3">Attendance Summary</h5>
                    <table class="table table-bordered text-center align-middle bg-white">
                        <thead class="table-light">
                            <tr>
                                <th>Present Days</th>
                                <th>Leave Days</th>
                                <th>Weekly Off</th>
                                <th>Half Day</th>
                                <th>Holiday</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $salary->present_days }}</td>
                                <td>{{ $salary->absent_days }}</td>
                                <td>{{ $salary->weekly_off }}</td>
                                <td>{{ $salary->half_day }}</td>
                                <td>{{ $salary->holiday }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Optional buttons --}}
            {{-- <div class="d-flex justify-content-start align-items-center gap-2 mt-3">
                <button onclick="window.print()" class="btn btn-primary fw-normal text-white">
                    <i class="bi bi-printer"></i> Print Salary Slip
                </button>
                <a href="{{ route('salary-generate.index') }}" class="btn btn-secondary fw-normal text-white">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div> --}}
        </div>
    </div>
@endsection
@push('styles')
    <style>
        table.table,
        table.table th,
        table.table td {
            background-color: #ffffff !important;
        }
    </style>
@endpush
