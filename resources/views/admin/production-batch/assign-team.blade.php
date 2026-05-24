@extends('include.master')
@push('styles')
    <style>
        body {
            background-color: #f8f9fa !important;
        }

        .process-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            margin-bottom: 25px;
            padding: 20px;
        }

        .process-title {
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .role-column {
            background: #ffffff;
            padding: 15px;
            border: 1px solid #f1f1f1;
            border-radius: 6px;
            height: 100%;
        }

        .role-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #212529;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 42px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            background-color: #ffffff;
        }

        .select2-container--default .select2-selection__choice {
            background-color: #eef1f4;
            border: 1px solid #d6d8db;
            color: #333;
        }

        .select2-dropdown {
            z-index: 9999 !important;
        }
    </style>
@endpush


@section('content')
    <div class="main-content-container py-4">

        <div class="container-fluid">

            <div class="mb-4">
                <h4 class="fw-semibold text-dark">Assign Team</h4>
            </div>

            <form action="{{ route('assignTeam') }}" method="POST" id="assignTeamForm">
                @csrf
                <input type="hidden" name="batch_id" value="{{ $batch->id }}">
                <input type="hidden" name="module_type" value="{{ $module }}">
                <input type="hidden" name="bom_master_id" value="{{ $batch->bom_master_id }}">

                @foreach ($processes as $process)
                    <div class="process-card">

                        <div class="process-title-custom text-dark">
                            <i class="fas fa-layer-group me-2 text-primary"></i>
                            {{ $process->bomType->name }}
                        </div>


                        <div class="row g-4">

                            @foreach ($process->items->groupBy('roles') as $roleId => $items)
                                @php
                                    $role = $items->first()->role;
                                @endphp

                                <div class="col-md-4">

                                    <div class="role-column">

                                        <div class="role-title">
                                            {{ $role->name }}
                                        </div>

                                        <select name="users[{{ $process->bom_type_id }}][{{ $roleId }}][]"
                                            class="form-control select2" multiple>

                                            @foreach ($role->users as $user)
                                                @php
                                                    $selectedUsers =
                                                        $assignedTeams[$process->bom_type_id][$roleId] ?? collect();
                                                @endphp

                                                <option value="{{ $user->id }}"
                                                    {{ $selectedUsers->pluck('user_id')->contains($user->id) ? 'selected' : '' }}>
                                                    {{ $user->full_name }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>

                                </div>
                            @endforeach

                        </div>

                    </div>
                @endforeach

                <div class="text-end mt-4">
                    <button type="submit" id="subdtaaa" class="btn btn-primary px-4 text-white">
                        Assign Team
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            const submitBtn = $('#subdtaaa');

            $('.select2').select2({
                placeholder: "Select Team Members",
                width: '100%'
            });

            submitBtn.prop('disabled', false).text('Assign Team');
            $('#assignTeamForm').on('submit', function(e) {

                e.preventDefault();
                submitBtn.prop('disabled', false).text('Assign Team');

                let emptyFound = false;

                $('select[name^="users"]').each(function() {

                    let selected = $(this).val();

                    if (!selected || selected.length === 0) {
                        emptyFound = true;
                        return false;
                    }

                });

                if (emptyFound) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please select at least 1 team member for each role.'
                    });

                    return;
                }
                submitBtn.prop('disabled', true).text('Processing...');
                this.submit();
            });

        });
    </script>
@endpush
