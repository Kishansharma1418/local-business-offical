<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Employee Generate Salary List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Employee Generate Salary List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                <div>
                    <button id="generateSalaryBtn" class="btn btn-primary text-white">
                        <i class="bi bi-cash-stack"></i> Generate Salary
                    </button>
                </div>
                <div class="d-flex gap-3 align-items-center" style="flex:1;">
                    <select id="monthSelect" class="form-select bg-white form-control" style="width: 180px; height: 50px;">
                       @for ($m = 1; $m <= 12; $m++)
                        @php
                            $current = now()->subMonth(); // 1 month back
                            $monthName = date('F', mktime(0, 0, 0, $m, 1));
                        @endphp

                        <option value="{{ $m }}" {{ $m == $current->month ? 'selected' : '' }}>
                            {{ $monthName }} {{ $current->year }}
                        </option>
                    @endfor

                    </select>
                    <!-- <div class="d-flex align-items-center gap-2">
                        <select name="employee_id" id="employee_id" class="form-control form-select-sm w-auto "
                            style="height: 50px;">
                            <option value="">All Employees</option>
                            @foreach ($employee as $e)
                                <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                            @endforeach
                        </select>
                    </div> -->
                </div>

                <div>
                    <button class="btn btn-primary text-white" id="exportSalaryBtn">
                        <i class="bi bi-filter-circle"></i> Salary Export

                    </button>
                </div>

            </div>

            <div class="default-table-area mx-minus-1">
                <div class="table-responsive overflow-none">
                    <table class="table" id="GenerateSalaryTable">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Present</th>
                                <th>Leave</th>
                                <th>Weekly Off</th>
                                <th>Half Day</th>
                                <th>Holiday</th>
                                <th>Total Salary</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            let salaryTable;

            function loadSalaryTable(month) {
                if ($.fn.DataTable.isDataTable('#GenerateSalaryTable')) {
                    salaryTable.destroy();
                }

                salaryTable = $('#GenerateSalaryTable').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    lengthMenu: [10, 20, 50, 100],
                    ajax: {
                        url: "{{ route('salary-generate.index') }}",
                        data: function(d) {
                            d.month = $('#monthSelect').val();
                            d.employee_id = $('#employee_id').val();
                        }
                    },
                    columns: [{
                            data: 'user',
                            name: 'user'
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
                            data: 'net_salary',
                            name: 'net_salary'
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
            }
            loadSalaryTable($('#monthSelect').val());

            $('#monthSelect').on('change', function() {
                let selectedMonth = parseInt($(this).val());
                let currentMonth = (new Date()).getMonth() + 1;


                if (selectedMonth >= currentMonth) {
                    $('#generateSalaryBtn').prop('disabled', true)
                        .removeClass('btn-success')
                        .addClass('btn-secondary')
                        .text('Not Allowed');
                } else {

                    $('#generateSalaryBtn').prop('disabled', false)
                        .removeClass('btn-secondary')
                        .addClass('btn-primary')
                        .text('Generate Salary');
                }

                loadSalaryTable(selectedMonth);
            });


            $('#generateSalaryBtn').click(function() {
                let month = $('#monthSelect').val();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to generate salary for this month?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Generate!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('salary-generate.store') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                month: month
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Processing...',
                                    html: 'Please wait while salary is being generated',
                                    allowOutsideClick: false,
                                    didOpen: () => Swal.showLoading()
                                });
                            },
                            success: function(res) {
                                Swal.close();
                                Swal.fire('Success', res.message, 'success');
                                loadSalaryTable(month);
                            },
                            error: function(err) {
                                Swal.close();
                                const msg = err.responseJSON?.message ||
                                    'Something went wrong!';
                                Swal.fire('Error', msg, 'error');
                            }
                        });
                    }
                });
            });
        });

        $('#exportSalaryBtn').click(function() {
            let month = $('#monthSelect').val();

            window.location.href = "{{ route('salary.export') }}?month=" + month;
        });
    </script>
@endpush
