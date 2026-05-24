<style>
    input.form-control.form-control-sm {
        height: 43px;
         /* display:none; */
    }
</style>
@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">
            Asset Management - {{ $employee->full_name }}
        </h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}"
                        class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item ">
                    <a href="{{ route('employee.index') }}">Employees</a>
                </li>
                <li class="breadcrumb-item active"> 
                    Asset Management
                </li>
            </ol>
        </nav>
    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        {{-- Top Buttons --}}
        <div class="row p-20 g-3">

            <div class="col-md-3 col-lg-2">
                <a href="{{ route('employee.assets.create', encrypt($employee->id)) }}"
                    class="btn btn-primary text-white w-100 p-3 fs-16">
                     Add Asset
                </a>
            </div>

           

        </div>

        {{-- Table --}}
        <div class="default-table-area mx-minus-1">
            <div class="table-responsive">
                <table class="table" id="assetTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            {{-- <th style="text-align:center">Action</th> --}}
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

    var table = $('#assetTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        lengthMenu: [10,20,50,100],
        ajax: {
            url: "{{ route('employee.assets.index', encrypt($employee->id)) }}",
            data: function (d) {
                d.search_value = $('#assetSearch').val();
            }
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'code', name: 'code' },
            { data: 'asset_type', name: 'asset_type' },
            { data: 'status_badge', name: 'status', orderable:false },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            // { data: 'action', name: 'action', orderable:false, searchable:false }
        ],
           dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +

                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
    });

    $('#assetSearch').keyup(function() {
        table.draw();
    });

    // Delete
    $(document).on('click', '.deleteAssetBtn', function() {

        if(confirm('Are you sure you want to delete this asset?')) {

            var id = $(this).data('id');

            $.ajax({
                url: "{{ url('employee-asset') }}/" + id,
                type: "DELETE",
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