@extends('include.master')
@section('content')
<div class="main-content-container overflow-hidden">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">{{ $customerAddress ? 'Update Customer Address' : 'Add Customer Address' }}</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('customers.index') }}" class="text-decoration-none text-body fs-14 hover">
                        Customer List
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    <span class="text-secondary">{{ $customerAddress ? 'Edit' : 'Add' }} Customer Address</span>
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

    {{-- Flash Messages --}}
    {{-- @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif --}}
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('customer.address.store') }}" class="needs-validation" novalidate>
        @csrf
        <input type="hidden" name="customer_id" value="{{ $customer_id }}">

        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
            <h3 class="mb-3">Customer Address Information</h3>
            <div class="row">

                {{-- Address Title --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">Address Title</label>
                    <input type="text" class="form-control" name="address_title"
                        placeholder="e.g. Home, Office"
                        value="{{ old('address_title', $customerAddress->address_title ?? '') }}">
                </div>

                {{-- Address Type --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">Address Type</label>
                    <select class="form-control" name="address_type">
                        <option value="">Select Type</option>
                        @foreach (['Billing', 'Shipping', 'Office', 'Other'] as $type)
                            <option value="{{ $type }}"
                                {{ old('address_type', $customerAddress->address_type ?? '') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Country --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">Country<span class="text-danger">*</span></label>
                    <select name="country_id" id="country" class="form-control" required>
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ old('country_id', $customerAddress->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- State --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">State<span class="text-danger">*</span></label>
                    <select name="state_id" id="state" class="form-control" required>
                        <option value="">Select State</option>
                    </select>
                </div>

                {{-- City --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">City<span class="text-danger">*</span></label>
                    <select name="city_id" id="city" class="form-control" required>
                        <option value="">Select City</option>
                    </select>
                </div>

                {{-- Address Line 1 --}}
                <div class="col-lg-12 mb-20">
                    <label class="label fs-16 mb-2">Address Line 1<span class="text-danger">*</span></label>
                    <textarea class="form-control" name="address_line1" placeholder="House No, Street, Locality" required>{{ old('address_line1', $customerAddress->address_line1 ?? '') }}</textarea>
                </div>

                {{-- Address Line 2 --}}
                <div class="col-lg-12 mb-20">
                    <label class="label fs-16 mb-2">Address Line 2</label>
                    <textarea class="form-control" name="address_line2" placeholder="Landmark (optional)">{{ old('address_line2', $customerAddress->address_line2 ?? '') }}</textarea>
                </div>

                {{-- Pincode --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">Pincode</label>
                    <input type="text" class="form-control" name="pincode"
                        value="{{ old('pincode', $customerAddress->pincode ?? '') }}" maxlength="6"
                        pattern="^[1-9][0-9]{5}$"
                        title="Please enter a valid 6-digit Pincode"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);">
                </div>

                {{-- Default Address Checkbox --}}
                <div class="col-lg-4 mb-20 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="is_default" id="is_default" value="1"
                            class="form-check-input"
                            {{ old('is_default', $customerAddress->is_default ?? false) ? 'checked' : '' }}>
                        <label for="is_default" class="form-check-label">Set as Default Address</label>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="col-lg-12 mt-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary fw-normal text-white">
                            {{ $customerAddress ? 'Update Customer Address' : '+ Add Customer Address' }}
                        </button>
                        <a href="{{ route('customers.index') }}" class="btn btn-danger fw-normal text-white">Cancel</a>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Prefill states and cities when editing
    let selectedState = "{{ old('state_id', $customerAddress->state_id ?? '') }}";
    let selectedCity = "{{ old('city_id', $customerAddress->city_id ?? '') }}";
    let selectedCountry = "{{ old('country_id', $customerAddress->country_id ?? '') }}";

    // Load states if country exists
    if (selectedCountry) {
        $.get('/get-states/' + selectedCountry, function (data) {
            $('#state').empty().append('<option value="">Select State</option>');
            $.each(data, function (key, value) {
                $('#state').append('<option value="' + value.id + '"' + (value.id == selectedState ? ' selected' : '') + '>' + value.name + '</option>');
            });
            if (selectedState) loadCities(selectedState);
        });
    }

    // Country -> State change
    $('#country').on('change', function () {
        let countryID = $(this).val();
        if (countryID) {
            $.get('/get-states/' + countryID, function (data) {
                $('#state').empty().append('<option value="">Select State</option>');
                $('#city').empty().append('<option value="">Select City</option>');
                $.each(data, function (key, value) {
                    $('#state').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            });
        }
    });

    $('#state').on('change', function () {
        loadCities($(this).val());
    });

    function loadCities(stateID) {
        if (stateID) {
            $.get('/get-cities/' + stateID, function (data) {
                $('#city').empty().append('<option value="">Select City</option>');
                $.each(data, function (key, value) {
                    $('#city').append('<option value="' + value.id + '"' + (value.id == selectedCity ? ' selected' : '') + '>' + value.name + '</option>');
                });
            });
        }
    }
});
</script>
@endpush
