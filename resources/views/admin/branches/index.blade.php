<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Branch List</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Branch List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="default-table-area mx-minus-1">

                <div class="d-flex align-items-center flex-wrap gap-3 p-20">
                    {{-- @can('add-branch') --}}
                    <a href="{{ route('branches.create') }}"
                        class="btn btn-primary d-flex align-items-center justify-content-center px-3 py-3 rounded-2 shadow-sm"
                        style="color: #fff; font-size: 14px;">
                        + Add Branch
                    </a>
                    {{-- @endcan --}}
                    <div class="d-flex align-items-center gap-2">
                        <select name="branch_name" id="nameFilter" class="form-control form-select-sm"
                            style="width: 180px; height: 50px;">
                            <option value="">All Branches</option>
                            @foreach ($branch as $t)
                                <option value="{{ $t->branch_name }}">{{ $t->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="table-responsive overflow-none mt-3">
                    <table class="table" id="branchTable">
                        <thead>
                            <tr>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th>Branch Type</th>
                                <th>Employee Count</th>

                                <th>Address</th>
                                <th>Status</th>
                                <th>Action</th>
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
        $(function() {
            var table = $('#branchTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('branches.index') }}",
                    data: function(d) {
                        d.branch_name = $('#nameFilter').val();
                    }
                },
                columns: [{
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },

                    {
                        data: 'branch_type',
                        name: 'branch_type'
                    },
                    {
                        data: 'employee_count',
                        name: 'employee_count'
                    },

                    {
                        data: 'address_line1',
                        name: 'address_line1'
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
                        searchable: false
                    },
                ],
                dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
            });
            $('#nameFilter').change(function() {
                table.draw();
            });
            $(document).on('click', '.deleteBranchBtn', function() {
                if (confirm('Are you sure want to delete this branch?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('branches') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            table.ajax.reload();
                            toastr.success(res.message);
                        }
                    });
                }
            });


        });
    </script>
@endpush
