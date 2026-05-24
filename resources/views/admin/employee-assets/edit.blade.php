@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Employee Expenses</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('employee-expense.index') }}" class="text-decoration-none">Expenses List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Expenses</li>
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

        <form action="{{ route('employee-expense.update', $employeeAsset->id) }}" method="POST"class="needs-validation"
            novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Employee Expenses Information</h3>

                        <div class="row">

                            {{-- Type --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Expenses Type <span class="text-danger">*</span></label>
                                <select name="type" id="asset_type" class="form-select form-control" required>
                                    <option value="">Select Type</option>
                                    @foreach (['traveling', 'hotel', 'telephone', 'postage', 'printing & stationary', 'advertisement'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('type', $employeeAsset->type) == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Country<span class="text-danger">*</label>
                                <select name="country_id" id="country_id" class="form-select form-control"required>
                                    <option value=""> Select Country </option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" data-phonecode="{{ $country->phonecode }}"
                                            {{ old('country_id', $employeeAsset->country_id) == $country->id ? 'selected' : '' }}>
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
                                            {{ $employeeAsset->state_id == $state->id ? 'selected' : '' }}>
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
                                            {{ $employeeAsset->city_id == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>



                            {{-- Amount --}}
                            <div class="col-lg-6 mb-20 amount-field">
                                <label class="label fs-16 mb-2">Amount</label>
                                <input type="number" step="0.01" name="amount" id="amount"
                                    value="{{ old('amount', $employeeAsset->amount) }}" class="form-control">
                            </div>

                            {{-- Traveling Fields --}}
                            <div class="traveling-fields row" style="display:none;">
                                <div class="col-lg-6 mb-20">
                                    <label class="label fs-16 mb-2">HQ Allow</label>
                                    <input type="number" step="0.01" name="hq_allow" id="hq_allow"
                                        value="{{ old('hq_allow', $employeeAsset->hq_allow) }}" class="form-control">
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="label fs-16 mb-2">Ex Stn Allow</label>
                                    <input type="number" step="0.01" name="ex_stn_allow" id="ex_stn_allow"
                                        value="{{ old('ex_stn_allow', $employeeAsset->ex_stn_allow) }}"
                                        class="form-control">
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="label fs-16 mb-2">Out Stn Allow</label>
                                    <input type="number" step="0.01" name="out_stn_allow" id="out_stn_allow"
                                        value="{{ old('out_stn_allow', $employeeAsset->out_stn_allow) }}"
                                        class="form-control">
                                </div>

                                <div class="col-lg-6 mb-20">
                                    <label class="label fs-16 mb-2">Rly/Bus Ticket Amount </label>
                                    <input type="number" step="0.01" name="bus_ticket_amount" id="bus_ticket_amount"
                                        value="{{ old('bus_ticket_amount', $employeeAsset->bus_ticket_amount) }}"
                                        class="form-control">
                                </div>
                            </div>

                            {{-- Total --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Total Amount</label>
                                <input type="number" step="0.01" name="total_amount" id="total_amount"
                                    value="{{ old('total_amount', $employeeAsset->total_amount) }}" class="form-control"
                                    readonly>
                            </div>


                            {{-- Buttons --}}
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary text-white">Update Expenses</button>
                                    <a href="{{ route('employee-expense.index') }}"
                                        class="btn btn-danger text-white">Cancel</a>
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

            // Flag to detect initial load
            let isInitialLoad = true;

            // Toggle Traveling and Amount fields
            function toggleFields(resetFields = false) {
                const selectedType = $('#asset_type').val();

                if (selectedType === 'traveling') {
                    $('.traveling-fields').slideDown(200);
                    $('.amount-field').slideUp(200);
                } else {
                    $('.traveling-fields').slideUp(200);
                    $('.amount-field').slideDown(200);
                }

                // ✅ Reset values only if triggered by user (not on first load)
                if (resetFields) {
                    $('#hq_allow').val('');
                    $('#ex_stn_allow').val('');
                    $('#out_stn_allow').val('');
                    $('#bus_ticket_amount').val('');
                    $('#amount').val('');
                    $('#total_amount').val('0.00');
                }

                calculateTotal();
            }

            // Auto calculate total
            function calculateTotal() {
                let type = $('#asset_type').val();
                let total = 0;

                if (type === 'traveling') {
                    let hq = parseFloat($('#hq_allow').val()) || 0;
                    let ex = parseFloat($('#ex_stn_allow').val()) || 0;
                    let out = parseFloat($('#out_stn_allow').val()) || 0;
                    let bus = parseFloat($('#bus_ticket_amount').val()) || 0;
                    total = hq + ex + out + bus;
                } else {
                    let amount = parseFloat($('#amount').val()) || 0;
                    total = amount;
                }

                $('#total_amount').val(total.toFixed(2));
            }

            // ✅ Event Listeners
            $('#asset_type').on('change', function() {
                // reset only when user changes the type manually
                toggleFields(true);
            });

            $('#hq_allow, #ex_stn_allow, #out_stn_allow, #bus_ticket_amount, #amount').on('input', calculateTotal);

            // Initial setup (no reset on load)
            toggleFields(false);
            calculateTotal();
        });
    </script>



    <script>
        $(document).ready(function() {

            var selectedCountry = $('#country_id').val();
            var selectedState = "{{ old('state_id', $employeeAsset->state_id) }}";
            var selectedCity = "{{ old('city_id', $employeeAsset->city_id) }}";

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
@endpush
