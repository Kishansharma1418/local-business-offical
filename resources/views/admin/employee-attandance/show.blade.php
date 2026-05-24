<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">
                Employee Attendance Details - {{ date('F', mktime(0, 0, 0, $month, 1)) }}
            </h3>


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none text-primary">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('employee-attandance.index') }}" class="text-decoration-none">
                            Attendance List
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-secondary">Details</li>
                </ol>
            </nav>
        </div>

        <div class="row g-4">

            {{-- ✅ Employee Information --}}
            <div class="col-lg-6">
                <div class="card border p-3 rounded-3 bg-white">
                    <h5 class="fw-semibold mb-3">Employee Information</h5>
                    <p class="mb-2"><strong>Name:</strong> {{ $employee->name ?? '-' }}</p>
                    <p class="mb-2"><strong>Employe ID:</strong> {{ $employee->employee_id ?? '-' }}</p>

                </div>
            </div>

            {{-- ✅ Attendance Summary --}}
            <div class="col-lg-6">
                <div class="card border p-3 rounded-3 bg-white">
                    <h5 class="fw-semibold mb-3">Attendance Summary</h5>

                    <div class="d-flex flex-wrap gap-3">
                        <div class="p-3 bg-light text-center flex-fill rounded">
                            <p class="mb-1 text-success fw-bold">Present</p>
                            <h5 class="mb-0">{{ $summary->present ?? 0 }}</h5>
                        </div>
                        <div class="p-3 bg-light text-center flex-fill rounded">
                            <p class="mb-1 text-danger fw-bold">leave</p>
                            <h5 class="mb-0">{{ $summary->leave ?? 0 }}</h5>
                        </div>
                        <div class="p-3 bg-light text-center flex-fill rounded">
                            <p class="mb-1 text-primary fw-bold">Weekly Off</p>
                            <h5 class="mb-0">{{ $summary->weekly_off ?? 0 }}</h5>
                        </div>
                        <div class="p-3 bg-light text-center flex-fill rounded">
                            <p class="mb-1 text-warning fw-bold">Half Day</p>
                            <h5 class="mb-0">{{ $summary->half_day ?? 0 }}</h5>
                        </div>
                        <div class="p-3 bg-light text-center flex-fill rounded">
                            <p class="mb-1 text-info fw-bold">Holiday</p>
                            <h5 class="mb-0">{{ $summary->holiday ?? 0 }}</h5>
                        </div>
                        <div class="p-3 bg-light text-center flex-fill rounded">
                            <p class="mb-1 text-dark fw-bold">Total</p>
                            <h5 class="mb-0">{{ $summary->total ?? 0 }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ✅ Daily Attendance Records --}}
            <div class="col-lg-12">
                <div class="card border p-3 rounded-3 bg-white table-card">
                    <h5 class="fw-semibold mb-3">Daily Attendance Records</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0 bg-white" id="attendanceDetailTable">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @forelse($records as $record)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</td>
                                        <td>
                                            @if ($record->status == 'Present')
                                                <span class="badge bg-success">Present</span>
                                            @elseif ($record->status == 'Leave')
                                                <span class="badge bg-danger">Leave</span>
                                            @elseif ($record->status == 'Weekly Off')
                                                <span class="badge bg-primary">Weekly Off</span>
                                            @elseif ($record->status == 'Half Day')
                                                <span class="badge bg-warning text-dark">Half Day</span>
                                            @elseif ($record->status == 'Holiday')
                                                <span class="badge bg-info">Holiday</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $record->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (in_array($record->status, ['Weekly Off', 'Holiday', 'Leave']))
                                                -
                                            @else
                                                @php
                                                    $in = $record->check_in
                                                        ? date('h:i A', strtotime($record->check_in))
                                                        : '09:00 AM';
                                                @endphp
                                                {{ $in }}
                                            @endif
                                        </td>

                                        <td>
                                            @if (in_array($record->status, ['Weekly Off', 'Holiday', 'Leave']))
                                                -
                                            @else
                                                @php
                                                    $out = $record->check_out
                                                        ? date('h:i A', strtotime($record->check_out))
                                                        : '05:00 PM';
                                                @endphp
                                                {{ $out }}
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            No attendance records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .table-card {
            background-color: #fff !important;
        }

        .table,
        .table th,
        .table td {
            background-color: #fff !important;
        }

        .card {
            background: #fff !important;
        }

        .badge {
            font-size: 0.85rem;
        }

        body {
            background-color: #f8f9fa;
        }
    </style>
@endsection


@push('scripts')
    {{-- ✅ DataTables --}}
    <script>
        $(document).ready(function() {
            $('#attendanceDetailTable').DataTable({
                paging: true,
                searching: true,
                info: true,
                lengthMenu: [10, 20, 50, 100],
                language: {
                    paginate: {
                        previous: "<i class='bi bi-chevron-left'>Previous</i>",
                        next: "<i class='bi bi-chevron-right'>Next</i>"
                    },
                    search: "_INPUT_",
                    searchPlaceholder: "Search records...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ records"
                },
                dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l>" +
                    "<'col-sm-6 d-flex justify-content-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i>" +
                    "<'col-sm-6 d-flex justify-content-end'p>>",
            });
        });
    </script>
@endpush
