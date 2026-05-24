   
<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Invoice Order List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Invoice Order List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="d-flex  align-items-center flex-wrap gap-3 p-20">
    
                    <a href="{{ route('invoice-orders.create') }}"
                        class="text-decorntion-none btn btn-primary fw-normal text-white fs-16 border-0 p-3 fs-16 border-0">+
                        Add Invoice Order </a>
                 <!-- <div class="d-flex align-items-center gap-2">
                    <select name="name" id="nameFilter" class="form-control form-select-sm"
                        style="width: 180px; height: 50px;">
                        <option value="">All Customers</option>
                        @foreach ($query as $t)
                            <option value="{{ $t->name }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div> -->
            </div>
            <div class="default-table-area mx-minus-1 ">
                <div class="table-responsive overflow-none">
                    <table class="table" id="salesOrderTable">
                        <thead>
                            <tr>

                                <th scope="col" class="fw-medium">Date</th>
                                <th scope="col" class="fw-medium">Invoice</th>
                                <th scope="col" class="fw-medium">Order Number</th>
                                <th scope="col" class="fw-medium">Customer</th>
                                <!-- <th scope="col" class="fw-medium">Quantity</th> -->
                                <th scope="col" class="fw-medium">Status</th>
                                <th scope="col" class="fw-medium">Due Date</th>
                                <th scope="col" class="fw-medium">Net Amount</th>
                                <th scope="col" class="fw-medium">Balance Due</th>
                                <!-- <th scope="col" class="fw-medium">Created At</th> -->
                                <th scope="col" class="fw-medium" style="text-align: center">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="invoiceEditReasonModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width:550px;">
            <form id="invoiceEditReasonForm" class="modal-content bg-white">
                @csrf

                <input type="hidden" id="invoiceId">
                <input type="hidden" id="invoiceEditUrl">

                <div class="modal-header border-border-color-40 p-20">
                    <h1 class="modal-title fs-18 fw-medium mb-0">
                        Reason for Editing Invoice
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-20 pb-0">
                    <label class="label">
                        Reason <span class="text-danger">*</span>
                    </label>

                    <div class="form-floating">
                        <textarea id="invoiceEditReason"
                                class="form-control"
                                style="height:100px"
                                placeholder="Enter reason"
                                required></textarea>
                        <label>Enter Minimum 3 character</label>
                    </div>
                </div>

                <div class="modal-footer border-0 p-20 pt-0">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit"
                            id="invoiceReasonSubmitBtn"
                            class="btn btn-primary text-white"
                            disabled>
                        Submit & Continue
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            if (!$.fn.DataTable.isDataTable('#salesOrderTable')) {
                var dataTable = $('#salesOrderTable').DataTable({
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
                        url: "{{ route('invoice-orders.index') }}",
                        data: function(d) {
                              d.name = $('#nameFilter').val();
                        }
                    },
                    columns: [

                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'sales_order_number',
                            name: 'sales_order_number'
                        },
                        {
                            data: 'user',
                            name: 'user'
                        },
                        // {
                        //     data: 'delivered_qty',
                        //     name: 'delivered_qty'
                        // },
                        {
                            data: 'payment_status',
                            name: 'payment_status'
                        },
                        {
                            data: 'due_date',
                            name: 'due_date',
                            defaultContent: 'N/A',
                        },
                        {
                            data: 'net_amount',
                            name: 'net_amount'
                        },
                        {
                            data: 'balance_due',
                            name: 'balance_due'
                        },
                        // {
                        //     data: 'created_at',
                        //     name: 'created_at',

                        // },
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
           
        });
    </script>
    <script>
$(document).ready(function () {

    
    $(document).on('click', '.open-invoice-edit', function () {
        $('#invoiceId').val($(this).data('id'));
        $('#invoiceEditUrl').val($(this).data('url'));

        $('#invoiceEditReason').val('');
        $('#invoiceReasonSubmitBtn').prop('disabled', true);

        $('#invoiceEditReasonModal').modal('show');
    });

    $('#invoiceEditReason').on('input', function () {
        $('#invoiceReasonSubmitBtn').prop(
            'disabled',
            $(this).val().trim().length < 3
        );
    });

    $('#invoiceEditReasonForm').on('submit', function (e) {
        e.preventDefault();

        $.post("{{ route('invoice-orders.save-edit-remark') }}", {
            _token: "{{ csrf_token() }}",
            invoice_id: $('#invoiceId').val(),
            remark: $('#invoiceEditReason').val()
        }, function () {
            window.location.href = $('#invoiceEditUrl').val();
        });
    });

});
</script>

@endpush
