@extends('include.master')
@section('content')
<div class="main-content-container overflow-hidden">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">{{ isset($employeeAsset) ? 'Edit' : 'Add' }} Expenses</h3>
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
                <li class="breadcrumb-item active">
                    {{ isset($employeeAsset) ? 'Edit' : 'Add' }} Expenses
                </li>
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

    <form 
        action="{{ isset($employeeAsset) ? route('employee-expense.update', $employeeAsset->id) : route('employee-expense.store') }}" 
        method="POST" class="needs-validation" novalidate>
        @csrf
        @if(isset($employeeAsset))
            @method('PUT')
        @endif
    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
            <h3 class="mb-20">Employee Expenses Information</h3>
            <div class="row">

                {{-- Type --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Expenses Type <span class="text-danger">*</span></label>
                    <select name="type" id="asset_type" class="form-select form-control" required>
                        <option value="">Select Type</option>
                        @foreach (['traveling', 'hotel', 'telephone', 'postage', 'printing & stationary', 'advertisement'] as $type)
                            <option value="{{ $type }}" {{ old('type', $employeeAsset->type ?? '') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

              

                {{-- Country --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Country <span class="text-danger">*</span></label>
                    <select name="country_id" id="country_id" class="form-select form-control" required>
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}"
                                {{ old('country_id', $employeeAsset->country_id ?? '') == $country->id ? 'selected' : '' }}>
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

                {{-- Amount --}}
                <div class="col-lg-6 mb-20 amount-field">
                    <label class="label fs-16 mb-2">Amount<span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" class="form-control amount-input" 
                        value="{{ old('amount', $employeeAsset->amount ?? '') }}" min="0" step="0.01">
                </div>

                {{-- Traveling Fields --}}
                <div class="traveling-fields row" style="display:none;">
                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">HQ Allow</label>
                        <input type="number" name="hq_allow" id="hq_allow" class="form-control amount-input"
                            value="{{ old('hq_allow', $employeeAsset->hq_allow ?? '') }}" min="0" step="0.01">
                    </div>

                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Ex Stn Allow</label>
                        <input type="number" name="ex_stn_allow" id="ex_stn_allow" class="form-control amount-input"
                            value="{{ old('ex_stn_allow', $employeeAsset->ex_stn_allow ?? '') }}" min="0" step="0.01">
                    </div>

                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Out Stn Allow</label>
                        <input type="number" name="out_stn_allow" id="out_stn_allow" class="form-control amount-input"
                            value="{{ old('out_stn_allow', $employeeAsset->out_stn_allow ?? '') }}" min="0" step="0.01">
                    </div>

                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Bus Ticket Amount</label>
                        <input type="number" name="bus_ticket_amount" id="bus_ticket_amount"
                            class="form-control amount-input" 
                            value="{{ old('bus_ticket_amount', $employeeAsset->bus_ticket_amount ?? '') }}" min="0" step="0.01">
                    </div>
                </div>

                {{-- Total Amount --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Total Amount</label>
                    <input type="number" name="total_amount" id="total_amount" class="form-control" readonly
                        value="{{ old('total_amount', $employeeAsset->total_amount ?? '0.00') }}">
                </div>

                {{-- Status --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Status</label>
                    <select name="status" class="form-select form-control">
                        <option value="Active" {{ old('status', $employeeAsset->status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $employeeAsset->status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-lg-12 mt-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary text-white">
                            {{ isset($employeeAsset) ? 'Update Expenses' : '+ Add Expenses' }}
                        </button>
                        <a href="{{ route('employee-expense.index') }}" class="btn btn-danger text-white">Cancel</a>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){

    // ✅ Show/hide fields dynamically
    function toggleFields() {
        if ($('#asset_type').val() === 'traveling') {
            $('.traveling-fields').slideDown(200);
            $('.amount-field').slideUp(200);
        } else {
            $('.traveling-fields input').val('');
            $('.traveling-fields').slideUp(200);
            $('.amount-field').slideDown(200);
        }
        calculateTotal();
    }

    // ✅ Calculate total amount
    function calculateTotal() {
        let total = 0;
        $('.amount-input').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#total_amount').val(total.toFixed(2));
    }

    $('#asset_type').on('change', toggleFields);
    $(document).on('input', '.amount-input', calculateTotal);
    toggleFields();

    // ✅ Load States
    $('#country_id').change(function(){
        let country_id = $(this).val();
        $('#state_id').html('<option value="">Select State</option>');
        $('#city_id').html('<option value="">Select City</option>');

        if(country_id){
            $.get('/get-states/'+country_id, function(states){
                $.each(states, function(key, state){
                    $('#state_id').append('<option value="'+state.id+'">'+state.name+'</option>');
                });
                let oldState = "{{ old('state_id', $employeeAsset->state_id ?? '') }}";
                if(oldState){ $('#state_id').val(oldState).trigger('change'); }
            });
        }
    });

    // ✅ Load Cities
    $('#state_id').change(function(){
        let state_id = $(this).val();
        $('#city_id').html('<option value="">Select City</option>');

        if(state_id){
            $.get('/get-cities/'+state_id, function(cities){
                $.each(cities, function(key, city){
                    $('#city_id').append('<option value="'+city.id+'">'+city.name+'</option>');
                });
                let oldCity = "{{ old('city_id', $employeeAsset->city_id ?? '') }}";
                if(oldCity){ $('#city_id').val(oldCity); }
            });
        }
    });

    let oldCountry = "{{ old('country_id', $employeeAsset->country_id ?? '') }}";
    if(oldCountry){ $('#country_id').val(oldCountry).trigger('change'); }

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
@endpush
