@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <!-- 🔹 Page Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0 ">Edit Attendance - {{ $employee->name ?? 'N/A' }}</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('employee-attandance.index') }}" class="text-decoration-none">Attendance List</a>
                </li>
                <li class="breadcrumb-item active">Edit Attendance</li>
            </ol>
        </nav>
    </div>

    {{--  Success Message --}}
    @if (session('success'))
        <div class="alert alert-success shadow-sm border-start border-4 border-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- 🔹 Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm border-start border-4 border-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 🔹 Summary Cards --}}
    <div class="row mb-4">
        @php
            $cards = [
                ['label' => 'Present', 'color' => 'text-success', 'value' => $summary->present ?? 0],
                ['label' => 'Leave', 'color' => 'text-danger', 'value' => $summary->leave ?? 0],
                ['label' => 'Weekly Off', 'color' => 'text-primary', 'value' => $summary->weekly_off ?? 0],
                ['label' => 'Half Day', 'color' => 'text-warning', 'value' => $summary->half_day ?? 0],
                ['label' => 'Holiday', 'color' => 'text-info', 'value' => $summary->holiday ?? 0],
                ['label' => 'Total', 'color' => 'text-info', 'value' => $summary->total ?? 0],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                <div class="p-3 bg-white text-center rounded-10 shadow-sm border">
                    <p class="mb-1 fw-bold {{ $card['color'] }}">{{ $card['label'] }}</p>
                    <h5 class="mb-0">{{ $card['value'] }}</h5>
                </div>
            </div>
        @endforeach
    </div>

    {{-- 🔹 Edit Form --}}
    <form action="{{ route('employee-attandance.update', encrypt($employee->employee_id)) }}" method="POST"
        class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        <div class="card bg-white p-20 rounded-10 shadow-sm border mb-4">
            <h4 class="mb-3 ">Attendance Records</h4>

            <div class="table-responsive">   
                <table class="table table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold">Date</th>
                            <th class="fw-semibold">Status</th>
                            <th class="fw-semibold">Check In</th>
                            <th class="fw-semibold">Check Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $rec)
                            <tr>
                                <td class="bg-white">{{ \Carbon\Carbon::parse($rec->date)->format('d M, Y') }}</td>

                                <td class="bg-white">
                                    <select name="attendance[{{ $rec->id }}][status]" class="form-control form-select-sm">
                                        @foreach (['Present', 'Weekly Off', 'Half Day', 'Holiday','Leave'] as $status)
                                            <option value="{{ $status }}"
                                                {{ $rec->status == $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="bg-white">
                                    <input type="time" name="attendance[{{ $rec->id }}][check_in]"
                                        value="{{ $rec->check_in ? date('H:i', strtotime($rec->check_in)) : '09:00' }}"
                                        class="form-control form-control-sm text-center">
                                </td>

                                <td class="bg-white">
                                    <input type="time" name="attendance[{{ $rec->id }}][check_out]"
                                        value="{{ $rec->check_out ? date('H:i', strtotime($rec->check_out)) : '17:00' }}"
                                        class="form-control form-control-sm text-center">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Buttons --}}
            <div class="d-flex justify-content-start mt-3 gap-2">
                <button type="submit" class="btn btn-primary fw-normal text-white">
                    <i class="ri-save-line me-1"></i> Update Attendance
                </button>
                <a href="{{ route('employee-attandance.index') }}" class="btn btn-danger fw-normal text-white">
                     Cancel
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    function toggleTimeInputs() {
        $("select[name*='[status]']").each(function () {
            let status = $(this).val();
            let row = $(this).closest("tr");

            let checkIn = row.find("input[name*='[check_in]']");
            let checkOut = row.find("input[name*='[check_out]']");

            if (status === "Weekly Off" || status === "Holiday" || status === "Leave") {
                // Disable and clear
                checkIn.val("").prop("readonly", true);
                checkOut.val("").prop("readonly", true);
            } else {
                // Enable and set default if empty
                checkIn.prop("readonly", false);
                checkOut.prop("readonly", false);

                if (checkIn.val() === "") checkIn.val("09:00");
                if (checkOut.val() === "") checkOut.val("17:00");
            }
        });
    }

    // Apply on page load
    toggleTimeInputs();

    // Apply on change event
    $(document).on("change", "select[name*='[status]']", function () {
        toggleTimeInputs();
    });

});
</script>

@endpush
