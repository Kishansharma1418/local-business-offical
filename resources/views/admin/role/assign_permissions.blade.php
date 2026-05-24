@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Assign Permission</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('roles.index') }}" class="text-decoration-none">Roles List</a>
                    </li>
                    <li class="breadcrumb-item active">Assign Permission</li>
                </ol>
            </nav>
        </div>

        <div class="card shadow-sm border-0 rounded p-4 bg-white">
            <div class="text-center mb-4">
                <h3 class="role-title text-primary">
                    <i class="ri-shield-user-line me-2"></i>
                    Assign Permissions to Role ({{ Str::title(str_replace('_', ' ', $role->name)) }})
                </h3>
                <p class="text-muted">Easily manage and control role-based permissions</p>
            </div>

            <form id="addRoleForm" method="post" action="{{ route('assignPermission', $role->id) }}" class="row g-4">
                @csrf
                <div class="row mt-4">
                    @foreach ($permissions as $subGroup => $permissionSet)
                        <div class="col-md-4">
                            <div class="accordion mb-3" id="permissionsAccordion{{ $loop->index }}">
                                <div class="accordion-item border rounded shadow-sm">
                                    <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                        <button
                                            class="accordion-button collapsed d-flex justify-content-between align-items-center"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $loop->index }}" aria-expanded="false"
                                            aria-controls="collapse{{ $loop->index }}">
                                            <div>
                                                <input class="form-check-input module-checkbox me-2" type="checkbox"
                                                    onclick="toggleSubPermissions(this)" />
                                                <strong>{{ ucwords(str_replace('-', ' ', $subGroup)) }}</strong>
                                            </div>
                                        </button>
                                    </h2>

                                    <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading{{ $loop->index }}"
                                        data-bs-parent="#permissionsAccordion{{ $loop->index }}">
                                        <div class="accordion-body p-2">
                                            @php
                                                $mainGroups = $permissionSet->groupBy('main_group');
                                            @endphp

                                            @foreach ($mainGroups as $mainGroup => $permissionsInGroup)
                                                <div class="mb-2 pb-2 border-bottom">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <input class="form-check-input group-checkbox me-2"
                                                            id="group_{{ $mainGroup }}" type="checkbox"
                                                            onclick="toggleGroupPermissions(this)" />
                                                        <span class="fw-bold text-warning">
                                                            <i class="ri-folder-shield-2-line me-1"></i>
                                                            {{ ucfirst($mainGroup) }}
                                                        </span>
                                                    </div>
                                                    <div class="row ps-3">
                                                        @foreach ($permissionsInGroup as $permission)
                                                            <div class="col-12 mb-1">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox"
                                                                        type="checkbox"
                                                                        id="permission_{{ $permission->id }}"
                                                                        name="permissions[]"
                                                                        value="{{ $permission->name }}"
                                                                        @if (in_array($permission->name, $rolePermissions)) checked @endif />
                                                                    @php
                                                                        $parts = explode('-', $permission->name);
                                                                        $action = ucfirst($parts[0]);
                                                                        $label = in_array($action, [
                                                                            'Add',
                                                                            'Edit',
                                                                            'View',
                                                                            'Delete',
                                                                            'Show',
                                                                            'Export',
                                                                            'Upload',
                                                                            'Restore',
                                                                        ])
                                                                            ? $action
                                                                            : ucfirst(
                                                                                str_replace(
                                                                                    '-',
                                                                                    ' ',
                                                                                    $permission->name,
                                                                                ),
                                                                            );
                                                                    @endphp
                                                                    <label class="form-check-label small"
                                                                        for="permission_{{ $permission->id }}">
                                                                        {{ $label }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-12 mt-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-normal text-white">+ Assign Permissions</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-danger fw-normal text-white">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleSubPermissions(moduleCheckbox) {
            const accordion = $(moduleCheckbox).closest('.accordion-item');
            const permissions = accordion.find('.permission-checkbox');
            const groups = accordion.find('.group-checkbox');

            permissions.prop('checked', moduleCheckbox.checked);
            groups.prop('checked', moduleCheckbox.checked);
            groups.each(function() {
                updateGroupCheckboxes(this);
            });
        }

        function toggleGroupPermissions(groupCheckbox) {
            const groupBlock = $(groupCheckbox).closest('.mb-2');
            const permissions = groupBlock.find('.permission-checkbox');

            permissions.prop('checked', groupCheckbox.checked);
            updateGroupCheckboxes(groupCheckbox);
        }

        function updateGroupCheckboxes(groupCheckbox) {
            const groupBlock = $(groupCheckbox).closest('.mb-2');
            const permissions = groupBlock.find('.permission-checkbox');

            const allChecked = permissions.length > 0 && permissions.filter(':checked').length === permissions.length;
            $(groupCheckbox).prop('checked', allChecked);

            const accordion = $(groupCheckbox).closest('.accordion-item');
            const moduleCheckbox = accordion.find('.module-checkbox');
            const allGroups = accordion.find('.group-checkbox');
            const allGroupsChecked = allGroups.length > 0 && allGroups.filter(':checked').length === allGroups.length;
            moduleCheckbox.prop('checked', allGroupsChecked);
        }

        function updateCheckboxStates() {
            $('.group-checkbox').each(function() {
                const groupBlock = $(this).closest('.mb-2');
                const permissions = groupBlock.find('.permission-checkbox');
                const allChecked = permissions.length > 0 && permissions.filter(':checked').length === permissions
                    .length;
                $(this).prop('checked', allChecked);
            });
            $('.module-checkbox').each(function() {
                const accordion = $(this).closest('.accordion-item');
                const groups = accordion.find('.group-checkbox');
                const allGroupsChecked = groups.length > 0 && groups.filter(':checked').length === groups.length;
                $(this).prop('checked', allGroupsChecked);
            });
        }
        $(document).ready(function() {
            updateCheckboxStates();
            $(document).on('change', '.permission-checkbox', function() {
                updateGroupCheckboxes($(this).closest('.mb-2').find('.group-checkbox'));
            });
        });
    </script>
@endpush
