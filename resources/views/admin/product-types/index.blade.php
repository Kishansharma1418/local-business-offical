<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Product Type List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="text-secondary">Product Type List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Card -->
        <div class="card bg-white rounded-10 border border-white mb-4">

            <!-- Add Button -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                <button class="btn btn-primary fw-normal text-white fs-16 border-0 p-3" data-bs-toggle="modal"
                    data-bs-target="#addProductTypeModal">
                    + Add Product Type
                </button>
            </div>

            <!-- Table -->
            <div class="default-table-area mx-minus-1">
                <div class="table-responsive overflow-none">
                    <table class="table" id="productTypeTable">
                        <thead>
                            <tr>
                                <th>Name</th>

                                <th>Description</th>
                                <th>Status</th>
                                <th style="text-align:center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- ADD MODAL -->
    <div class="modal fade" id="addProductTypeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:550px;">
            <form class="modal-content bg-white" id="addProductTypeForm">
                @csrf
                <input type="hidden" id="class_route" value="{{ route('product-types.store') }}">
                <div class="modal-header p-20">
                    <h5 class="modal-title">Add Product Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-20 pb-0">
                    <div class="mb-20">
                        <label class="label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name">
                    </div>

                    <div class="mb-20">
                        <label class="label">Description</label>
                        <input type="text" class="form-control" name="description">
                    </div>

                    <div class="mb-20">
                        <label class="label">Status</label>
                        <select class="form-select form-control" name="status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer p-20 pt-0 border-0">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary text-white">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="edit_model" tabindex="-1"></div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            var table = $('#productTypeTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('product-types.index') }}",
                    data: function(d) {}
                },
                columns: [{
                        data: 'name',
                        name: 'name'
                    },

                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'status',
                        name: 'status'
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

            // DELETE
            $(document).on('click', '.deleteProductTypeBtn', function() {
                if (confirm('Are you sure want to delete this Product Type?')) {
                    let id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('product-types') }}/" + id,
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

    <script>
        handleModalFormSubmit('#addProductTypeForm', '#addProductTypeModal', '#productTypeTable',
            'Product Type created successfully');
        handleModalUpdateSubmit('#editProductTypeForm', '#edit_model', '#productTypeTable',
            'Product Type updated successfully');
    </script>
@endpush
