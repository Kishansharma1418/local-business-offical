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
            <h3 class="mb-0">Raw Category List</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Raw Category List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Card -->
        <div class="card bg-white rounded-10 border border-white mb-4">

            <!-- Add New Button -->
            {{-- @can('add-product-category') --}}
            <div class="d-flex  align-items-center flex-wrap gap-3 p-20">
                <!-- <a href="{{ route('rawcategory.create') }}"
                    class="btn btn-primary d-flex align-items-center justify-content-center px-3 py-3 rounded-2 shadow-sm"
                    style="color: #fff; font-size: 14px;">
                    + Add Raw Category
                </a> -->
                {{-- @endcan --}}
                {{-- <div class="d-flex align-items-center gap-2">
                    <select name="category_name" id="nameFilter" class="form-control form-select-sm"
                        style="width: 180px; height: 50px;">
                        <option value="">All Categories</option>
                        @foreach ($categories as $t)
                            <option value="{{ $t->category_name }}">{{ $t->category_name }}</option>
                        @endforeach
                    </select>
                </div> --}}
            </div>

            <!-- Table -->
            <div class="default-table-area mx-minus-1">
                <div class="table-responsive overflow-none">
                    <table class="table" id="categoryTable">
                        <thead>
                            <tr>

                                <th>Code</th>
                                <th>Parent Category</th>
                                <th>Name</th>
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
          var table= $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                 ajax: {
                        url: "{{ route('rawcategory.index') }}",
                        data: function(d) {
                            d.category_name = $('#nameFilter').val();
                        }
                    },
                columns: [

                    {
                        data: 'code',
                        name: 'code'
                    },
                     {
                        data: 'parent_category',
                        name: 'parent_category'
                    },
                    {
                        data: 'name',
                        name: 'name'
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
              $(document).on('click', '.deleteBranchBtn', function() {
                if (confirm('Are you sure want to delete this category?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('category') }}/" + id,
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
