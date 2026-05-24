@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Warehouse</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('warehouse.index') }}" class="text-decoration-none">Warehouse List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Warehouse</li>
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

        <form action="{{ route('warehouse.update', $warehouse->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Warehouse Information</h3>
                        <div class="row g-3">

                            {{-- Branch Code --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Warehouse Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" value="{{ old('code', $warehouse->code) }}"
                                    class="form-control" required>
                            </div>

                            {{-- Branch Name --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Warehouse Name <span class="text-danger">*</span></label>
                                <input type="text" name="warehouse_name"
                                    value="{{ old('warehouse_name', $warehouse->warehouse_name) }}" class="form-control"
                                    required>
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Contact Person <span class="text-danger">*</span></label>
                                <input type="text" name="contact_person"
                                    value="{{ old('contact_person', $warehouse->contact_person) }}" class="form-control"
                                    required placeholder = "Please Enter Contact Person name">
                            </div>

                            {{-- Branch Type --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Warehouse Purpose <span class="text-danger">*</span></label>
                                <select name="warehouse_purpose" class="form-select form-control" required>
                                    <option value="">Select Type</option>
                                    @foreach (['GeneralStorage', 'Quarantine', 'Testing', 'Dispatch', 'ColdStorage', 'Returns', 'Sampling'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('warehouse_purpose', $warehouse->warehouse_purpose) == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Material Type <span class="text-danger">*</span></label>
                                <select name="material_type" class="form-select form-control" required>
                                    <option value="">Select Type</option>
                                    @foreach (['RawMaterial', 'PackagingMaterial', 'FinishedGood', 'SemiFinishedGood', 'All'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('material_type', $warehouse->material_type) == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Temperature Control</label>
                                <select name="temperature_controlled" id="temperature_controlled"
                                    class="form-select form-control">
                                    <option value="Yes"
                                        {{ old('temperature_controlled', $warehouse->temperature_controlled ?? 'Yes') == 'Yes' ? 'selected' : '' }}>
                                        Yes</option>
                                    <option value="No"
                                        {{ old('temperature_controlled', $warehouse->temperature_controlled ?? 'Yes') == 'No' ? 'selected' : '' }}>
                                        No</option>
                                </select>
                            </div>

                            <div class="col-lg-6 mb-20 temperature-fields">
                                <label class="label fs-16 mb-2">Minimum Temperature</label>
                                <input type="number" name="temperature_range_min" id="temperature_range_min"
                                    value="{{ old('temperature_range_min', $warehouse->temperature_range_min ?? '') }}"
                                    class="form-control" step="0.01" min="0">
                            </div>

                            <div class="col-lg-6 mb-20 temperature-fields">
                                <label class="label fs-16 mb-2">Maximum Temperature</label>
                                <input type="number" name="temperature_range_max" id="temperature_range_max"
                                    value="{{ old('temperature_range_max', $warehouse->temperature_range_max ?? '') }}"
                                    class="form-control" step="0.01" min="0">
                            </div>

                          



                            {{-- Status --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Branch</label>
                                <select name="branch_id" class="form-select form-control">
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id', isset($warehouse) ? $warehouse->branch_id : '') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->branch_name }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            {{-- Address --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Address (Street/Area)</label>
                                <input type="text" name="address_line1"
                                    value="{{ old('address_line1', $warehouse->address_line1) }}" class="form-control">
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Landmark (optional)</label>
                                <input type="text" name="address_line2"
                                    value="{{ old('address_line2', $warehouse->address_line2) }}" class="form-control">
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Pin Code</label> 
                                <input type="text" name="pincode" value="{{ old('pincode', $warehouse->pincode) }}" 
                                    class="form-control" pattern="\d{6}" maxlength="6"
                                    minlength="6" title="Please enter a valid 6-digit pincode" required
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)"> 
                            </div>

                            {{-- Country / State --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Country<span class="text-danger">*</label>
                                <select name="country_id" id="country_id" class="form-select form-control"required>
                                    <option value=""> Select Country </option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" data-phonecode="{{ $country->phonecode }}"
                                            {{ old('country_id', $warehouse->country_id) == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label for="StateID" class="label fs-16 mb-2">State <span
                                        class="text-danger">*</span></label>
                                <select name="state_id" id="state_id" class="form-select form-control" required>
                                    <option value=""> Select State </option>
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}"
                                            {{ $warehouse->state_id == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label for="city_id" class="label fs-16 mb-2">City <span
                                        class="text-danger">*</span></label>
                                <select name="city_id" id="city_id" class="form-select form-control" required>
                                    <option value="">Select City </option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}"
                                            {{ $warehouse->city_id == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            {{-- Mobile / Landline --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Contact Number</label>
                                <input type="number" name="contact_number"  maxlength="10"
                                    value="{{ old('phone', $warehouse->contact_number) }}" class="form-control"  placeholder="e.g. 9876543210" maxlength="10"
                                    pattern="[0-9]{10}" title="Please enter valid 10 digit mobile number" required
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,10)"
                                     >
                            </div>

                            {{-- <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Landline</label>
                                <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}"
                                    class="form-control">
                            </div> --}}

                            {{-- Email / GST --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', $warehouse->email) }}"
                                    class="form-control">
                            </div>
                              <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Status <span class="text-danger">*</span></label>
                                <select name="is_active" class="form-select form-control" required>
                                    <option value="1"
                                        {{ old('is_active', $warehouse->is_active) == 1 ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0"
                                        {{ old('is_active', $warehouse->is_active) == 0 ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            {{-- Actions --}}
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-normal text-white">Update
                                        Warehouse</button>
                                    <a href="{{ route('warehouse.index') }}"
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

            var selectedCountry = $('#country_id').val();
            var selectedState = "{{ old('state_id', $warehouse->state_id) }}";
            var selectedCity = "{{ old('city_id', $warehouse->city_id) }}";

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

                $('#mobile').val(phonecode ? '+' + phonecode : '');

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

            function toggleTemperatureFields() {
                if ($('#temperature_controlled').val() === 'Yes') {
                    $('.temperature-fields').show();
                } else {
                    $('.temperature-fields').hide();
                    // Optional: hide hone par value clear karna
                    $('#temperature_range_min, #temperature_range_max').val('');
                }
            }

            // Initial check on page load
            toggleTemperatureFields();

            // On change of select
            $('#temperature_controlled').change(function() {
                toggleTemperatureFields();
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            const recordId = "{{ encrypt($warehouse->id) }}";

            setupRealtimeValidation('Warehouse', 'code', 'input[name="code"]', recordId);

        });
    </script>
@endpush
