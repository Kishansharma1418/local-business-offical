@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Add Customer</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('customers.index') }}" class="d-flex align-items-center text-decoration-none">
                            <span class="text-body fs-14 hover">Customer List</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="text-secondary">Add Customer</span>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('customers.store') }}" enctype="multipart/form-data" class="needs-validation"
            novalidate>
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Customer Information</h3>
                        <div class="row">
                            {{-- Basic Info --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                    required>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Mobile No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="mobile_no" value="{{ old('mobile_no') }}"
                                    maxlength="10" required   oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Customer Code<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="code" id="code"
                                    value="{{ old('code') }}" required>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Contact Person <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                                    name="contact_person" value="{{ old('contact_person') }}" required>
                                @error('contact_person')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Branch</label>
                                <select name="branch_id" id="branch" class="form-control">
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->branch_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Customer Type --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Customer Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="customer_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Doctor" {{ old('customer_type') == 'Doctor' ? 'selected' : '' }}>Doctor
                                    </option>
                                    <option value="Chemist" {{ old('customer_type') == 'Chemist' ? 'selected' : '' }}>
                                        Chemist</option>
                                    <option value="Distributor"
                                        {{ old('customer_type') == 'Distributor' ? 'selected' : '' }}>Distributor</option>
                                    <option value="Stockist" {{ old('customer_type') == 'Stockist' ? 'selected' : '' }}>
                                        Stockist</option>
                                    <option value="Hospital" {{ old('customer_type') == 'Hospital' ? 'selected' : '' }}>
                                        Hospital</option>
                                    <option value="Other" {{ old('customer_type') == 'Other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="gst_type" class="form-label fw-semibold">GST Type</label>

                                <select name="gst_type" id="gst_type" class="form-control gst-tooltip">
                                    <option value="">Select GST Type</option>

                                    <option value="Regular" {{ old('gst_type') == 'Regular' ? 'selected' : '' }}
                                        title="Business Registered Under GST">
                                        Registered Business (Regular)
                                    </option>

                                    <option value="Composition" {{ old('gst_type') == 'Composition' ? 'selected' : '' }}
                                        title="Business Registered Under GST With Composition Scheme">
                                        Registered Business (Composition)
                                    </option>

                                    <option value="Unregistered" {{ old('gst_type') == 'Unregistered' ? 'selected' : '' }}
                                        title="Small Supplier Not Registered Under GST">
                                        Unregistered Business
                                    </option>

                                    <option value="Overseas" {{ old('gst_type') == 'Overseas' ? 'selected' : '' }}
                                        title="Person With No Business in India">
                                        Overseas
                                    </option>
                                </select>
                            </div>

                            {{-- Tax Info --}}
                            <div class="col-lg-4 mb-20 gst" style="display:none;">
                                <label class="label fs-16 mb-2">GST Number</label>
                                <input type="text" class="form-control" name="gst_no" value="{{ old('gst_no') }}"
                                    maxlength="15" pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$"
                                    title="Please enter a valid GST Number (e.g. 22ABCDE1234F1Z5)"
                                    placeholder="Enter GST Number" oninput="this.value = this.value.toUpperCase();">
                            </div>

                            <div class="col-lg-4 mb-20 place" style="display:none;">
                                <label class="label fs-16 mb-2">Place Of Supply</label>
                                <select name="state_id" class="form-control">
                                    <option value="">Select State</option>
                                    @foreach ($state as $st)
                                        <option value="{{ $st->id }}"
                                            {{ old('state_id') == $st->id ? 'selected' : '' }}>
                                            {{ $st->name }} ({{ $st->iso2 }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">PAN No</label>
                                <input type="text" class="form-control" name="pan_no" value="{{ old('pan_no') }}"
                                    maxlength="10" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}"
                                    title="Please enter valid PAN (e.g. ABCDE1234F)"
                                    oninput="this.value = this.value.toUpperCase();">
                            </div>

                            {{-- Credit Details --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Credit Limit <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="credit_limit" required min='0' >
                            </div>


                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Credit Days</label>

                                <select name="payment_terms_id"
                                    class="form-control @error('payment_terms_id') is-invalid @enderror">

                                    <option value="">Select Credit Days</option>

                                    @foreach ($paymentTerms as $term)
                                        <option value="{{ $term->id }}"
                                            {{ old('payment_terms_id') == $term->id ? 'selected' : '' }}>
                                            {{ $term->name }} ({{ $term->days }} Days)
                                        </option>
                                    @endforeach
                                </select>

                                @error('payment_terms_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- Blocked Status --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Is Blocked</label>
                                <select class="form-control" name="is_blocked" id="is_blocked">
                                    <option value="0" {{ old('is_blocked') == '0' ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('is_blocked') == '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>

                            <div class="col-lg-12 mb-20 blocked_reason_box" style="display:none;">
                                <label class="label fs-16 mb-2">Blocked Reason</label>
                                <textarea class="form-control" name="blocked_reason" rows="3" placeholder="Enter reason for blocking">{{ old('blocked_reason') }}</textarea>
                            </div>

                            {{-- Status / Login --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Is Login Allowed<span class="text-danger">*</span></label>
                                <select class="form-control" name="is_login" required>
                                    <option value="1" {{ old('is_login') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_login') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Status</label>
                                <select class="form-control" name="status">
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="col-lg-12 mt-4">
                                <h4 class="mb-3 mt-4">Billing Address</h4>
                                <input type="hidden" name="addresses[0][address_type]" value="Billing">

                                <div class="row">
                                    <div class="col-lg-4 mb-20">
                                        <label class="label fs-16 mb-2">Country *</label>
                                        <select name="addresses[0][country_id]" class="form-control billing-country"
                                            required>
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4 mb-20">
                                        <label class="label fs-16 mb-2">State *</label>
                                        <select name="addresses[0][state_id]" class="form-control billing-state"
                                            required></select>
                                    </div>

                                    <div class="col-lg-4 mb-20">
                                        <label class="label fs-16 mb-2">City *</label>
                                        <select name="addresses[0][city_id]" class="form-control billing-city"
                                            required></select>
                                    </div>

                                    <div class="col-lg-12 mb-20">
                                        <label class="label fs-16 mb-2">Address Line 1 *</label>
                                        <textarea name="addresses[0][address_line1]" class="form-control" required></textarea>
                                    </div>

                                    <div class="col-lg-12 mb-20">
                                        <label class="label fs-16 mb-2">Address Line 2</label>
                                        <textarea name="addresses[0][address_line2]" class="form-control"></textarea>
                                    </div>

                                    <div class="col-lg-4 mb-20">
                                        <label class="label fs-16 mb-2">Pincode</label>
                                        <input type="text" name="addresses[0][pincode]" class="form-control" maxlength="6">
                                    </div>
                                </div>

                                <h4 class="mb-3 mt-4">Shipping Address</h4>
                                <input type="hidden" name="addresses[1][address_type]" value="Shipping">

                                <div class="row">
                                    <div class="col-lg-4 mb-20">
                                        <label class="label fs-16 mb-2">Country *</label>
                                        <select name="addresses[1][country_id]" id="country"
                                            class="form-control shipping-country" required>
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4 mb-20">
                                        <label class="label fs-16 mb-2">State *</label>
                                        <select name="addresses[1][state_id]" id="state"
                                            class="form-control shipping-state" required></select>
                                    </div>

                                    <div class="col-lg-4 mb-20">
                                        <label class="label fs-16 mb-2">City *</label>
                                        <select name="addresses[1][city_id]" id="city"
                                            class="form-control shipping-city" required></select>
                                    </div>

                                    <div class="col-lg-12 mb-20">
                                        <label class="label fs-16 mb-2">Address Line 1 *</label>
                                        <textarea name="addresses[1][address_line1]" class="form-control" required></textarea>
                                    </div>

                                    <div class="col-lg-12 mb-20">
                                        <label class="label fs-16 mb-2">Address Line 2</label>
                                        <textarea name="addresses[1][address_line2]" class="form-control"></textarea>
                                    </div>

                                    <div class="col-lg-4 mb-20">
                                        <label class="label fs-16 mb-2">Pincode</label>
                                        <input type="text" name="addresses[1][pincode]" class="form-control" maxlength="6">
                                    </div>
                                </div>

                            </div>


                            {{-- Submit --}}
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-normal text-white">+ Add
                                        Customer</button>
                                    <a href="{{ route('customers.index') }}"
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const gstSelect = document.querySelector('#gst_type');

            gstSelect.addEventListener('mouseover', function() {
                let option = gstSelect.options[gstSelect.selectedIndex];

                if (gstSelect._tooltipInstance) {
                    gstSelect._tooltipInstance.dispose();
                }

                gstSelect.setAttribute("data-bs-toggle", "tooltip");
                gstSelect.setAttribute("title", option.title);

                gstSelect._tooltipInstance = new bootstrap.Tooltip(gstSelect);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#is_blocked').on('change', function() {
                if ($(this).val() == '1') {
                    $('.blocked_reason_box').slideDown();
                } else {
                    $('.blocked_reason_box').slideUp();
                }
            }).trigger('change');

            $('#gst_type').on('change', function() {
                let val = $(this).val();

                if (val == 'Regular' || val == 'Composition') {
                    $('.gst').slideDown();
                    $('.place').slideDown();
                } else if (val == 'Unregistered' || val == 'Overseas' || val == '') {
                    $('.gst').slideUp();
                    $('.place').slideUp();
                } else {
                    $('.gst').slideUp();
                    $('.place').slideUp();
                }
            }).trigger('change');

            $('form').on('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(this).addClass('was-validated');
                } else {
                    $(this).find('button[type="submit"]').prop('disabled', true).text('Processing...');
                }
            });

            setupRealtimeValidation('Customer', 'code', '#code');
            setupRealtimeValidation('Customer', 'email', 'input[name="email"]');
        });
    </script>
    <script>
        $(document).ready(function() {

            let oldState = "{{ old('state_id') }}";
            let oldCity = "{{ old('city_id') }}";
            let oldCountry = "{{ old('country_id') }}";

            if (oldCountry) {
                loadStates(oldCountry, oldState);
                if (oldState) {
                    loadCities(oldState, oldCity);
                }
            }

            $('#country').on('change', function() {
                let id = $(this).val();
                $('#state').html('<option value="">Select State</option>');
                $('#city').html('<option value="">Select City</option>');
                if (id) loadStates(id);
            });

            $('#state').on('change', function() {
                let id = $(this).val();
                $('#city').html('<option value="">Select City</option>');
                if (id) loadCities(id);
            });

            function loadStates(countryID, selected = null) {
                $.get('/get-states/' + countryID, function(data) {
                    $.each(data, function(i, val) {
                        $('#state').append('<option value="' + val.id + '"' +
                            (selected == val.id ? ' selected' : '') + '>' + val.name +
                            '</option>');
                    });
                });
            }

            function loadCities(stateID, selected = null) {
                $.get('/get-cities/' + stateID, function(data) {
                    $.each(data, function(i, val) {
                        $('#city').append('<option value="' + val.id + '"' +
                            (selected == val.id ? ' selected' : '') + '>' + val.name +
                            '</option>');
                    });
                });
            }

        });
    </script>
    <script>
        $(document).ready(function() {

            // ============================
            // BILLING ADDRESS CASCADING
            // ============================

            $('.billing-country').on('change', function() {
                let countryId = $(this).val();
                let stateSelect = $('.billing-state');
                let citySelect = $('.billing-city');

                stateSelect.html('<option value="">Select State</option>');
                citySelect.html('<option value="">Select City</option>');

                if (countryId) {
                    $.get('/get-states/' + countryId, function(data) {
                        $.each(data, function(i, val) {
                            stateSelect.append(
                                '<option value="' + val.id + '">' + val.name +
                                '</option>'
                            );
                        });
                    });
                }
            });

            $('.billing-state').on('change', function() {
                let stateId = $(this).val();
                let citySelect = $('.billing-city');

                citySelect.html('<option value="">Select City</option>');

                if (stateId) {
                    $.get('/get-cities/' + stateId, function(data) {
                        $.each(data, function(i, val) {
                            citySelect.append(
                                '<option value="' + val.id + '">' + val.name +
                                '</option>'
                            );
                        });
                    });
                }
            });

        });
    </script>
@endpush
