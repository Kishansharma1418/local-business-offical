@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Branch</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('branches.index') }}" class="text-decoration-none">Branch List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Branch</li>
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

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('branches.update', $branch->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Branch Information</h3>
                        <div class="row g-3">

                            {{-- Branch Code --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Branch Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" value="{{ old('code', $branch->code) }}"
                                    class="form-control" required>
                            </div>

                            {{-- Branch Name --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Branch Name <span class="text-danger">*</span></label>
                                <input type="text" name="branch_name"
                                    value="{{ old('branch_name', $branch->branch_name) }}" class="form-control" required>
                            </div>

                            {{-- Branch Type --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Branch Type <span class="text-danger">*</span></label>
                                <select name="branch_type" class="form-select form-control" required>
                                    @foreach (['Head Office', 'Regional Office', 'Warehouse', 'Factory', 'Export Division'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('branch_type', $branch->branch_type) == $type ? 'selected' : '' }}>
                                            {{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Status</label>
                                <select name="status" class="form-select form-control">
                                    <option value="Active"
                                        {{ old('status', $branch->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive"
                                        {{ old('status', $branch->status) == 'Inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            {{-- Address --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Address (Street/Area)</label>
                                <input type="text" name="address_line1"
                                    value="{{ old('address_line1', $branch->address_line1) }}" class="form-control">
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Landmark (optional)</label>
                                <input type="text" name="address_line2"
                                    value="{{ old('address_line2', $branch->address_line2) }}" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Pincode</label>
                                <input type="number" name="pin_code" value="{{ old('pin_code', $branch->pin_code) }}"
                                    class="form-control" maxlength="6" class="form-control" pattern="\d{6}" maxlength="6"
                                    minlength="6" title="Please enter a valid 6-digit pincode" required
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)">
                            </div>

                            {{-- Country / State --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Country</label>
                                <select name="country_id" id="country_id" class="form-select form-control">
                                    <option value="">-- Select Country --</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" data-phonecode="{{ $country->phonecode }}"
                                            {{ old('country_id', $branch->country_id) == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label for="StateID" class="label fs-16 mb-2">State <span
                                        class="text-danger">*</span></label>
                                <select name="state_id" id="state_id" class="form-select form-control" required>
                                    <option value="">-- Select State --</option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}"
                                            {{ $branch->state_id == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label for="city_id" class="label fs-16 mb-2">City <span
                                        class="text-danger">*</span></label>
                                <select name="city_id" id="city_id" class="form-select form-control" required>
                                    <option value="">-- Select City --</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ $branch->city_id == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            {{-- Mobile / Landline --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Mobile</label>
                                <input type="text" name="mobile" value="{{ old('mobile', $branch->mobile) }}"
                                    class="form-control" placeholder="e.g. 9876543210" maxlength="10"
                                    pattern="[0-9]{10}" title="Please enter valid 10 digit mobile number" required
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,10)">
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Landline</label>
                                <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}"
                                    class="form-control" placeholder="e.g. 9876543210" maxlength="10"
                                    pattern="[0-9]{10}" title="Please enter valid 10 digit mobile number"
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,10)">
                            </div>

                            {{-- Email / GST --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', $branch->email) }}"
                                    class="form-control">
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">GST Number</label>
                                <input type="text" name="gst_number"
                                    value="{{ old('gst_number', $branch->gst_number) }}" class="form-control"
                                    pattern="[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}"
                                    placeholder="e.g. 22AAAAA0000A1Z5" oninput="this.value = this.value.toUpperCase();"
                                    class="form-control">
                            </div>

                            {{-- PAN / Manager --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">PAN Number</label>
                                <input type="text" name="pan_number"
                                    value="{{ old('pan_number', $branch->pan_number) }}" class="form-control"
                                    maxlength="10" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" placeholder="e.g. ABCDE1234F"
                                    oninput="this.value = this.value.toUpperCase();">
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Policy No.</label>
                                <input type="text" name="policy_no"
                                    value="{{ old('policy_no', $branch->policy_no) }}" class="form-control">    
                            </div>

                             <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">DL No.</label>
                                <input type="text" name="dl_no"
                                    value="{{ old('dl_no', $branch->dl_no) }}" class="form-control">    
                            </div>

                             <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Cbn No.</label>
                                <input type="text" name="cbn_no"
                                    value="{{ old('cbn_no', $branch->cbn_no) }}" class="form-control">    
                            </div>
                            {{-- Notes full width --}}
                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">Notes</label>
                                <textarea name="notes" class="form-control" rows="2">{{ old('notes', $branch->notes) }}</textarea>
                            </div>

                            {{-- Actions --}}
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-normal text-white">Update
                                        Branch</button>
                                    <a href="{{ route('branches.index') }}"
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

            // Existing states & cities load on page load
            var selectedCountry = $('#country_id').val();
            var selectedState = "{{ old('state_id', $branch->state_id) }}";
            var selectedCity = "{{ old('city_id', $branch->city_id) }}";

            if (selectedCountry) {
                $.get('/get-states/' + selectedCountry, function(states) {
                    $('#state_id').html('<option value="">Select State</option>');
                    $.each(states, function(key, state) {
                        var selected = (state.id == selectedState) ? 'selected' : '';
                        $('#state_id').append('<option value="' + state.id + '" ' + selected + '>' +
                            state.name + '</option>');
                    });
                });
            }

            if (selectedState) {
                $.get('/get-cities/' + selectedState, function(cities) {
                    $('#city_id').html('<option value="">Select City</option>');
                    $.each(cities, function(key, city) {
                        var selected = (city.id == selectedCity) ? 'selected' : '';
                        $('#city_id').append('<option value="' + city.id + '" ' + selected + '>' +
                            city.name + '</option>');
                    });
                });
            }

            // Country change
            $('#country_id').change(function() {
                var country_id = $(this).val();
                var phonecode = $('#country_id option:selected').data('phonecode');

                // Reset mobile field with new country code
                $('#mobile').val(phonecode ? '+' + phonecode : '');

                // Load states for selected country
                if (country_id) {
                    $.get('/get-states/' + country_id, function(states) {
                        $('#state_id').html('<option value="">Select State</option>');
                        $.each(states, function(key, state) {
                            $('#state_id').append('<option value="' + state.id + '">' +
                                state
                                .name + '</option>');
                        });
                        $('#city_id').html('<option value="">Select City</option>'); // reset cities
                    });
                } else {
                    $('#state_id').html('<option value="">Select State</option>');
                    $('#sity_id').html('<option value="">Select City</option>');
                }
            });

            // State change
            $('#state_id').change(function() {
                var state_id = $(this).val();
                if (state_id) {
                    $.get('/get-cities/' + state_id, function(cities) {
                        $('#city_id').html('<option value="">Select City</option>');
                        $.each(cities, function(key, city) {
                            $('#city_id').append('<option value="' + city.id + '">' + city
                                .name + '</option>');
                        });
                    });
                } else {
                    $('#city_id').html('<option value="">Select City</option>');
                }
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            const recordId = "{{ encrypt($branch->id) }}";

            setupRealtimeValidation('Branch', 'code', 'input[name="code"]', recordId);

        });
    </script>
@endpush
