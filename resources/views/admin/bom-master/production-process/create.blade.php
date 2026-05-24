<style>
    .select2-container.is-invalid .select2-selection {
        border: 2px solid #dc3545 !important;
    }
</style>@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Add Production Process</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('bom-master.index') }}" class="text-decoration-none">BOM Master List</a>
                </li>
                <li class="breadcrumb-item active">Add Production Process</li>
            </ol>
        </nav>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @php
    $isLocked = $bomMaster->status == '1';
    @endphp
    @if($isLocked)
    <div class="alert alert-danger d-flex align-items-center justify-content-between">
        <div>
            <strong>🔒 Production Locked</strong><br>
            This production process is completed and cannot be modified.
        </div>
    </div>
    @endif


    <form action="{{ route('production-process.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                    <h3 class="mb-20">Production Process Information</h3>
                    <input type="hidden" name="bom_master_id" value="{{ $bomMaster->id }}">

                    <table class="table table-bordered" id="processTable">
                        <thead>
                            <tr>
                                <th width="10%">Step</th>
                                <th>Select Type</th>
                                <th>BOM Item</th>
                                <th>Roles</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            {{-- EDIT MODE --}}
                            @if(isset($processes) && $processes->count())
                            @foreach($processes as $i => $process)
                            <tr>
                                <td>{{ $i + 1 }}</td>

                                <td>
                                    <select name="steps[{{ $i }}][bom_type_id]"
                                        class="form-control" required {{ $isLocked ? 'disabled' : '' }}>
                                        <option value="">Select Type</option>
                                        @foreach ($bomTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ $process->bom_type_id == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                @php

                                $selectedRoles = $process->items
                                ->pluck('roles')
                                ->unique()
                                ->toArray();
                                @endphp

                                <td>
                                    <select name="steps[{{ $i }}][bom_item_id][]"
                                        class="form-control step-select" {{ $isLocked ? 'disabled' : '' }}
                                        multiple>
                                        @foreach($bomMaster->items as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $process->items->pluck('bom_item_id')->contains($item->id) ? 'selected' : '' }}>
                                            {{ $item->material->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="steps[{{ $i }}][roles][]"
                                        class="form-control step-selectss"
                                        {{ $isLocked ? 'disabled' : '' }}
                                        multiple required>
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ in_array($role->id, $selectedRoles) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-danger removeStep" {{ $isLocked ? 'disabled' : '' }}>X</button>
                                </td>
                            </tr>
                            @endforeach
                            @endif

                        </tbody>

                    </table>

                    <div class="col-lg-12">
                        <button type="button" id="addStep"
                            class="btn btn-primary fw-normal text-white mt-2" {{ $isLocked ? 'disabled' : '' }}>
                            + Add Step
                        </button>
                    </div>


                    {{-- Actions --}}
                    <div class="col-lg-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit"
                                class="btn btn-primary fw-normal text-white"
                                {{ $isLocked ? 'disabled' : '' }}>
                                {{ $isLocked ? 'Production Locked' : (isset($processes) ? 'Update Production' : '+ Add Production') }}
                            </button>

                            <a href="{{ route('bom-master.index') }}"
                                class="btn btn-danger fw-normal text-white">Cancel</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</div>
</form>
</div>
@endsection

@push('scripts')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let allBomItems = @json(
        $bomMaster -> items -> map(function($item) {
            return [
                'id' => $item -> id,
                'name' => $item -> material -> name
            ];
        })
    );


    let usedItems = [];


    $(document).ready(function() {
        initSelect2();
        refreshUsedItems();
        refreshAllSelects();
    });


    function initSelect2() {
        $('.step-select').select2({
            placeholder: 'Select BOM Items',
            width: '100%'
        });

        $('.step-selectss').select2({
            placeholder: 'Select Roles',
            width: '100%'
        });
    }

function refreshStepNumbers() {
    $('#processTable tbody tr').each(function(index) {
        $(this).find('td:first').text(index + 1);
    });
}
    function getNextStepNo() {
        return $('#processTable tbody tr').length + 1;
    }


    function refreshUsedItems() {
        usedItems = [];
        $('.step-select').each(function() {
            let vals = $(this).val() || [];
            vals.forEach(v => usedItems.push(parseInt(v)));
        });
    }

    function buildOptions(current = []) {
        let html = '';
        allBomItems.forEach(item => {
            let selected = current.includes(item.id) ? 'selected' : '';
            let disabled = (!current.includes(item.id) && usedItems.includes(item.id)) ? 'disabled' : '';
            html += `<option value="${item.id}" ${selected} ${disabled}>${item.name}</option>`;
        });
        return html;
    }

    function refreshAllSelects() {
        $('.step-select').each(function() {
            let current = ($(this).val() || []).map(Number);
            $(this).select2('destroy');
            $(this).html(buildOptions(current));
            $(this).val(current);
        });
        initSelect2();
    }


    function hasAvailableItems() {
        return allBomItems.some(item => !usedItems.includes(item.id));
    }


    $('#addStep').on('click', function() {

        // if (!hasAvailableItems()) {
        //     Swal.fire({
        //         icon: 'warning',
        //         title: 'No BOM Items Available',
        //         text: 'All BOM items are already assigned.'
        //     });
        //     return;
        // }

        let stepNo = getNextStepNo();

        let row = ` 
            <tr>
                <td>${stepNo}</td>
                <td>
                    <select name="steps[${stepNo}][bom_type_id]" class="form-control" required>
                        <option value="">Select Type</option>
                        @foreach ($bomTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="steps[${stepNo}][bom_item_id][]" 
                            class="form-control step-select" multiple>
                        ${buildOptions([])}
                    </select>
                </td>
                <td>
                    <select name="steps[${stepNo}][roles][]" 
                            class="form-control step-selectss" multiple required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <button type="button" class="btn btn-danger removeStep">X</button>
                </td>
            </tr>
        `;

        $('#processTable tbody').append(row);
 refreshStepNumbers();
        initSelect2();
        refreshUsedItems();
        refreshAllSelects();
    });

    $('form').on('submit', function(e) {

        let isValid = true;

        $('.step-selectss').each(function() {

            let value = $(this).val();
            let select2Box = $(this).next('.select2-container');

            if (!value || value.length === 0) {

                isValid = false;

                select2Box.addClass('is-invalid');

            } else {
                select2Box.removeClass('is-invalid');
            }
        });

        if (!isValid) {

            e.preventDefault();

            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select at least one role in each step'
            });
        }
    });
    $(document).on('change', '.step-selectss', function() {

        let value = $(this).val();
        let select2Box = $(this).next('.select2-container');

        if (value && value.length > 0) {
            select2Box.removeClass('is-invalid');
        }
    });
    $(document).on('click', '.removeStep', function() {

        if ($('#processTable tbody tr').length === 1) {
            Swal.fire('At least one step is required');
            return;
        }

        $(this).closest('tr').remove();
 refreshStepNumbers();
        refreshUsedItems();
        refreshAllSelects();
    });
</script>

@endpush