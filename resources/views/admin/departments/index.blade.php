<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Department List</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Department List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Card -->
        <div class="card bg-white rounded-10 border border-white mb-4">

            <!-- Add New Button -->
            <div class="d-flex align-items-center flex-wrap gap-3 p-20">
                {{-- @can('add-department') --}}
                <a href="{{ route('departments.create') }}"
                    class="btn btn-primary d-flex align-items-center justify-content-center px-3 py-3 rounded-2 shadow-sm"
                    style="color: #fff; font-size: 14px;">
                    + Add Department
                </a>
                {{-- @endcan --}}
                  <div class="d-flex align-items-center gap-2">
                    <select name="department_name" id="nameFilter" class="form-control form-select-sm"
                        style="width: 180px; height: 50px;">
                        <option value="">All Departments</option>
                        @foreach ($departments as $t)
                            <option value="{{ $t->department_name }}">{{ $t->department_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="default-table-area mx-minus-1">
                <div class="table-responsive overflow-none">
                    <table class="table" id="departmentTable">
                        <thead>
                            <tr>

                                <th>Code</th>
                                <th>Department Name</th>
                                <th>Employee Count</th>
                                <th>Branch</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
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
    <script>
        $(document).ready(function() {
           var table= $('#departmentTable').DataTable({
                processing: true,
                serverSide: true,
               ajax: {
                    url: "{{ route('departments.index') }}",
                    type: "GET",
                    data: function (d) {
                        d.department_name = $('#nameFilter').val();
                    }
                },

                columns: [

                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'department_name',
                        name: 'department_name'
                    },
                    {
                        data:'employee_count',
                        name:'employee_count'
                    },
                   
                    {
                        data: 'branch',
                        name: 'branch',
                        defaultContent: '-'
                    },
                 
                  
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +

                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",

            });

             $('#nameFilter').change(function() {
                    table.draw();
                });
             $(document).on('click', '.deletedepartmentBtn', function() {
                if (confirm('Are you sure want to delete this department?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('departments') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                             console.log(res); 
                            table.ajax.reload();
                            toastr.success(res.message);
                        }
                    });
                }
            });
        });
    </script>
@endpush
