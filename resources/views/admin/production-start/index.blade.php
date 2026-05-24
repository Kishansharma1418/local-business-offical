<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Production Start</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Production Start</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <!-- Table -->
            <div class="default-table-area mx-minus-1">
                <div class="table-responsive overflow-none">
                    <table class="table" id="bomMasterTable">
                        <thead>
                            <tr>
                                <th>Batch Number</th>
                                <th>Finished Good</th>
                                <th>Manufacturing Date</th>
                                <th>Status</th>
                                <th>Current Step</th>
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

            var table = $('#bomMasterTable').DataTable({
                processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ route('production-start.index') }}",
                    type: "GET",
                    data: function(d) {
                        d.finished_good_id = $('#finished_good_filter').val();
                    }
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

      
            $('#product_filter').change(function() {
                table.ajax.reload();
            });
        
        });
    </script>

      <script>
        $(document).on('click', '.assign-team-btn', function () {
            let productionId = $(this).data('id');
            $('#production_id').val(productionId);

            $('#role').html('<option>Select Role</option>');
            $('#user').html('<option>Select User</option>');

            $.get(`/production-voucher-roles/${productionId}/roles`, function (roles) {
                roles.forEach(role => {
                    $('#role').append(`<option value="${role.id}">${role.name}</option>`);
                });
            });

            $('#assignteam').modal('show');
        });

        $('#role').on('change', function () {
            let roleId = $(this).val();
            $('#user').html('<option>Loading...</option>');

            $.get(`/roles/${roleId}/users`, function (users) {
                $('#user').html('<option>Select User</option>');
                users.forEach(user => {
                    $('#user').append(`<option value="${user.id}">${user.full_name}</option>`);
                });
            });
        });

        $('#assignTeamForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    if (res.success) {
                        $('#assignteam').modal('hide');
                        toastr.success('Team assigned successfully');
                        $('#bomMasterTable').DataTable().ajax.reload();
                    }
                },
                error: function (err) {
                    toastr.error('Something went wrong');
                    console.log(err.responseJSON);
                }
            });
        });

    </script>
@endpush
