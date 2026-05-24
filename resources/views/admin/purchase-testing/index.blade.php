<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')

@section('content')

<div class="main-content-container overflow-hidden">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">

        <h3 class="mb-0">Purchase Testing</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">

                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    <span class="text-secondary">Purchase Testing</span>
                </li>

            </ol>
        </nav>

    </div>


    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="default-table-area mx-minus-1">
            <div class="table-responsive overflow-none">

                <table class="table" id="testingTable">

                    <thead>

                        <tr>

                            <th scope="col" class="fw-medium">Vendor Detail</th>
                            <th scope="col" class="fw-medium">Purchase Order Number</th>
                            <th scope="col" class="fw-medium">Status</th>
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

<script>
    $(document).ready(function() {

        if (!$.fn.DataTable.isDataTable('#testingTable')) {

            $('#testingTable').DataTable({

                processing: true,
                serverSide: true,
                responsive: true,
                lengthMenu: [10, 20, 50, 100],

                language: {
                    processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border"></div></div></div>'
                },

                ajax: {
                    url: "{{ route('purchase-testing.index') }}"
                },

                columns: [

                    {
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
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }

                ],

                dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",

            });

        }

    });
</script>

@endpush