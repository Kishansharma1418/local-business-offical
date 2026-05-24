@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Overtime</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('overtime.index') }}" class="text-decoration-none">Overtime List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Overtime</li>
                </ol>
            </nav>
        </div>
        <form method="POST" action="{{ route('overtime.update', $overtime->id) }}" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Overtime Information</h3>
                        <div class="row">
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Employee</label>
                                <select name="employee_id" class="form-control">
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}"
                                            {{ $emp->id == $overtime->employee_id ? 'selected' : '' }}>

                                            {{ $emp->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Date</label>
                                <input type="date" name="date" value="{{ $overtime->date }}" class="form-control">
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Hours</label>
                                <input type="number" name="hours" value="{{ $overtime->hours }}" class="form-control"
                                    id="hours">
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Rate Per Hour</label>
                                <input type="number" name="rate_per_hour" value="{{ $overtime->rate_per_hour }}"
                                    class="form-control" id="rate_per_hour">
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Total Amount</label>
                                <input type="number" name="total_amount" value="{{ $overtime->total_amount }}"
                                    class="form-control" id="total_amount">
                            </div>
                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">Remark</label>
                                <textarea name="remark" class="form-control">
                                {{ $overtime->remark }}
                                </textarea>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary text-white">
                                        Update Overtime
                                    </button>
                                    <a href="{{ route('overtime.index') }}" class="btn btn-danger text-white">
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
        $(document).ready(function() {

            function calculateOvertime() {

                let hours = parseFloat($("#hours").val()) || 0;
                let rate = parseFloat($("#rate_per_hour").val()) || 0;

                let total = hours * rate;

                $("#total_amount").val(total.toFixed(2));

            }

            $("#hours").on("input", calculateOvertime);
            $("#rate_per_hour").on("input", calculateOvertime);

        });
    </script>
@endpush
