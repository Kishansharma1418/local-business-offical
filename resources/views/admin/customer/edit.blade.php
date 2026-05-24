@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Customer</h3>

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
                        <span class="text-secondary">Edit Customer</span>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Success/Error --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('customers.update', encrypt($customer->id)) }}" enctype="multipart/form-data"
            class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Customer Information</h3>

                        <div class="row">
                            {{-- Name --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Name<span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $customer->name) }}" required>
                            </div>

                            {{-- Email --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Email<span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $customer->email) }}" required>
                            </div>

                            {{-- Mobile --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Mobile No<span class="text-danger">*</span></label>
                                <input type="text" name="mobile_no" maxlength="10" class="form-control"
                                    value="{{ old('mobile_no', $customer->mobile_no) }}" required  
                                     oninput="this.value = this.value.replace(/[^0-9]/g, '');" >
                            </div>

                            {{-- Code --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Customer Code<span class="text-danger">*</span></label>
                                <input type="text" name="code" id="code" class="form-control"
                                    value="{{ old('code', $customer->code) }}" required>
                            </div>

                            {{-- Contact Person --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Contact Person<span class="text-danger">*</span></label>
                                <input type="text" name="contact_person" class="form-control"
                                    value="{{ old('contact_person', $customer->contact_person) }}" required>
                            </div>

                            {{-- Branch --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Branch</label>
                                <select name="branch_id" class="form-control">
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id', $customer->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->branch_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Customer Type --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Customer Type<span class="text-danger">*</span></label>
                                <select name="customer_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    @foreach (['Doctor', 'Chemist', 'Distributor', 'Stockist', 'Hospital', 'Other'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('customer_type', $customer->customer_type) == $type ? 'selected' : '' }}>
                                            {{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- GST Type --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">GST Type</label>
                                <select name="gst_type" id="gst_type" class="form-control">
                                    <option value="">Select GST Type</option>
                                    @foreach (['Regular', 'Composition', 'Unregistered', 'Overseas'] as $gt)
                                        <option value="{{ $gt }}"
                                            {{ old('gst_type', $customer->gst_type) == $gt ? 'selected' : '' }}>
                                            {{ $gt }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- GST No --}}
                            <div class="col-lg-4 mb-20 gst"
                                style="{{ in_array($customer->gst_type, ['Regular', 'Composition']) ? '' : 'display:none;' }}">
                                <label class="label fs-16 mb-2">GST Number</label>
                                <input type="text" name="gst_no" class="form-control"
                                    value="{{ old('gst_no', $customer->gst_no) }}" maxlength="15">
                            </div>

                            {{-- Place of Supply --}}
                            <div class="col-lg-4 mb-20 place"
                                style="{{ in_array($customer->gst_type, ['Regular', 'Composition']) ? '' : 'display:none;' }}">
                                <label class="label fs-16 mb-2">Place Of Supply</label>
                                <select name="state_id" class="form-control">
                                    <option value="">Select State</option>
                                    @foreach ($gst_states as $st)
                                        <option value="{{ $st->id }}"
                                            {{ old('state_id', $customer->state_id) == $st->id ? 'selected' : '' }}>
                                            {{ $st->name }} ({{ $st->iso2 }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- PAN --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">PAN</label>
                                <input type="text" name="pan_no" class="form-control"
                                    value="{{ old('pan_no', $customer->pan_no) }}" maxlength="10">
                            </div>

                            {{-- Credit Limit --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Credit Limit<span class="text-danger">*</span> </label>
                                <input type="number" name="credit_limit" class="form-control" required onkeydown="return event.key !== '-'"
                                    oninput="this.value = this.value < 0 ? 0 : this.value"
                                    value="{{ old('credit_limit', $customer->credit_limit) }}">
                            </div>

                            {{-- Credit Days --}}
                           
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">
                                   Credit Days
                                </label>

                                <select name="payment_terms_id" class="form-control">
                                    <option value="">Select Credit Days</option>

                                    @foreach ($paymentTerms as $term)
                                        <option value="{{ $term->id }}"
                                            {{ old('payment_terms_id', $customer->payment_terms_id) == $term->id ? 'selected' : '' }}>
                                            {{ $term->name }}
                                            @if ($term->days)
                                                ({{ $term->days }} Days)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>



                            {{-- Is Blocked --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Is Blocked</label>
                                <select name="is_blocked" id="is_blocked" class="form-control">
                                    <option value="0" {{ old('is_blocked', $customer->is_blocked) == 0 ? 'selected' : '' }}>
                                        No</option>
                                    <option value="1" {{ old('is_blocked', $customer->is_blocked) == 1 ? 'selected' : '' }}>
                                        Yes</option>
                                </select>
                            </div>

                            {{-- Blocked Reason --}}
                            <div class="col-lg-12 mb-20 blocked_reason_box"
                                style="{{ $customer->is_blocked ? '' : 'display:none;' }}">
                                <label class="label fs-16 mb-2">Blocked Reason</label>
                                <textarea name="blocked_reason" class="form-control">{{ old('blocked_reason', $customer->blocked_reason) }}</textarea>
                            </div>

                            {{-- Login --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Is Login Allowed</label>
                                <select name="is_login" class="form-control">
                                    <option value="1" {{ old('is_login', $customer->is_login) == 1 ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="0" {{ old('is_login', $customer->is_login) == 0 ? 'selected' : '' }}>No
                                    </option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ old('status', $customer->status) == 1 ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ old('status', $customer->status) == 0 ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            {{-- Address --}}
                            <div class="col-lg-12 mt-4">
                                <h4 class="mb-3">Address Information</h4>

                                @foreach ($customer->addresses as $index => $address)
                                    <h5 class="mb-3">{{ $address->address_type }} Address</h5>

                                    <input type="hidden" name="addresses[{{ $index }}][id]"
                                        value="{{ $address->id }}">
                                    <input type="hidden" name="addresses[{{ $index }}][address_type]"
                                        value="{{ $address->address_type }}">

                                    <div class="row">

                                        {{-- Country --}}
                                        <div class="col-lg-4 mb-20">
                                            <label>Country</label>
                                            <select name="addresses[{{ $index }}][country_id]"
                                                class="form-control country-dd" data-index="{{ $index }}"
                                                required>
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $c)
                                                    <option value="{{ $c->id }}"
                                                        {{ $address->country_id == $c->id ? 'selected' : '' }}>
                                                        {{ $c->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- State --}}
                                        <div class="col-lg-4 mb-20">
                                            <label>State</label>
                                            <select name="addresses[{{ $index }}][state_id]"
                                                class="form-control state-dd" data-index="{{ $index }}"
                                                data-selected="{{ $address->state_id }}">
                                                <option value="">Select State</option>
                                            </select>
                                        </div>

                                        {{-- City --}}
                                        <div class="col-lg-4 mb-20">
                                            <label>City</label>
                                            <select name="addresses[{{ $index }}][city_id]"
                                                class="form-control city-dd" data-index="{{ $index }}"
                                                data-selected="{{ $address->city_id }}">
                                                <option value="">Select City</option>
                                            </select>
                                        </div>

                                        {{-- Address Lines --}}
                                        <div class="col-lg-12 mb-20">
                                            <label>Address Line 1</label>
                                            <textarea name="addresses[{{ $index }}][address_line1]" class="form-control" required>{{ $address->address_line1 }}</textarea>
                                        </div>

                                        <div class="col-lg-12 mb-20">
                                            <label>Address Line 2</label>
                                            <textarea name="addresses[{{ $index }}][address_line2]" class="form-control">{{ $address->address_line2 }}</textarea>
                                        </div>

                                        <div class="col-lg-4 mb-20">
                                            <label>Pincode</label>
                                            <input type="text" name="addresses[{{ $index }}][pincode]" maxlength="6"
                                                class="form-control" value="{{ $address->pincode }}">
                                        </div>

                                    </div>
                                @endforeach

                            </div>

                            {{-- Submit --}}
                            <div class="col-lg-12 mt-3">
                                <button type="submit" class="btn btn-primary text-white">Update Customer</button>
                                <a href="{{ route('customers.index') }}" class="btn btn-danger text-white">Cancel</a>
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
        $(document).ready(function() {

            /** GST Show / Hide */
            $('#gst_type').on('change', function() {
                let val = $(this).val();
                if (val === 'Regular' || val === 'Composition') {
                    $('.gst').slideDown();
                    $('.place').slideDown();
                } else {
                    $('.gst').slideUp();
                    $('.place').slideUp();
                }
            }).trigger('change');

            /** Blocked Reason Show / Hide */
            $('#is_blocked').on('change', function() {
                if ($(this).val() == "1") $('.blocked_reason_box').slideDown();
                else $('.blocked_reason_box').slideUp();
            }).trigger('change');

            /** Load Country → State → City */
            let oldCountry = "{{ old('country_id', $customer->getCustomerAddress?->country_id) }}";
            let oldState = "{{ old('state_id', $customer->getCustomerAddress?->state_id) }}";
            let oldCity = "{{ old('city_id', $customer->getCustomerAddress?->city_id) }}";

            if (oldCountry) {
                loadStates(oldCountry, oldState);
                if (oldState) loadCities(oldState, oldCity);
            }

            $('#country').change(function() {
                $('#state').html('<option>Select State</option>');
                loadStates($(this).val());
            });

            $('#state').change(function() {
                $('#city').html('<option>Select City</option>');
                loadCities($(this).val());
            });

            function loadStates(country, selected = null) {
                $.get('/get-states/' + country, function(data) {
                    $.each(data, function(i, val) {
                        $('#state').append(
                            `<option value="${val.id}" ${selected == val.id ? "selected" : ""}>${val.name}</option>`
                            );
                    });
                });
            }

            function loadCities(state, selected = null) {
                $.get('/get-cities/' + state, function(data) {
                    $.each(data, function(i, val) {
                        $('#city').append(
                            `<option value="${val.id}" ${selected == val.id ? "selected" : ""}>${val.name}</option>`
                            );
                    });
                });
            }

        });

        $(document).ready(function() {

            // Auto load State & City on edit
            $('.country-dd').each(function() {
                let countryId = $(this).val();
                if (countryId) {
                    $(this).trigger('change');
                }
            });
        });

        // Country → State
        $(document).on('change', '.country-dd', function() {
            let index = $(this).data('index');
            let countryId = $(this).val();

            let stateSelect = $(`.state-dd[data-index="${index}"]`);
            let citySelect = $(`.city-dd[data-index="${index}"]`);

            stateSelect.html('<option value="">Select State</option>');
            citySelect.html('<option value="">Select City</option>');

            if (!countryId) return;

            $.get('/get-states/' + countryId, function(states) {
                $.each(states, function(_, state) {
                    stateSelect.append(
                        `<option value="${state.id}">${state.name}</option>`
                    );
                });

                let selectedState = stateSelect.data('selected');
                if (selectedState) {
                    stateSelect.val(selectedState).trigger('change');
                }
            });
        });

        // State → City
        $(document).on('change', '.state-dd', function() {
            let index = $(this).data('index');
            let stateId = $(this).val();

            let citySelect = $(`.city-dd[data-index="${index}"]`);
            citySelect.html('<option value="">Select City</option>');

            if (!stateId) return;

            $.get('/get-cities/' + stateId, function(cities) {
                $.each(cities, function(_, city) {
                    citySelect.append(
                        `<option value="${city.id}">${city.name}</option>`
                    );
                });

                let selectedCity = citySelect.data('selected');
                if (selectedCity) {
                    citySelect.val(selectedCity);
                }
            });
        });
    </script>
@endpush
