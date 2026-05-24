@extends('include.master')
@section('content')
<div class="main-content-container overflow-hidden">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Add Branch</h3>

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
                <li class="breadcrumb-item active">Add Branch</li>
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

    <form action="{{ route('branches.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                    <h3 class="mb-20">Branch Information</h3>
                    <div class="row">

                        {{-- Branch Code --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" value="{{ old('code') }}"
                                class="form-control" placeholder="E.g. BR001" required>
                        </div>

                        {{-- Branch Name --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" name="branch_name" value="{{ old('branch_name') }}"
                                class="form-control" placeholder="E.g. Head Office" required>
                        </div>

                        {{-- Branch Type --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">Branch Type <span class="text-danger">*</span></label>
                            <select name="branch_type" class="form-select form-control" required>
                                <option value="">Select Type</option>
                                @foreach (['Head Office', 'Regional Office', 'Warehouse', 'Factory', 'Export Division'] as $type)
                                <option value="{{ $type }}"
                                    {{ old('branch_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select form-control" required>
                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        {{-- Address Line1 --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">Address (Street/Area) <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="address_line1" value="{{ old('address_line1') }}"
                                class="form-control" required>
                        </div>

                        {{-- Address Line2 --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">Landmark</label>
                            <input type="text" name="address_line2" value="{{ old('address_line2') }}"
                                class="form-control">
                        </div>

                        {{-- Country --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">Country <span class="text-danger">*</span></label>
                            <select name="country_id" id="country_id" class="form-select form-control" required>
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                <option value="{{ $country->id }}" data-phonecode="{{ $country->phonecode }}"
                                    {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- State --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">State <span class="text-danger">*</span></label>
                            <select name="state_id" id="state_id" class="form-select form-control" required>
                                <option value="">Select State</option>
                            </select>
                        </div>

                        {{-- City --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">City <span class="text-danger">*</span></label>
                            <select name="city_id" id="city_id" class="form-select form-control" required>
                                <option value="">Select City</option>
                            </select>
                        </div>

                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">PinCode <span class="text-danger">*</span></label>
                            <input type="text" name="pin_code" id="pin_code" value="{{ old('pin_code') }}"
                                class="form-control" pattern="\d{6}" maxlength="6" minlength="6"
                                title="Please enter a valid 6-digit pincode" required
                                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)">
                        </div>

                        {{-- Mobile --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">Mobile <span class="text-danger">*</span></label>
                            <input type="text" name="mobile" id="mobile" value="{{ old('mobile') }}"
                                class="form-control" required placeholder="e.g. 9876543210" maxlength="10"
                                pattern="[0-9]{10}" title="Please enter valid 10 digit mobile number" required
                                oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,10)">
                        </div>

                        {{-- Phone --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">Landline</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control"
                                placeholder="e.g. 9876543210" maxlength="10" pattern="[0-9]{10}"
                                title="Please enter valid 10 digit mobile number"
                                oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,10)">
                        </div>

                        {{-- Email --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                        </div>

                        {{-- GST Number --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">GST Number </label>
                            <input type="text" name="gst_number" value="{{ old('gst_number') }}" maxlength="15"
                                pattern="[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}"
                                placeholder="e.g. 22AAAAA0000A1Z5" oninput="this.value = this.value.toUpperCase();"
                                class="form-control">
                        </div>

                        {{-- PAN Number --}}
                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">PAN Number</label>
                            <input type="text" name="pan_number" value="{{ old('pan_number') }}"
                                class="form-control" maxlength="10" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}"
                                placeholder="e.g. ABCDE1234F" oninput="this.value = this.value.toUpperCase();">
                        </div>


                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">Policy Number</label>
                            <input type="text" name="policy_no" value="{{ old('policy_no') }}"
                                class="form-control" maxlength="50" placeholder="e.g. POL-001">
                        </div>

                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">DL Number</label>
                            <input type="text" name="dl_no" value="{{ old('dl_no') }}"
                                class="form-control" maxlength="50" placeholder="e.g. DL-001">
                        </div>

                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">CBN Number</label>
                            <input type="text" name="cbn_no" value="{{ old('cbn_no') }}"
                                class="form-control" maxlength="50" placeholder="e.g. CBN-001">
                        </div>
                        {{-- Notes --}}
                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>

                        {{-- Actions --}}
                        <div class="col-lg-12 mt-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary fw-normal text-white">+ Add
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

        // Country change hone par states load karega
        $('#country_id').change(function() {
            var country_id = $(this).val();
            var phonecode = $('#country_id option:selected').data('phonecode');

            // Phonecode set kare
            if (phonecode) {
                $('#mobile').val('+' + phonecode);
            } else {
                $('#mobile').val('');
            }

            // Reset state & city
            $('#state_id').html('<option value="">Select State</option>');
            $('#city_id').html('<option value="">Select City</option>');

            if (country_id) {
                $.get('/get-states/' + country_id, function(states) {
                    $.each(states, function(key, state) {
                        $('#state_id').append('<option value="' + state.id + '">' +
                            state.name + '</option>');
                    });

                    // agar old selected state hai (Laravel old() se)
                    let oldState = "{{ old('state_id') }}";
                    if (oldState) {
                        $('#state_id').val(oldState).trigger('change');
                    }
                });
            }
        });

        // State change hone par cities load karega
        $('#state_id').change(function() {
            var state_id = $(this).val();
            $('#city_id').html('<option value="">Select City</option>');

            if (state_id) {
                $.get('/get-cities/' + state_id, function(cities) {
                    $.each(cities, function(key, city) {
                        $('#city_id').append('<option value="' + city.id + '">' + city
                            .name + '</option>');
                    });

                    // agar old selected city hai (Laravel old() se)
                    let oldCity = "{{ old('city_id') }}";
                    if (oldCity) {
                        $('#city_id').val(oldCity);
                    }
                });
            }
        });

        // Page load hone ke baad agar old country, state, city set hain, to wo auto-select kare
        let oldCountry = "{{ old('country_id') }}";
        if (oldCountry) {
            $('#country_id').val(oldCountry).trigger('change');
        }

    });
</script>



<script>
    $(document).ready(function() {
        setupRealtimeValidation('Branch', 'code', '#code');
    });
</script>
@endpush