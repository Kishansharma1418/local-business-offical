@extends('include.master')

@section('content')

<div class="main-content-container overflow-hidden">


<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
    <h3 class="mb-0">Add Employee TDS</h3>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb align-items-center mb-0 lh-1">

            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                    <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                    <span class="text-body fs-14 hover">Dashboard</span>
                </a>
            </li>

            <li class="breadcrumb-item">
                <a href="{{ route('tds.index') }}" class="text-decoration-none">
                    TDS List
                </a>
            </li>

            <li class="breadcrumb-item active">
                Add TDS
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
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


<form method="POST" action="{{ route('tds.store') }}" class="needs-validation" novalidate>
    @csrf

    <div class="row">

        <div class="col-lg-12">

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">

                <h3 class="mb-20">Employee TDS Information</h3>

                <div class="row">


                    {{-- Employee --}}
                    <div class="col-lg-6 mb-20">

                        <label class="label fs-16 mb-2">
                            Employee <span class="text-danger">*</span>
                        </label>

                        <select name="employee_id" class="form-control" required>

                            <option value="">Select Employee</option>

                            @foreach($employees as $emp)

                                <option value="{{ $emp->id }}">

                                    {{ $emp->full_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- Financial Year --}}
                    <div class="col-lg-6 mb-20">

                        <label class="label fs-16 mb-2">
                            Financial Year <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="financial_year"
                            class="form-control"
                            placeholder="Example: 2025-26"
                            required
                            value="{{ old('financial_year') }}"
                        >

                    </div>



                    {{-- Month --}}
                    <div class="col-lg-6 mb-20">

                        <label class="label fs-16 mb-2">
                            Month <span class="text-danger">*</span>
                        </label>

                        <input
                            type="month"
                            name="month"
                            class="form-control"
                            required
                            value="{{ old('month') }}"
                        >

                    </div>



                    {{-- Gross Salary --}}
                    <div class="col-lg-6 mb-20">

                        <label class="label fs-16 mb-2">
                            Gross Salary
                        </label>

                        <input
                            type="number"
                            name="gross_salary"
                            class="form-control"
                            value="{{ old('gross_salary') }}"
                            min="0"
                        >

                    </div>



                    {{-- Taxable Salary --}}
                    <div class="col-lg-6 mb-20">

                        <label class="label fs-16 mb-2">
                            Taxable Salary
                        </label>

                        <input
                            type="number"
                            name="taxable_salary"
                            class="form-control"
                              id="taxable_salary"
                            value="{{ old('taxable_salary') }}"
                            min="0"
                        >

                    </div>



                    {{-- TDS Percent --}}
                    <div class="col-lg-6 mb-20">

                        <label class="label fs-16 mb-2">
                            TDS % 
                        </label>

                        <input
                            type="number"
                            name="tds_percent"
                            class="form-control"
                            step="0.01"
                             id="tds_percent"
                            min="0"
                            max="100"
                            value="{{ old('tds_percent') }}"
                        >

                    </div>



                    {{-- TDS Amount --}}
                    <div class="col-lg-6 mb-20">

                        <label class="label fs-16 mb-2">
                            TDS Amount <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="tds_amount"
                            class="form-control"
                              id="tds_amount"
                            step="0.01"
                            min="0"
                            required
                            readonly
                            value="{{ old('tds_amount') }}"
                        >

                    </div>



                    {{-- Remark --}}
                    <div class="col-lg-12 mb-20">

                        <label class="label fs-16 mb-2">
                            Remark
                        </label>

                        <textarea
                            name="remark"
                            class="form-control"
                            rows="3"
                        >{{ old('remark') }}</textarea>

                    </div>



                    {{-- Buttons --}}
                    <div class="col-lg-12 mt-3">

                        <div class="d-flex gap-2">

                            <button type="submit" class="btn btn-primary fw-normal text-white">
                                + Add TDS
                            </button>

                            <a href="{{ route('tds.index') }}"
                               class="btn btn-danger fw-normal text-white">

                                Cancel

                            </a>

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

$(document).ready(function(){

    $('form').on('submit', function(e){

        let form = $(this)[0];

        if(!form.checkValidity()){

            e.preventDefault();
            e.stopPropagation();

            $(this).addClass('was-validated');

            return false;
        }

        $(this).find('button[type="submit"]')
            .prop('disabled',true)
            .text('Processing...');

    });
 function calculateTDS(){

        let taxable = parseFloat($("#taxable_salary").val()) || 0;
        let percent = parseFloat($("#tds_percent").val()) || 0;

        if(percent > 100){
            percent = 100;
            $("#tds_percent").val(100);
        }

        let tdsAmount = (taxable * percent) / 100;

        $("#tds_amount").val(tdsAmount.toFixed(2));
    }

    $("#taxable_salary, #tds_percent").on("input", function(){
        calculateTDS();
    });
});

</script>

@endpush
