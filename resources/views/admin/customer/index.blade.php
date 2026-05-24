<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Customer List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Customer List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="d-flex  align-items-center flex-wrap gap-3 p-20">

                {{-- @can('add-customer-detail') --}}
                <a href="{{ route('customers.create') }}"
                    class="text-decorntion-none btn btn-primary fw-normal text-white fs-16 border-0 p-3 fs-16 border-0">+
                    Add Customer </a>
                {{-- @endcan --}}
                <div class="d-flex align-items-center gap-2">
                    <select name="name" id="nameFilter" class="form-control form-select-sm"
                        style="width: 180px; height: 50px;">
                        <option value="">All Customers</option>
                        @foreach ($query as $t)
                            <option value="{{ $t->name }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                    <select id="filter_branch" class="form-control" style="height: 50px;">
                        <option value="">All Branches</option>
                        @foreach ($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->branch_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="default-table-area mx-minus-1 ">
                <div class="table-responsive overflow-none">
                    <table class="table" id="customerTable">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium">User</th>
                                <th scope="col" class="fw-medium">Customer type</th>
                                <th scope="col" class="fw-medium">Status</th>
                                <th scope="col" class="fw-medium">Created At</th>
                                <th scope="col" class="fw-medium" style="text-align: center">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <div class="modal fade" id="noOutstandingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fs-18 fw-semibold">No Outstanding Invoices</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success fs-50"></i>
                    </div>
                    <p class="mb-0 fs-16 text-secondary">
                        This customer currently has <strong>no pending invoices</strong>.
                    </p>
                </div>
                <div class="modal-footer justify-content-center border-top-0 py-3">
                    <button type="button" class="btn btn-primary text-white px-4 fw-medium" data-bs-dismiss="modal">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            if (!$.fn.DataTable.isDataTable('#customerTable')) {
                var dataTable = $('#customerTable').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    scrollX: false,
                    // responsive: true,
                    lengthMenu: [10, 20, 50, 100],
                    language: {
                        processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border"></div></div></div>'
                    },
                    ajax: {
                        url: "{{ route('customers.index') }}",
                        data: function(d) {
                            d.name = $('#nameFilter').val();
                            d.branch_id = $('#filter_branch').val();
                        }
                    },
                    columns: [

                        {
                            data: 'user',
                            name: 'user'
                        },
                        {
                            data: 'customer_type',
                            name: 'customer_type'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',

                        },
                        {
                            data: 'action',
                            name: 'action',

                        }
                    ],
                    dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +

                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
                });
            }
            $('#nameFilter').change(function() {
                dataTable.draw();
            });
            $('#filter_branch')
                .on('change', function() {
                    dataTable.ajax.reload();
                });
            $(document).on('click', '.deleteBranchBtn', function() {
                if (confirm('Are you sure want to delete this customer?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('customers') }}/" + id,
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
    <script>
    $(document).on('click', '.customerOutstandingBtn', function () {
            const dueCount = parseInt($(this).data('due'));
            const customerId = $(this).data('customer-id');

            if (dueCount === 0) {
                const modalEl = document.getElementById('noOutstandingModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            } else {
                const url = "{{ route('customer.ledger.pdf', ':id') }}".replace(':id', customerId);
                window.location.href = url;
            }
        });
    </script>
@endpush
