<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Employee Holiday List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Employee Holiday List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="d-flex  align-items-center flex-wrap gap-3 p-20">
                {{-- @can('add-employee-holiday') --}}
                <a href="{{ route('employee-holiday.create') }}"
                    class="text-decorntion-none btn btn-primary fw-normal text-white fs-16 border-0 p-3 fs-16 border-0">+
                    Add Employee Holiday </a>
                {{-- @endcan --}}
                <div class="d-flex align-items-center gap-2 ">
                    <select name="branch_id" id="branch_id" class="form-control form-select-sm " style="height: 50px;"
                        style="width: 180px; height: 55px;">
                        <option value="">All Branches</option>
                        @foreach ($branches as $e)
                            <option value="{{ $e->id }}">{{ $e->branch_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Filter by Year</label>
                    <select id="filter_year" class="form-control" style="height: 50px;">
                        <option value="">-- Select Year --</option>
                        @for ($y = now()->year+1; $y >= 2000; $y--)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>


            </div>
            <div class="default-table-area mx-minus-1 ">
                <div class="table-responsive overflow-none">
                    <table class="table" id="holidayTable">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium">Title</th>

                                <th scope="col" class="fw-medium">Branch</th>
                                <th scope="col" class="fw-medium">Status</th>
                                <th scope="col" class="fw-medium">Holiday</th>
                                <th scope="col" class="fw-medium">Created At</th>
                                <th scope="col" class="fw-medium text-center">Action</th>
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

            var table = $('#holidayTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: true,
                lengthMenu: [10, 20, 50, 100],

                ajax: {
                    url: "{{ route('employee-holiday.index') }}",
                    data: function(d) {
                        d.branch_id = $('#branch_id').val();
                        d.year = $('#filter_year').val();
                    }
                },

                columns: [{
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'branch',
                        name: 'branch'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],

                dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +

                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
            });

            // ✅ Correct filter reload listener
            $('#branch_id').on('change', function() {
                table.ajax.reload();
            });
            $('#filter_year').on('change', function() {
                table.ajax.reload();
            });


            $(document).on('click', '.deleteBranchBtn', function() {

                let id = $(this).data('id');

                if (confirm("Are you sure want to delete this Holiday?")) {

                    $.ajax({
                        url: "{{ url('employee-holiday') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },

                        success: function(res) {
                            if (res.status) {
                                toastr.success(res.message);
                            } else {
                                toastr.error(res.message);
                                table.ajax.reload(null, false);
                            }
                            dataTable.ajax.reload();
                        },

                        error: function(err) {
                            toastr.error("Something went wrong!");
                        }
                    });
                }
            });
            $('#filter_start_date').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });


        });
    </script>
@endpush
