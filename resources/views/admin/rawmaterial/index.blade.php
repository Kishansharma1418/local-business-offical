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
            <h3 class="mb-0">Raw Material List</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Raw Material</li>
                </ol>
            </nav>
        </div>

        <!-- Card -->
        <div class="card bg-white rounded-10 border border-white mb-4">

            <!-- Toolbar -->
            <div class="d-flex align-items-center flex-wrap gap-3 p-20">

                <a href="{{ route('rawmaterial.create') }}" class="btn btn-primary px-3 py-3 shadow-sm"
                    style="height:50px;color:#fff;">
                    + Add Raw Material
                </a>
            </div>

            <!-- Table -->
            <div class="default-table-area mx-minus-1">
                <div class="table-responsive">
                    <table class="table" id="rawMaterialTable">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Sub Category</th>

                                <th>Total stock </th>
                                <th>Status</th>
                                <th>Created At</th>
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

            let table = $('#rawMaterialTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('rawmaterial.index') }}",
                    data: function(d) {
                        d.raw_category_id = $('#raw_category_id').val();
                        d.sub_rawcategory_id = $('#sub_rawcategory_id').val();
                    }
                },
                columns: [{
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'category',
                        name: 'category',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'subcategory',
                        name: 'subcategory',
                        defaultContent: 'N/A'
                    },
                    {
                        data: null,
                        name: 'stock_all',
                        defaultContent: 'N/A',
                        render: function(data, type, row) {
                            return (row.stock_all ?? 0) + ' ' + (row.uom?.name ?? '');
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
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

            /* ===============================
                Parent → Sub Category
            ===============================*/
            $('#raw_category_id').change(function() {

                let categoryId = $(this).val();
                $('#sub_rawcategory_id').html('<option value="">All Sub Categories</option>');

                if (categoryId) {
                    $.get("{{ url('get-subcategories') }}/" + categoryId, function(res) {
                        res.forEach(item => {
                            $('#sub_rawcategory_id').append(
                                `<option value="${item.id}">${item.name}</option>`
                            );
                        });
                    });
                }

                table.ajax.reload();
            });

            $('#sub_rawcategory_id').change(function() {
                table.ajax.reload();
            });

            /* ===============================
                Delete
            ===============================*/
            $(document).on('click', '.deleteBranchBtn', function() {
                if (!confirm('Are you sure want to delete this raw material?')) return;

                let id = $(this).data('id');

                $.ajax({
                    url: "{{ url('rawmaterial') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        table.ajax.reload();
                        toastr.success(res.message);
                    }
                });
            });

        });
    </script>
@endpush
