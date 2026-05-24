<style>
    input.form-control.form-control-sm {
        height: 43px;
    }

    #downloadSampleBtn {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        height: 50px;
    }

    #downloadSampleBtn:hover {
        background-color: #4886e4 !important;
    }
</style>
@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Employee Attendance List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Employee Attendance List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="row p-20 g-3">

                <div class="col-md-3 col-lg-2">
                    <select name="employee_id" id="employee_id" class="form-control" style="height: 50px;">
                        <option value="">All Employees</option>
                        @foreach ($employee as $e)
                            <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 col-lg-2">
                    <select id="monthSelect" class=" form-control" style="height: 50px;">
                        <option value="">All</option>
                        @for ($m = 1; $m <= 12; $m++)
                            @php
                                $monthName = date('F', mktime(0, 0, 0, $m, 1));
                            @endphp
                            <option value="{{ $m }}" {{ $m == now()->subMonth()->month ? 'selected' : '' }}>
                                {{ $monthName }} {{ now()->year }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-6 col-lg-8">
                    <form action="{{ route('employee.attendance.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="file" name="file" class="form-control form-control-sm"
                                    accept=".xlsx, .xls" style="height:50px;">
                            </div>

                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary fw-normal text-white w-100 p-3">
                                    Import
                                </button>
                            </div>

                            <div class="col-md-3">
                                <a href="javascript:void(0)" id="downloadSampleBtn"
                                    class="btn btn-primary  text-white w-100 p-3 border-0" >
                                    Download Sample
                                </a>

                            </div>

                        </div>
                    </form>
                </div>

            </div>

            <div class="default-table-area mx-minus-1 ">
                <div class="table-responsive overflow-none">
                    <table class="table" id="attandanceTable">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Present</th>
                                <th>Leave</th>
                                <th>Weekly Off</th>
                                <th>Half Day</th>
                                <th>Holiday</th>
                                <th>Month</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            let table = $('#attandanceTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: true,
                lengthMenu: [10, 20, 50, 100],
                language: {
                    processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border"></div></div></div>'
                },
                ajax: {
                    url: "{{ route('employee-attandance.index') }}",
                    data: function(d) {
                        d.month = $('#monthSelect').val();
                        d.employee_id = $('#employee_id').val();
                    }
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'present',
                        name: 'present'
                    },
                    {
                        data: 'leave',
                        name: 'leave'
                    },
                    {
                        data: 'weekly_off',
                        name: 'weekly_off'
                    },
                    {
                        data: 'half_day',
                        name: 'half_day'
                    },
                    {
                        data: 'holiday',
                        name: 'holiday'
                    },
                    {
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
            });
            $('#employee_id, #searchMonth').on('change', function() {
                table.ajax.reload();
            });
            $('#monthSelect').on('change', function() {
                table.ajax.reload();
            });


            $('#downloadSampleBtn').on('click', function(e) {
                e.preventDefault();
                let selectedMonth = $('#monthSelect').val();
                let selectedYear = new Date().getFullYear();

                let downloadUrl = "{{ route('attendance.exportSample') }}" + "?month=" + selectedMonth +
                    "&year=" + selectedYear;

                window.location.href = downloadUrl;
            });

            function updateDownloadButtonState() {
                let sel = $('#monthSelect').val();
                let selectedMonth = sel === "" ? null : parseInt(sel);
                let today = new Date();
                let currentMonth = today.getMonth() + 1;

                h
                let allowed = (selectedMonth !== null && selectedMonth < currentMonth);

                if (allowed) {
                    $('#downloadSampleBtn').prop('disabled', false).removeClass('btn-secondary').addClass(
                        'btn-warning');
                } else {
                    $('#downloadSampleBtn').prop('disabled', true).removeClass('btn-warning').addClass(
                        'btn-secondary');
                }
            }
            updateDownloadButtonState();
            $('#monthSelect').on('change', function() {
                updateDownloadButtonState();
                if ($.fn.DataTable.isDataTable('#attandanceTable')) {
                    $('#attandanceTable').DataTable().ajax.reload();
                }
            });

            $('#downloadSampleBtn').on('click', function(e) {
                e.preventDefault();
                let sel = $('#monthSelect').val();
                if (!sel) {
                    alert('Please select a previous month (not current or future) to download sample.');
                    return;
                }
                let selectedMonth = parseInt(sel);
                let today = new Date();
                let currentMonth = today.getMonth() + 1;
                if (selectedMonth >= currentMonth) {
                    alert('Download not allowed for current or future month.');
                    return;
                }
                let selectedYear = new Date().getFullYear();
                let downloadUrl = "{{ route('attendance.exportSample') }}" + "?month=" + selectedMonth +
                    "&year=" + selectedYear;
                window.location.href = downloadUrl;
            });

        });
    </script>
@endpush
