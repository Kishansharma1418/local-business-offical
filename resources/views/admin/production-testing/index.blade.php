<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')
@section('content')
<div class="main-content-container overflow-hidden">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Production Testing</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">

                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    <span class="text-secondary">Production Testing</span>
                </li>

            </ol>
        </nav>
    </div>
{{-- Success Message --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="default-table-area mx-minus-1">

            <div class="table-responsive overflow-none">

                <table class="table" id="productionTestingTable">

                    <thead>
                        <tr>
                            <th>Batch Number</th>
                            <th>Finished Good</th>
                            <th>Manufacturing Date</th>
                            <th>Status</th>
                            <th>QC Step</th>
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

$(function() {

    var table = $('#productionTestingTable').DataTable({

        processing: true,
        serverSide: true,

        ajax: {
            url: "{{ route('production-testing.index') }}",
            type: "GET"
        },

        columns: [

            {
                data: 'batch_number',
                name: 'batch_number'
            },

            {
                data: 'finished_good',
                name: 'finished_good'
            },

            {
                data: 'mfg_date',
                name: 'mfg_date'
            },

            {
                data: 'status',
                name: 'status'
            },

            {
                data: 'current_step',
                name: 'current_step'
            },

            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: "text-center"
            }

        ],

        dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l>" +
            "<'col-sm-6 d-flex justify-content-end'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",

    });

});

</script>

@endpush