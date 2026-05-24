<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Payment Terms</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Payment Terms List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="d-flex align-items-center flex-wrap gap-3 p-20">
                <button class="btn btn-primary fw-normal text-white fs-16 border-0 p-3" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">
                    + Add Payment Terms
                </button>

                <div class="d-flex align-items-center gap-2">
                    <select name="name" id="nameFilter" class="form-control form-select-sm"
                        style="width: 180px; height: 50px;">
                        <option value="">All Payment Terms</option>
                        @foreach ($terms as $t)
                            <option value="{{ $t->name }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="default-table-area mx-minus-1">
                <div class="table-responsive overflow-none">
                    <table class="table" id="paymentTermsTable">
                        <thead>
                            <tr>
                                <th class="fw-medium">Name</th>
                                <th class="fw-medium">Days</th>
                                <th class="fw-medium">Status</th>
                                <th class="fw-medium text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
            <form class="modal-content bg-white" id="addTermForm" method="POST">
                @csrf
                <input type="hidden" id="class_route" value="{{ route('payment-terms.store') }}">

                <div class="modal-header border-border-color-40 p-20">
                    <h1 class="modal-title fs-18 fw-medium mb-0" id="exampleModalLabel">Add Payment Terms</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>



                <div class="modal-body p-20 pb-0">
                    <div class="row">

                        <div class="col-lg-12 mb-20">
                            <label class="label">Name <span class="text-danger">*</span></label>
                            <div class="form-floating">
                                <input type="text" class="form-control" name="name"
                                    placeholder="Payment Term Name"data-rule-required="true">
                                <label>Payment Term Name</label>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-20">
                            <label class="label">Days <span class="text-danger">*</span></label>
                            <div class="form-floating">
                                <input type="number" class="form-control days" name="days" placeholder="Enter Days"
                                    min="0" data-rule-required="true">
                                <label>Days</label>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Status</label>
                            <select class="form-select form-control" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer border-0 p-20 pt-0">
                    <button type="button" class="btn btn-danger fw-normal text-white"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-normal text-white">Create</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="edit_model" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    @endsection

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {

                var dataTable = $('#paymentTermsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    scrollX: false,
                    lengthMenu: [10, 20, 50, 100],
                    language: {
                        processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border"></div></div></div>'
                    },
                    ajax: {
                        url: "{{ route('payment-terms.index') }}",
                        data: function(d) {
                            d.name = $('#nameFilter').val();
                        }
                    },
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'days',
                            name: 'days'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6 d-flex justify-content-end'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
                });
                $('#nameFilter').change(function() {
                    dataTable.draw();
                });


                $(document).on('click', '.deleteTermBtn', function() {
                    if (confirm('Are you sure want to delete this Payment Term?')) {
                        var id = $(this).data('id');
                        $.ajax({
                            url: "{{ url('payment-terms') }}/" + id,
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

                $(document).on('keydown', '.days',
                    function(e) {
                        if (e.key === '-' || e.key === 'e' || e.key === '+') {
                            e.preventDefault();
                        }
                    });

            });
        </script>

        <script>
            handleModalFormSubmit('#addTermForm', '#exampleModal', '#paymentTermsTable', 'Payment Term created successfully');
            handleModalUpdateSubmit('#editTermForm', '#edit_model', '#paymentTermsTable', 'Payment Term updated successfully');
        </script>
    @endpush
