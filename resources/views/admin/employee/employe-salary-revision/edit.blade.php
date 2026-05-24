@extends('include.master')
@section('content')

<div class="main-content-container overflow-hidden">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Edit Employee Salary Revision</h3>
    </div>

    <form method="POST" action="{{ route('employee.revisionsalary.update', $revision->id) }}">
        @csrf
        @method('PUT')

        <input type="hidden" name="employee_id" value="{{ $revision->employee_id }}">

        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
            <h4 class="mb-3 text-primary">Edit Salary Revision</h4>

            <div class="row">

                {{-- COMPONENTS --}}
                @foreach($components as $com)

                @php
                    $existingAmount = $revision->components
                        ->where('salary_component_id', $com->id)
                        ->first()
                        ->amount ?? '';
                @endphp

                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">
                        {{ $com->component_name }}
                        <small class="text-muted">({{ ucfirst($com->component_type) }})</small>
                    </label>

                    @if($com->calculation_type == 'Fixed')
                        <input type="number" step="0.01"
                            class="form-control fixed-input"
                            name="components[{{ $com->id }}][amount]"
                            value="{{ $existingAmount }}">
                    @else
                        <div class="input-group">
                            <input type="number" step="0.01"
                                class="form-control percentage-input"
                                name="components[{{ $com->id }}][amount]"
                                data-percent="{{ $com->percentage_value }}"
                                value="{{ $existingAmount }}"
                                readonly>
                            <span class="input-group-text">{{ $com->percentage_value }}%</span>
                        </div>
                    @endif

                    <input type="hidden" name="components[{{ $com->id }}][type]"
                        value="{{ $com->component_type }}">
                </div>
                @endforeach

                {{-- EFFECTIVE DATE --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">Effective From</label>
                    <input type="date" class="form-control"
                        name="effective_from"
                        value="{{ $revision->effective_from }}">
                </div>

                {{-- REASON --}}
                <div class="col-lg-8 mb-20">
                    <label class="label fs-16 mb-2">Revision Reason</label>
                    <textarea class="form-control" rows="2"
                        name="revision_reason">{{ $revision->revision_reason }}</textarea>
                </div>

                {{-- STATUS --}}
                <div class="col-lg-4 mb-20">
                    <label class="label fs-16 mb-2">Status</label>
                    <select class="form-control" name="status">
                        <option value="1" {{ $revision->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $revision->status == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="col-lg-12 mt-3">
                    <button type="submit" class="btn btn-primary">Update Salary Revision</button>
                    <a href="{{ route('employee.revisionsalarylist.index', $revision->employee_id) }}"
                        class="btn btn-danger">Cancel</a>
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