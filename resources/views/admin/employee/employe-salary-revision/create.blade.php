@extends('include.master')
@section('content')

<div class="main-content-container overflow-hidden">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Employee Salary Revision</h3>
    </div>

    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('salaryRevisitionStoree') }}" class="needs-validation"
            novalidate>
        @csrf
        <input type="hidden" name="employee_id" value="{{ $employee_id }}">

        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
            <h4 class="mb-3 text-primary">Add Salary Revision</h4>

            <div class="row">

              
                {{-- COMPONENTS --}}
                @foreach($components as $com)
                <div class="col-lg-4 mb-20">

                    <label class="label fs-16 mb-2">
                        {{ $com->component_name }}
                        <small class="text-muted">({{ ucfirst($com->component_type) }})</small>
                    </label>

                    @if($com->calculation_type == 'Fixed')
                        <input type="number" step="0.01" class="form-control fixed-input"
                            name="components[{{ $com->id }}][amount]"
                            placeholder="Enter amount" min='0' > 
                    @else
                        <div class="input-group">
                            <input type="number" step="0.01"
                                class="form-control percentage-input"
                                name="components[{{ $com->id }}][amount]"
                                data-percent="{{ $com->percentage_value }}"
                                readonly>
                            <span class="input-group-text">{{ $com->percentage_value }}%</span>
                        </div>
                    @endif

                    <input type="hidden" name="components[{{ $com->id }}][type]" value="{{ $com->component_type }}">
                </div>
                @endforeach

                {{-- EFFECTIVE DATE --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">Effective From <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="effective_from" required>
                </div>

                {{-- REASON --}}
                <div class="col-lg-8 mb-20">
                    <label class="label fs-16 mb-2">Revision Reason</label>
                    <textarea class="form-control" rows="2" name="revision_reason"></textarea>
                </div>

                {{-- STATUS --}}`
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">Status</label>
                    <select class="form-control" name="status">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div class="col-lg-12 mt-3">
                    <button type="submit" class="btn btn-primary fw-normal text-white">+ Add Salary Revision</button>
                    <a href="{{ route('employee.revisionsalarylist.index', $employee_id) }}"
                        class="btn btn-danger fw-normal text-white">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')

<script>
$(document).ready(function () {

    function calculateSalary() {

        let totalFixed = 0;

        $(".fixed-input").each(function () {
            totalFixed += parseFloat($(this).val() || 0);
        });

        $(".percentage-input").each(function () {
            let percent = parseFloat($(this).data("percent"));
            let amount = (totalFixed * percent) / 100;

            $(this).val(amount.toFixed(2));
        });

        let totalSalary = 0;
        $("input[name*='[amount]']").each(function () {
            totalSalary += parseFloat($(this).val() || 0);
        });

        $("#totalPay").val(totalSalary.toFixed(2));
    }

    $(document).on("input", ".fixed-input", function () {
        calculateSalary();
    });

});
</script>

@endpush
