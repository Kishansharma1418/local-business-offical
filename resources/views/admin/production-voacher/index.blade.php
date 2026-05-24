<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Production Voucher</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Production Voucher</span>
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
                            
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="modal fade" id="assignteam" tabindex="-1" aria-labelledby="assignTeamModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
                <form id="assignTeamForm" class="modal-content bg-white" action="{{ route('assignTeam') }}" method="POST">
                    @csrf

                    <!-- HIDDEN -->
                    <input type="hidden" id="stage" name="stage" value="PRODUCTION_VOACHER">
                    <input type="hidden" id="production_id" name="production_id" value="">

                    <!-- HEADER -->
                    <div class="modal-header border-border-color-40 p-20">
                        <h1 class="modal-title fs-18 fw-medium mb-0" id="assignTeamModalLabel">
                            Assign Team
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- BODY -->
                    <div class="modal-body p-20 pb-0">
                        <div class="row">

                            <!-- ROLE -->
                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">Role</label>
                                <select class="form-select form-control" id="role" name="role_id" required>
                                    <option value="">Select Role</option>
                                </select>
                            </div>

                            <!-- USER -->
                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">User</label>
                                <select class="form-select form-control" id="user" name="user_id" required>
                                    <option value="">Select User</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer border-0 p-20 pt-0">
                        <button type="button" class="btn btn-danger fw-normal text-white" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-primary fw-normal text-white">
                            Assign Team
                        </button>
                    </div>

                </form>
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
                    url: "{{ route('production-voucher.index') }}",
                    type: "GET",
                    data: function(d) {
                        d.finished_good_id = $('#finished_good_filter').val();
                    }
                },

                columns: [{
                        data: 'batch_number',
                        name: 'batch_number'
                    },
                    {
                        data: 'finished_good',
                        name: 'finished_good'
                    },
                   

                    {
                        data: 'status',
                        name: 'status'
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
        $(document).on('click', '.assign-team-btn', function() {
            let productionId = $(this).data('id');
            $('#production_id').val(productionId);

            $('#role').html('<option>Select Role</option>');
            $('#user').html('<option>Select User</option>');

            $.get(`/production-voucher-roles/${productionId}/roles`, function(roles) {
                roles.forEach(role => {
                    $('#role').append(`<option value="${role.id}">${role.name}</option>`);
                });
            });

            $('#assignteam').modal('show');
        });

        $('#role').on('change', function() {
            let roleId = $(this).val();
            $('#user').html('<option>Loading...</option>');

            $.get(`/roles/${roleId}/users`, function(users) {
                $('#user').html('<option>Select User</option>');
                users.forEach(user => {
                    $('#user').append(`<option value="${user.id}">${user.full_name}</option>`);
                });
            });
        });

        $('#assignTeamForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        $('#assignteam').modal('hide');
                        toastr.success('Team assigned successfully');
                        $('#bomMasterTable').DataTable().ajax.reload();
                    }
                },
                error: function(err) {
                    toastr.error('Something went wrong');
                    console.log(err.responseJSON);
                }
            });
        });
    </script>
@endpush
