@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Vendor</h3>

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
                        <span class="text-secondary">Edit Vendor</span>
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
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('vendor.update', $vendor->id) }}" enctype="multipart/form-data"
            class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Vendor Information</h3>
                        <div class="row">

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2"> Registered Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name"
                                    value="{{ old('name', $vendor->name) }}" required>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Vendor Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="code" id="code"
                                    value="{{ old('code', $vendor->code) }}" required>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email', $vendor->email) }}" required>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone" maxlength="10"
                                    value="{{ old('phone', $vendor->phone) }}" required paceholder="e.g. 9876543210"
                                    maxlength="10" pattern="[0-9]{10}" title="Please enter valid 10 digit mobile number"
                                    required oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,10)">
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Vendor Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="vendor_type" required>
                                    @foreach (['rawmaterial', 'packaging', 'service', 'transport', 'import', 'other'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('vendor_type', $vendor->vendor_type) == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Contact Person</label>
                                <input type="text" class="form-control" name="contact_person"
                                    value="{{ old('contact_person', $vendor->contact_person) }}">
                            </div>

                            <div class="col-lg-4 mb-20" id="gst_registration_div">
                                <label class="label fs-16 mb-2">GST Registered <span class="text-danger">*</span></label>
                                <select class="form-control" name="is_gst_registered" required>
                                    <option value="1"
                                        {{ old('is_gst_registered', $vendor->is_gst_registered) == 1 ? 'selected' : '' }}>
                                        Yes
                                    </option>
                                    <option value="0"
                                        {{ old('is_gst_registered', $vendor->is_gst_registered) == 0 ? 'selected' : '' }}>
                                        No
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-20" id="gst_no_div">
                                <label class="label fs-16 mb-2">GST No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="gst_no" id="gst_no"
                                    value="{{ old('gst_no', $vendor->gst_no) }}">
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Status</label>
                                <select class="form-control" name="status">
                                    <option value="active" {{ $vendor->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $vendor->status == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            {{-- Country / State / City --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Country</label>
                                <select name="country_id" id="country" class="form-control">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('country_id', $vendor->country_id) == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">State</label>
                                <select name="state_id" id="state" class="form-control"></select>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">City</label>
                                <select name="city_id" id="city" class="form-control"></select>
                            </div>

                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">Address Line 1 <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="address_line1" required>{{ old('address_line1', $vendor->address_line1) }}</textarea>
                            </div>

                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">Address Line 2</label>
                                <textarea class="form-control" name="address_line2">{{ old('address_line2', $vendor->address_line2) }}</textarea>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Pincode<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pincode" 
                                    value="{{ old('pincode', $vendor->pincode) }}" required>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Payment Term <span class="text-danger">*</span></label>
                                <select class="form-control" name="payment_term_id" required>
                                    @foreach ($paymentTerms as $term)
                                        <option value="{{ $term->id }}"
                                            {{ old('payment_term_id', $vendor->payment_term_id) == $term->id ? 'selected' : '' }}>
                                            {{ $term->days }} {{ $term->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">PAN No</label>
                                <input type="text" class="form-control" name="pan_no"
                                    value="{{ old('pan_no', $vendor->pan_no) }}">
                            </div>

                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">Remarks</label>
                                <textarea class="form-control" name="remarks">{{ old('remarks', $vendor->remarks) }}</textarea>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <button type="submit" class="btn btn-primary text-white">Update Vendor</button>
                                <a href="{{ route('vendor.index') }}" class="btn btn-danger text-white">Cancel</a>
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
            function loadStates(countryID, selectedState = null) {
                if (countryID) {
                    $.getJSON('/get-states/' + countryID, function(data) {
                        $('#state').empty().append('<option value="">Select State</option>');
                        $('#city').empty().append('<option value="">Select City</option>');
                        $.each(data, function(k, v) {
                            $('#state').append('<option value="' + v.id + '" ' + (selectedState == v
                                .id ? 'selected' : '') + '>' + v.name + '</option>');
                        });
                    });
                }
            }

            function loadCities(stateID, selectedCity = null) {
                if (stateID) {
                    $.getJSON('/get-cities/' + stateID, function(data) {
                        $('#city').empty().append('<option value="">Select City</option>');
                        $.each(data, function(k, v) {
                            $('#city').append('<option value="' + v.id + '" ' + (selectedCity == v
                                .id ? 'selected' : '') + '>' + v.name + '</option>');
                        });
                    });
                }
            }

            var selectedCountry = '{{ old('country_id', $vendor->country_id) }}';
            var selectedState = '{{ old('state_id', $vendor->state_id) }}';
            var selectedCity = '{{ old('city_id', $vendor->city_id) }}';

            loadStates(selectedCountry, selectedState);
            loadCities(selectedState, selectedCity);

            $('#country').change(function() {
                var countryID = $(this).val();
                loadStates(countryID);
            });
            $('#state').change(function() {
                var stateID = $(this).val();
                loadCities(stateID);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
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
    </script>

    <script>
        $(document).ready(function() {
            function toggleGstNoField() {
                var isGstRegistered = $('select[name="is_gst_registered"]').val();
                if (isGstRegistered == '1') {
                    $('#gst_no').prop('required', true);
                    $('#gst_no_div').show();
                } else {
                    $('#gst_no').prop('required', false);
                    $('#gst_no_div').hide();
                }
            }

            // Initial check
            toggleGstNoField();

            // On change
            $('select[name="is_gst_registered"]').change(function() {
                toggleGstNoField();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            const recordId = "{{ encrypt($vendor->id) }}";

            setupRealtimeValidation('Vendor', 'code', 'input[name="code"]', recordId);

        });
    </script>
@endpush
