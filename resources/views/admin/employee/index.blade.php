<style>
    input.form-control.form-control-sm {
        height: 43px;
         /* display:none; */
    }
</style>
@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Employee List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Employee List</span>
                    </li>   
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="row p-20 g-3">
                <div class="col-md-3 col-lg-2">
                    <a href="{{ route('employee.create') }}" class="btn btn-primary fw-normal text-white w-100 p-3 fs-16">
                        + Add Employee
                    </a>
                </div>
                <div class="col-md-3 col-lg-2">
                    <a href="{{ route('employees.export') }}" class="btn btn-primary text-white w-100 p-3 fs-16">
                        Export Employees
                    </a>
                </div>


                {{-- Role Filter --}}
                <div class="col-md-3 col-lg-2">
                    <select name="role_type" id="roleFilter" class="form-control form-select-sm" style="height: 55px;">
                        <option value="">Employee type</option>
                        <option value="sales">Sales</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                {{-- Import + Download --}}
                <div class="col-md-6 col-lg-6">
                    <form action="{{ route('employee.import') }}" method="POST" enctype="multipart/form-data"
                        id="importForm">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-6">
                                <input type="file" name="file" id="employeeFile" accept=".xlsx,.xls"
                                    class="form-control form-control-sm" style="height:50px;">
                            </div>

                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary text-white w-100 p-3">
                                    Import
                                </button>
                            </div>

                            <div class="col-md-3">
                                <a href="javascript:void(0)" id="downloadExcel" class="btn btn-primary text-white w-100 p-2"
                                    style="height: 50px">
                                    Sample Download
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="d-flex gap-3">

                    <input type="text" name="value" id="allGlobal" class="form-control"
                        placeholder="Search Employee Details" style="width: 200px; height: 55px;">
                    <!-- Branch Filter -->
                    <select id="filter_branch" class="form-control" style="height: 55px;">
                        <option value="">All Branches</option>
                        @foreach ($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->branch_name }}</option>
                        @endforeach
                    </select>

                    <!-- Department Filter -->
                    <select id="filter_department" class="form-control" style="height: 55px;">
                        <option value="">All Departments</option>
                        @foreach ($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->department_name }}</option>
                        @endforeach
                    </select>

                    <!-- Designation Filter -->
                    <select id="filter_designation" class="form-control" style="height: 55px;">
                        <option value="">All Designation</option>
                        @foreach ($designations as $dg)
                            <option value="{{ $dg->id }}">{{ $dg->name }}</option>
                        @endforeach
                    </select>

                    <!-- Reporting Manager Filter -->
                    <select id="filter_reporting" class="form-control" style="height: 55px;">
                        <option value="">All Reporting Managers</option>
                        @foreach ($reporting_managers as $rm)
                            <option value="{{ $rm->id }}">{{ $rm->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="default-table-area mx-minus-1 ">
                <div class="table-responsive overflow-none">
                    <table class="table" id="employeTable">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium">User</th>
                                <th scope="col" classs="fw-medium">Employee Details</th>
                                <th scope="col" class="fw-medium">Employment type</th>
                                <th scope="col" class="fw-medium">Status</th>
                                <th scope="col" class="fw-medium">Login</th>
                                <th scope="col" class="fw-medium">Joining date</th>
                                {{-- <th scope="col" class="fw-medium">Created by</th> --}}
                                <th scope="col" class="fw-medium" style="text-align : center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            if (!$.fn.DataTable.isDataTable('#employeTable')) {
                var dataTable = $('#employeTable').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    scrollX: false,
                    responsive: true,
                    lengthMenu: [10, 20, 50, 100],
                    language: {
                        processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border"></div></div></div>'
                    },
                    ajax: {
                        url: "{{ route('employee.index') }}",
                        data: function(d) {
                            d.value = $('#allGlobal').val();
                            d.role = $('#roleFilter').val();
                            d.branch_id = $('#filter_branch').val();
                            d.department_id = $('#filter_department').val();
                            d.designation_id = $('#filter_designation').val();
                            d.reporting_id = $('#filter_reporting').val();
                        }
                    },
                    columns: [{
                            data: 'user',
                            name: 'user'
                        },
                        {
                            data: 'details',
                            name: 'details'
                        },
                        {
                            data: 'employee_type',
                            name: 'employee_type'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'is_login',
                            name: 'is_login'
                        },
                        {
                            data: 'joining_date',
                            name: 'joining_date'
                        },
                        // { data: 'created_by', name: 'created_by' },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],

                    dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +

                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
                });
            }
            $('#nameFilter').change(function() {
                dataTable.draw();
            });
            $('#roleFilter').change(function() {
                dataTable.draw();
            });
            $('#allGlobal').on('keyup', function() {
                dataTable.ajax.reload();
            });


            $('#filter_branch, #filter_department, #filter_designation, #filter_reporting')
                .on('change', function() {
                    dataTable.ajax.reload();
                });
            $('#downloadExcel').click(function() {
                let name = $('#nameFilter').val();
                let role = $('#roleFilter').val();

                window.location.href = "{{ route('employee.export') }}" + "?full_name=" + name + "&role=" +
                    role;
            });

            $(document).on('click', '.deleteBranchBtn', function() {
                if (confirm('Are you sure want to delete this employee?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('employee') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            dataTable.ajax.reload();
                            toastr.success(res.message);
                        }
                    });
                }
            });

        });
    </script>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {

            @if (session('import_first_error'))
                toastr.error(
                    "Row {{ session('import_first_error.row') }} : {{ session('import_first_error.error') }}"
                );
            @endif

            @if (session('import_error_single'))
                toastr.error(@json(session('import_error_single')));
            @endif

        });
    </script>
@endpush
