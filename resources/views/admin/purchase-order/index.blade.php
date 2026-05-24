<style>
    input.form-control.form-control-sm {
        height: 43px;

    }

    div#employeTable_filter {}
</style>
@extends('include.master')
@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0"> Purchase Order List</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>

                <li class="breadcrumb-item active" aria-current="page">
                    <span class="text-secondary">Purchase Order List</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="row p-20 g-3">
            <div class="col-md-3 col-lg-2">
                <a href="{{ route('purchase-order.create') }}" class="btn btn-primary fw-normal text-white w-100 p-3 fs-16">
                    + Add Purchase Order
                </a>
            </div>

            <!-- <div class="col-md-9 col-lg-10 d-flex justify-content-end">
                    <input type="text" name="value" id="allGlobal" class="form-control" placeholder="Search Purchase Order Details"
                    style="width: 300px; height: 55px;">
                </div> -->
        </div>

        <div class="default-table-area mx-minus-1 ">
            <div class="table-responsive overflow-none">
                <table class="table" id="employeTable">
                    <thead>
                        <tr>
                            <th scope="col" class="fw-medium">Vendor Detail</th>
                            <th scope="col" class="fw-medium">Purchase Order Number</th>
                            <th scope="col" class="fw-medium">Status</th>
                            @role('Store')
                            <th scope="col" class="fw-medium">Expected Delivery Date</th>
                            @endrole
                            <th scope="col" class="fw-medium">Created at</th>
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
                    url: "{{ route('purchase-order.index') }}",
                    data: function(d) {
                        d.value = $('#allGlobal').val();

                    }
                },
                columns: [{
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'po_number',
                        name: 'po_number'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                  @if(auth()->user()->hasRole('Store'))
                    {
                        data: 'expected_delivery_date',
                        name: 'expected_delivery_date'
                    },
                   @endif

                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
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

        $('#allGlobal').on('keyup', function() {
            dataTable.ajax.reload();
        });


    });
</script>
@endpush