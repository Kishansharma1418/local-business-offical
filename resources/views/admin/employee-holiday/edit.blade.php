@extends('include.master')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <style>
        /* Select2 basic style */
        .select2-container--bootstrap-5 .select2-selection--multiple {
            min-height: 55px !important;
            border: 1px solid #ced4da !important;
            border-radius: 6px !important;
            transition: border-color 0.3s;
        }

        /* Error & success border */
        .is-invalid+.select2-container--bootstrap-5 .select2-selection--multiple {
            border-color: #dc3545 !important;
        }

        .is-valid+.select2-container--bootstrap-5 .select2-selection--multiple {
            border-color: #28a745 !important;
        }

        /* Inline error message */
        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 14px;
            margin-top: 4px;
        }

        .is-invalid~.invalid-feedback {
            display: block;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple {
            min-height: 55px !important;
            border: 1px solid #ced4da !important;
            border-radius: 6px !important;
            transition: border-color 0.3s;
            display: flex !important;
            align-items: center !important;
            flex-wrap: wrap !important;
            /* 🔥 ADDED — side-by-side badges */
            padding-top: 5px !important;
        }

        /* Selected options (badge style inline) */
        .select2-container--bootstrap-5 .select2-selection__choice {
            display: inline-block !important;
            margin-right: 6px !important;
            margin-bottom: 4px !important;
            padding: 4px 8px !important;
        }

        /* Proper vertical alignment for placeholder */
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__placeholder {
            padding-left: 6px !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
        }
    </style>
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Employee Holiday</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <i class="ri-home-8-line text-primary me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Edit Employee Holiday</li>
                </ol>
            </nav>
        </div>

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

        <form method="POST" action="{{ route('employee-holiday.update', encrypt($holiday->id)) }}" id="holidayForm"
            novalidate>
            @csrf
            @method('PUT')

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <h4 class="mb-3">Holiday Information</h4>
                <div class="row">

                    <!-- Holiday Title -->
                    <div class="col-lg-6 mb-3">
                        <label class="label fs-16 mb-2">Holiday Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $holiday->title) }}"
                            class="form-control" placeholder="Enter holiday title" required>

                    </div>

                    <!-- Branch -->
                    <div class="col-lg-6 mb-3">
                        <label class="label fs-16 mb-2">Branch <span class="text-danger">*</span></label>
                        <select name="branch_id[]" id="branchSelect" class="form-select select2" multiple required>
                            <option value="all">-- Select All Branches --</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ in_array($branch->id, $selectedBranches ?? []) ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <!-- Date Range -->
                    <div class="col-lg-6 mb-3">
                        <label for="start_date" class="form-label">Holiday Date Range <span
                                class="text-danger">*</span></label>
                        <input type="text" name="start_date" id="start_date" class="form-control"
                            value="{{ old('start_date', $holiday->start_date ) }}"
                            required>


                    </div>

                    <!-- Description -->
                    <div class="col-lg-12 mb-3">
                        <label class="label fs-16 mb-2">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Enter holiday description">{{ old('description', $holiday->description) }}</textarea>
                    </div>

                    <!-- Status -->
                    <div class="col-lg-4 mb-3">
                        <label class="label fs-16 mb-2">Status</label>
                        <select class="form-control" name="status">
                            <option value="1" {{ old('status', $holiday->status) == '1' ? 'selected' : '' }}>Active
                            </option>
                            <option value="0" {{ old('status', $holiday->status) == '0' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-lg-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-normal text-white">Update Holiday</button>
                            <a href="{{ route('employee-holiday.index') }}"
                                class="btn btn-danger fw-normal text-white">Cancel</a>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            const $branchSelect = $('#branchSelect');

            // ✅ Initialize Select2
            $branchSelect.select2({
                theme: 'bootstrap-5',
                placeholder: "Select Branches",
                allowClear: true
            });

            // ✅ "Select All" option functionality
            $branchSelect.on('select2:select', function(e) {
                if (e.params.data.id === 'all') {
                    const allValues = [];
                    $branchSelect.find('option').each(function() {
                        if ($(this).val() !== 'all') {
                            allValues.push($(this).val());
                        }
                    });
                    $branchSelect.val(allValues).trigger('change.select2');
                }
            });

            // ✅ Daterangepicker
            $('#start_date').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                minDate: moment().startOf('day')
            });

            // ✅ Form validation (red/green border)
            $('#holidayForm').on('submit', function(e) {
                let isValid = true;

                const title = $('input[name="title"]');
                const branches = $branchSelect.val();
                const dateRange = $('#date_range');

                if (title.val().trim() === '') {
                    title.addClass('is-invalid').removeClass('is-valid');
                    isValid = false;
                } else {
                    title.addClass('is-valid').removeClass('is-invalid');
                }

                if (!branches || branches.length === 0) {
                    $branchSelect.addClass('is-invalid').removeClass('is-valid');
                    isValid = false;
                } else {
                    $branchSelect.addClass('is-valid').removeClass('is-invalid');
                }

                if (dateRange.val().trim() === '') {
                    dateRange.addClass('is-invalid').removeClass('is-valid');
                    isValid = false;
                } else {
                    dateRange.addClass('is-valid').removeClass('is-invalid');
                }

                if (!isValid) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 100
                    }, 400);
                } else {
                    $(this).find('button[type="submit"]').prop('disabled', true).text('Processing...');
                }
            });

            // ✅ Real-time validation
            $('input, textarea').on('input', function() {
                if ($(this).val().trim() === '') {
                    $(this).addClass('is-invalid').removeClass('is-valid');
                } else {
                    $(this).addClass('is-valid').removeClass('is-invalid');
                }
            });

            $branchSelect.on('change', function() {
                const selected = $(this).val();
                if (!selected || selected.length === 0) {
                    $(this).addClass('is-invalid').removeClass('is-valid');
                } else {
                    $(this).addClass('is-valid').removeClass('is-invalid');
                }
            });
        });
    </script>
@endpush
