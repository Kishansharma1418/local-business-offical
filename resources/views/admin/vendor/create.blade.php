@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Add Vendor</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('vendor.index') }}" class="d-flex align-items-center text-decoration-none">
                            <span class="text-body fs-14 hover">Vendor List</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="text-secondary">Add Vendor</span>
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

        <form method="POST" action="{{ route('vendor.store') }}" enctype="multipart/form-data" class="needs-validation"
            novalidate>
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Vendor Information</h3>
                        <div class="row">


                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2"> Registered Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Vendor Code<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="code" value="{{ old('code') }}"
                                    id="code" required>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                    required>
                            </div>


                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                    maxlength="10" required placeholder="e.g. 9876543210" maxlength="10" pattern="[0-9]{10}"
                                    title="Please enter valid 10 digit mobile number" required
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,10)">
                            </div>


                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Vendor Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="vendor_type" required>
                                    <option value="">Select</option>
                                    <option value="rawmaterial"
                                        {{ old('vendor_type') == 'rawmaterial' ? 'selected' : '' }}>Raw Material</option>
                                    <option value="packaging" {{ old('vendor_type') == 'packaging' ? 'selected' : '' }}>
                                        Packaging</option>
                                    <option value="service" {{ old('vendor_type') == 'service' ? 'selected' : '' }}>Service
                                    </option>
                                    <option value="transport" {{ old('vendor_type') == 'transport' ? 'selected' : '' }}>
                                        Transport</option>
                                    <option value="import" {{ old('vendor_type') == 'import' ? 'selected' : '' }}>Import
                                    </option>
                                    <option value="other" {{ old('vendor_type') == 'other' ? 'selected' : '' }}>Other
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Contact Person</label>
                                <input type="text" class="form-control" name="contact_person">
                            </div>

                            <div class="col-lg-4 mb-20" id="gst_registration_div">
                                <label class="label fs-16 mb-2">GST Registered <span class="text-danger">*</span></label>
                                <select class="form-control" name="is_gst_registered" required>
                                    <option value="">Select</option>
                                    <option value="1" {{ old('is_gst_registered') == '1' ? 'selected' : '' }}>Yes
                                    </option>
                                    <option value="0" {{ old('is_gst_registered') == '0' ? 'selected' : '' }}>No
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-20" id="gst_no_div">
                                <label class="label fs-16 mb-2">GST No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="gst_no" id="gst_no"
                                    value="{{ old('gst_no') }}" maxlength="15"
                                    pattern="[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}"
                                    title="Please enter valid GST No (e.g. 22AAAAA0000A1Z5)"
                                    oninput="this.value = this.value.toUpperCase();">
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Status</label>
                                <select class="form-control" name="status">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>


                            {{-- Country / State / City --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Country  <span class="text-danger">*</span></label>
                                <select name="country_id" id="country" class="form-control" required>
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('country_id', $selectedCountryId ?? '') == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}  
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">State <span class="text-danger">*</span></label>
                                <select name="state_id" id="state" class="form-control" required>
                                    <option value="">Select State</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">City <span class="text-danger">*</span></label>
                                <select name="city_id" id="city" class="form-control" required>
                                    <option value="">Select City</option>
                                </select>
                            </div>

                            {{-- Address --}}
                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">Address Line 1<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="address_line1" placeholder="Residential Address" required>{{ old('address_line1') }}</textarea>
                            </div>

                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">Address Line 2</label>
                                <textarea class="form-control" name="address_line2">{{ old('address_line2') }}</textarea>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Pincode <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pincode" value="{{ old('pincode') }}"
                                    maxlength="6" pattern="^[1-9][0-9]{5}$"
                                    title="Please enter a valid 6-digit Pincode (e.g. 110001)"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);" required>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Payment Term <span class="text-danger">*</span></label>
                                <select class="form-control" name="payment_term_id" required>
                                    <option value="">Select Payment Term</option>
                                    @foreach ($paymentTerms as $term)
                                        <option value="{{ $term->id }}"
                                            {{ old('payment_term_id') == $term->id ? 'selected' : '' }}>
                                            {{ $term->days }} {{ $term->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">PAN No</label>
                                <input type="text" class="form-control" name="pan_no" value="{{ old('pan_no') }}"
                                    maxlength="10" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" placeholder="e.g. ABCDE1234F"
                                    oninput="this.value = this.value.toUpperCase();">
                            </div>

                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">Remarks</label>
                                <textarea class="form-control" name="remarks"></textarea>
                            </div>


                            {{-- Submit --}}
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-normal text-white">+ Add
                                        Vendor</button>
                                    <a href="{{ route('vendor.index') }}"
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
        $(document).ready(function() {
            const oldCountry = "{{ old('country_id', $selectedCountryId ?? '') }}";
            const oldState = "{{ old('state_id', $selectedStateId ?? '') }}";
            const oldCity = "{{ old('city_id', $selectedCityId ?? '') }}";

            $('#country').on('change', function() {
                var countryID = $(this).val();
                $('#state').empty().append('<option value="">Select State</option>');
                $('#city').empty().append('<option value="">Select City</option>');

                if (countryID) {
                    $.ajax({
                        url: '/get-states/' + countryID,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#state').append('<option value="' + value.id + '"' +
                                    (value.id == oldState ? ' selected' : '') +
                                    '>' + value.name + '</option>');
                            });

                            if (oldState) $('#state').trigger('change');
                        }
                    });
                }
            });

            $('#state').on('change', function() {
                var stateID = $(this).val();
                $('#city').empty().append('<option value="">Select City</option>');

                if (stateID) {
                    $.ajax({
                        url: '/get-cities/' + stateID,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $.each(data, function(key, value) {
                                $('#city').append('<option value="' + value.id + '"' +
                                    (value.id == oldCity ? ' selected' : '') + '>' +
                                    value.name + '</option>');
                            });
                        }
                    });
                }
            })
            if (oldCountry) {
                $('#country').val(oldCountry).trigger('change');
            }

            $('form').on('submit', function(e) {
                let form = $(this)[0];


                if (oldCountry) {
                    $('#country').val(oldCountry).trigger('change');
                }


                $('form').on('submit', function(e) {
                    let form = $(this)[0];

                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                        $(this).addClass('was-validated');
                        return false;
                    }

                    $(this).find('button[type="submit"]')
                        .prop('disabled', true)
                        .text('Processing...');
                });
            });
        })
    </script>

    <script>
        $(document).ready(function() {

            $('#gst_no_div').hide();
            $('#gst_registration_div select').on('change', function() {
                var isGstRegistered = $(this).val();
                if (isGstRegistered === '1') {
                    $('#gst_no_div').show();
                    $('#gst_no').attr('required', true);
                } else {
                    $('#gst_no_div').hide();
                    $('#gst_no').attr('required', false);
                    $('#gst_no').val('');
                }
            });
            // GST No to uppercase
            $('#gst_no').on('input', function() {
                this.value = this.value.toUpperCase();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            setupRealtimeValidation('Vendor', 'code', '#code');
            setupRealtimeValidation('Vendor', 'email', 'input[name="email"]', );
        });
    </script>
@endpush
