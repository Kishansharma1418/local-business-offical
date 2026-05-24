@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Broker</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('brokers.index') }}" class="text-decoration-none">Broker List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Broker</li>
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

        <form action="{{ route('brokers.update', encrypt($broker->id)) }}" method="POST" class="needs-validation"
            novalidate>
            @csrf
            @method('PUT')

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <h3 class="mb-20">Broker Information</h3>

                <div class="row">

                    {{-- Broker Code --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Broker Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $broker->code) }}" class="form-control"
                            required>
                    </div>

                    {{-- Broker Name --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Broker Name <span class="text-danger">*</span></label>
                        <input type="text" name="broker_name" value="{{ old('broker_name', $broker->broker_name) }}"
                            class="form-control" required>
                    </div>

                    {{-- Contact Person --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Contact Person</label>
                        <input type="text" name="contact_person"
                            value="{{ old('contact_person', $broker->contact_person) }}" class="form-control">
                    </div>

                    {{-- Mobile --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Mobile <span class="text-danger">*</span></label>
                        <input type="text" name="mobile_no" value="{{ old('mobile_no', $broker->mobile_no) }}"
                            class="form-control" required placeholder="e.g. 9876543210" maxlength="10" pattern="[0-9]{10}"
                            title="Please enter valid 10 digit mobile number" required
                            oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,10)">
                    </div>

                    {{-- Email --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $broker->email) }}" class="form-control"
                            required>
                    </div>

                    {{-- GST --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">GST Number</label>
                        <input type="text" name="gst_number" value="{{ old('gst_number', $broker->gst_number) }}"
                            class="form-control" placeholder="e.g. 22AAAAA0000A1Z5" maxlength="15"
                            pattern="[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}"
                            title="Please enter valid GST No (e.g. 22AAAAA0000A1Z5)"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>

                    {{-- PAN --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">PAN Number <span class="text-danger">*</span></label>
                        <input type="text" name="pan_number" value="{{ old('pan_number', $broker->pan_number) }}"
                            class="form-control" required placeholder="e.g. AAAAA0000A" maxlength="10"
                            pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="Please enter valid PAN No (e.g. AAAAA0000A)"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>

                    {{-- Address Line 1 --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Address Line 1 <span class="text-danger">*</span></label>
                        <input type="text" name="address_line1"
                            value="{{ old('address_line1', $broker->address_line1) }}" class="form-control" required>
                    </div>

                    {{-- Address Line 2 --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Address Line 2</label>
                        <input type="text" name="address_line2"
                            value="{{ old('address_line2', $broker->address_line2) }}" class="form-control">
                    </div>

                    {{-- Country --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Country <span class="text-danger">*</span></label>
                        <select name="country_id" id="country" class=" form-control" required>
                            <option value="">Select Country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('country_id', $broker->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- State --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">State <span class="text-danger">*</span></label>
                        <select name="state_id" id="state" class="form-control" required>
                            <option value="">Select State</option>
                        </select>
                    </div>

                    {{-- City --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">City <span class="text-danger">*</span></label>
                        <select name="city_id" id="city" class="form-control" required>
                            <option value="">Select City</option>
                        </select>
                    </div>

                    {{-- Pincode --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Pincode <span class="text-danger">*</span></label>
                        <input type="text" name="pincode" value="{{ old('pincode', $broker->pincode) }}"
                            class="form-control" required pattern="\d{6}" maxlength="6" minlength="6"
                                    title="Please enter a valid 6-digit pincode" required
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)">
                    </div>

                    {{-- Commission Type --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Commission Type <span class="text-danger">*</span></label>
                        <select name="commission_type" class="form-select form-control" required>
                            <option value="Percentage"
                                {{ old('commission_type', $broker->commission_type) == 'Percentage' ? 'selected' : '' }}>
                                Percentage
                            </option>
                            <option value="Fixed"
                                {{ old('commission_type', $broker->commission_type) == 'Fixed' ? 'selected' : '' }}>
                                Fixed
                            </option>
                        </select>
                    </div>

                    {{-- Commission Value --}}
                    {{-- Commission Value --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Commission Value <span class="text-danger">*</span></label>

                        <div class="position-relative">
                            <input type="number" step="0.01" min="0" name="commission_value"
                                id="commission_value" value="{{ old('commission_value', $broker->commission_value) }}"
                                class="form-control pe-5" required>

                            <span id="percentage_symbol"
                                style="
                                        position:absolute;
                                        right:12px;
                                        top:50%;
                                        transform:translateY(-50%);
                                        font-size:14px;
                                        color:#666;
                                        display:none;
                                    ">
                                %
                            </span>
                        </div>
                    </div>


                    {{-- Status --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Status</label>
                        <select name="status" class="form-select form-control">
                            <option value="Active" {{ old('status', $broker->status) == 'Active' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="Inactive" {{ old('status', $broker->status) == 'Inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                    {{-- Remarks --}}
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Remarks</label>
                        <input type="text" name="remarks" value="{{ old('remarks', $broker->remarks) }}"
                            class="form-control">
                    </div>

                    {{-- Actions --}}
                    <div class="col-lg-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary text-white">
                                Update Broker
                            </button>
                            <a href="{{ route('brokers.index') }}" class="btn btn-danger text-white">
                                Cancel
                            </a>
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

            let selectedState = "{{ old('state_id', $broker->state_id) }}";
            let selectedCity = "{{ old('city_id', $broker->city_id) }}";

            function loadStates(countryID) {
                $('#state').html('<option value="">Select State</option>');
                $('#city').html('<option value="">Select City</option>');

                if (countryID) {
                    $.get('/get-states/' + countryID, function(data) {
                        $.each(data, function(key, value) {
                            $('#state').append(
                                '<option value="' + value.id + '"' +
                                (value.id == selectedState ? ' selected' : '') +
                                '>' + value.name + '</option>'
                            );
                        });

                        if (selectedState) {
                            loadCities(selectedState);
                        }
                    });
                }
            }

            function loadCities(stateID) {
                $('#city').html('<option value="">Select City</option>');

                if (stateID) {
                    $.get('/get-cities/' + stateID, function(data) {
                        $.each(data, function(key, value) {
                            $('#city').append(
                                '<option value="' + value.id + '"' +
                                (value.id == selectedCity ? ' selected' : '') +
                                '>' + value.name + '</option>'
                            );
                        });
                    });
                }
            }

            $('#country').on('change', function() {
                selectedState = '';
                selectedCity = '';
                loadStates($(this).val());
            });

            $('#state').on('change', function() {
                selectedCity = '';
                loadCities($(this).val());
            });

            // Initial load
            loadStates($('#country').val());

        });
    </script>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {

            function handleCommissionType() {
                let type = $('select[name="commission_type"]').val();
                let $valueInput = $('#commission_value');
                let value = parseFloat($valueInput.val());

                if (type === 'Percentage') {
                    $('#percentage_symbol').show();

                    $valueInput.attr({
                        min: 0,
                        max: 100,
                        step: 0.01
                    });

                    // agar saved value > 100 ho (safety)
                    if (!isNaN(value) && value > 100) {
                        $valueInput.val(100);
                    }

                } else {
                    $('#percentage_symbol').hide();

                    $valueInput.attr({
                        min: 0,
                        step: 0.01
                    });
                    $valueInput.removeAttr('max');

                    // negative safety
                    if (!isNaN(value) && value < 0) {
                        $valueInput.val(0);
                    }
                }
            }

            // Commission type change
            $('select[name="commission_type"]').on('change', function() {
                handleCommissionType();
                $('#commission_value').val('');
            });

            // Input validation (real-time)
            $('#commission_value').on('input', function() {
                let value = parseFloat(this.value);
                let type = $('select[name="commission_type"]').val();

                if (isNaN(value)) return;

                // ❌ negative block (both)
                if (value < 0) {
                    this.value = 0;
                }

                // ❌ percentage > 100 block
                if (type === 'Percentage' && value > 100) {
                    this.value = 100;
                }
            });

            // ✅ Page load par already saved data ke hisaab se set
            handleCommissionType();

        });
    </script>
@endpush
