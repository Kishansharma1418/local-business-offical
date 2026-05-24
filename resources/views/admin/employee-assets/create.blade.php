@extends('include.master')

@section('content')
<style>
    /* Table White Background */
    .table {
        background-color: white !important;
        color: #333 !important;
    }

    .table thead th {
        background: #f8f9fa !important;
    }

    .table tbody tr {
        background-color: white !important;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa !important;
    }

    .table td {
        background-color: white !important;
        border-color: #dee2e6 !important;
    }

    .form-label {
        background-color: white !important;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
        color: #333;
        margin-bottom: 4px;
    }

    #monthPicker {
        background-color: white !important;
        color: #333 !important;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .rounded-10 {
        border-radius: 10px;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .saveRow,
    .addMore {
        padding: 4px 12px;
        font-size: 12px;
        height: auto;
        line-height: 1.2;
    }

    .card,
    .card-body {
        background-color: white !important;
    }

    /* Red Border for Validation */
    .error-border {
        border: 1px solid red !important;
    }

    /* Table White Background */
    .table {
        background-color: white !important;
        color: #333 !important;
        font-size: 12px;
        /* 👈 table compact */
    }

    .table thead th {
        background: #f8f9fa !important;
        font-size: 12px;
        padding: 6px 8px !important;
        /* 👈 small header space */
    }

    .table tbody td {
        padding: 4px 6px !important;
        /* 👈 compact rows */
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa !important;
    }

    .form-control-sm {
        padding: 3px 6px !important;
        /* 👈 small inputs */
        height: 30px !important;
        /* 👈 input height reduce */
        font-size: 12px !important;
    }

    select.form-control {
        height: 30px !important;
        /* 👈 select height reduce */
        padding: 3px 6px !important;
        font-size: 12px !important;
    }

    .btn-sm {
        padding: 3px 10px !important;
        /* 👈 smaller buttons */
        font-size: 12px !important;
    }

    #monthPicker {
        height: 32px !important;
        font-size: 12px;
    }

    /* Reduce Card & Page Spacing */
    .card-body {
        padding: 12px !important;
    }

    .main-content-container {
        padding: 5px 10px !important;
        /* 👈 overall page compact */
    }

    .form-label {
        font-size: 12px;
        margin-bottom: 2px;
    }

    /* Reduce space between rows */
    #dailyExpenseTable input,
    #dailyExpenseTable select {
        margin: 0px !important;
    }

    /* Error border */
    .error-border {
        border: 1px solid red !important;
    }

    /* Disable row background */
    .disabled-row {
        background: #f1f1f1 !important;
        opacity: 0.85;
    }
</style>


<div class="main-content-container">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 mt-1">
        <h3 class="mb-0 fw-semibold">Employee Daily Expenses</h3>
    </div>

    <div class="card rounded-10 border mb-4">
        <div class="card-body">
            <div class="row mb-3 align-items-end">
                <div class="col-lg-3">
                    <label class="form-label">Select Month *</label>
                    <input type="month" id="monthPicker" class="form-control form-control-sm">
                </div>
                <div class="col-lg-9 d-flex justify-content-end">
                    <button class="btn btn-primary btn-sm text-white" id="submitAll">
                        <span class="spinner-border spinner-border-sm d-none" id="submitSpinner"></span>
                        Submit All
                    </button>
                </div>
            </div>
            <div class="row mb-3" id="attendanceSummary">

                <div class="col-md-2">
                    <div class="card p-2 text-center">
                        <small>Present</small>
                        <h6 id="presentCount">0</h6>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card p-2 text-center">
                        <small>Leave</small>
                        <h6 id="leaveCount">0</h6>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card p-2 text-center">
                        <small>Holiday</small>
                        <h6 id="holidayCount">0</h6>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card p-2 text-center">
                        <small>Sunday</small>
                        <h6 id="sundayCount">0</h6>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card p-2 text-center">
                        <small>Half Day</small>
                        <h6 id="halfdayCount">0</h6>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card p-2 text-center">
                        <small>Total Days</small>
                        <h6 id="totalDays">0</h6>
                    </div>
                </div>

            </div>

        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm" id="dailyExpenseTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Expense Type</th>
                        <th>Working Type</th>
                        <th>Travel From</th>
                        <th>Travel To</th>
                        <th>Distance (KM)</th>
                        <th>Other Amount</th>
                        <th>HQ</th>
                        <th>Ex Stn</th>
                        <th>Out Stn</th>
                        {{-- <th>Rly/Bus Tkt</th> --}}
                        <th>Total</th>
                        <th>Upload File</th>
                        <th>Status</th>
                        <th width="160">Action</th>
                    </tr>
                </thead>
                <tbody id="expenseBody"></tbody>
            </table>
        </div>
        <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
                <div class="modal-content bg-white">

                    <div class="modal-header border-border-color-40 p-20  text-white">
                        <h5 class="modal-title fs-18 fw-medium mb-0">Rejected Reason</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>


                    <div class="p-3 rounded-10 mb-0 " style="background: white;">

                        <label class="fs-16 fw-semibold mb-1 text-dark">Reason:</label>

                        <p id="rejectReasonText"
                            class="mt-0 mb-0 fs-16 text-dark form-control"
                            style="white-space: normal; word-break: break-word; max-height: 150px; overflow-y: auto;">
                        </p>

                    </div>


                    <div class="modal-footer border-0 p-20 pt-0">
                        <button type="button" class="btn btn-primary fw-normal text-white"
                            data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let rowTemplate = `
            <tr>

                 <td>
                    <span class="date-text"></span>
                    <input type="hidden" class="exp-date">
                </td>
                <input type="hidden" class="row-id">

                <td>
                    <select class="form-control expense-type">
                        <option value="">Select</option>
                        <option value="traveling">Traveling</option>
                        <option value="hotel">Hotel</option>
                        <option value="telephone">Mobile</option>
                        <option value="postage">Postage</option>
                        <option value="printing">Printing</option>
                        <option value="advertisement">Advertisement</option>
                        <option value="bankcharges">Bank Charges</option>
                        <option value="donation">Donation</option>
                        <option value="leave">Leave</option>
                        <option value="holiday">Holiday</option>
                        <option value="halfday">Halfday</option>
                        <option value="mislinious">Miscellaneous</option>
                        <option value="exgratia_perquisites">ex gratia perquisites</option>
                        <option value="weekly_off">Sunday</option>
                        <option value="sunday_claims">Sunday Claims</option>
                        <option value= 'bus_ticket'>Bus Ticket</option>
                    </select>
                </td>

                    <td>
                        <select class="form-control working-type">
                            <option value="">Select</option>
                            <option value="half_day">Half Day</option>
                            <option value="leave">Leave</option>
                           
                            <option value='present'>Present</option>
                        </select>
                    </td>
                <td><input type="text" class="form-control form-control-sm travel_from"></td>
                <td><input type="text" class="form-control form-control-sm travel_to"></td>
                <td><input type="number" class="form-control form-control-sm distance" min="0"></td>

                <td><input type="number" class="form-control form-control-sm amount" min="0"></td>
                <td><input type="number" class="form-control form-control-sm hq_allow" disabled min="0"></td>
                <td><input type="number" class="form-control form-control-sm ex_stn_allow" disabled min="0"></td>
                <td><input type="number" class="form-control form-control-sm out_stn_allow" disabled min="0"></td>
             

                <td><input type="number" class="form-control form-control-sm total" readonly></td>
            <td><input type="file" class="form-control form-control-sm file">
              <small class="file-name text-muted d-block mt-1"></small></td>
            
                <td><input type="text" class="form-control form-control-sm status" readonly></td>

                <td>
                    <button class="btn btn-primary btn-sm text-white saveRow">
                        <span class="spinner-border spinner-border-sm d-none"></span> Save
                    </button>
                    <button class="btn btn-primary btn-sm text-white addMore">+ Add</button>
                                    <button class="bg-transparent p-0 border-0 deleteRow d-none">

                        <i class="material-symbols-outlined fs-18 text-danger">delete</i>
                    </button>
                </td>

            </tr>`;


        //----------------------------
        // VALIDATION FUNCTION
        //----------------------------
        function validateRow(row) {
            row.find("input, select").removeClass("error-border");

            let type = row.find(".expense-type").val();
            let distance = row.find(".distance").val();
            let amount = row.find(".amount").val();
            let hq = row.find(".hq_allow").val();
            let ex = row.find(".ex_stn_allow").val();
            let out = row.find(".out_stn_allow").val();
            let bus = row.find(".bus_ticket").val();

            if (!type) {
                toastr.error("Please select expense type");
                row.find(".expense-type").addClass("error-border");
                return false;
            }

            if (type === "traveling") {
                if ((!hq && !ex && !out) || !distance) {

                    toastr.error("Please fill traveling fields");

                    if (!hq && !ex && !out) {
                        row.find(".hq_allow").addClass("error-border");
                        row.find(".ex_stn_allow").addClass("error-border");
                        row.find(".out_stn_allow").addClass("error-border");
                    }

                    if (!distance) row.find(".distance").addClass("error-border");
                    if (!travel_from) row.find(".travel_from").addClass("error-border");
                    if (!travel_to) row.find(".travel_to").addClass("error-border");

                    return false;
                }
            }

            if (type !== "traveling" && type !== "holiday" && type !== "leave" && type !== "weekly_off" &&
                type !== "sunday_claims") {
                if (!amount) {
                    toastr.error("Amount is required");
                    row.find(".amount").addClass("error-border");
                    return false;
                }
            }

            return true;
        }



        $("#dailyExpenseTable").on("change", ".expense-type", function() {
            let row = $(this).closest("tr");
            let type = $(this).val();

            if (type === "traveling") {
                row.find(".amount").prop("disabled", true).val("");
                row.find(".hq_allow, .ex_stn_allow, .out_stn_allow, .travel_from, .travel_to").prop(
                    "disabled",
                    false);
            } else if (type === "leave" || type === "holiday") {
                row.find(".amount").prop("disabled", true).val("");
                row.find(".hq_allow, .ex_stn_allow, .out_stn_allow, .travel_from, .travel_to")
                    .prop("disabled", true).val("");
                row.find(".total").val("0.00");
            } else {
                row.find(".amount").prop("disabled", false);
                row.find(".hq_allow, .ex_stn_allow, .out_stn_allow, .travel_from, .travel_to")
                    .prop("disabled", true).val("");
            }

            recalcTotal(row);
        });

        $("#dailyExpenseTable").on("input", ".hq_allow, .ex_stn_allow, .out_stn_allow", function() {

            let row = $(this).closest("tr");

            let hq = row.find(".hq_allow");
            let ex = row.find(".ex_stn_allow");
            let out = row.find(".out_stn_allow");

            let hqVal = parseFloat(hq.val()) || 0;
            let exVal = parseFloat(ex.val()) || 0;
            let outVal = parseFloat(out.val()) || 0;

            if (hqVal > 0) {
                ex.prop("disabled", true).val("");
                out.prop("disabled", true).val("");
            } else if (exVal > 0) {
                hq.prop("disabled", true).val("");
                out.prop("disabled", true).val("");
            } else if (outVal > 0) {
                hq.prop("disabled", true).val("");
                ex.prop("disabled", true).val("");
            } else {
                hq.prop("disabled", false);
                ex.prop("disabled", false);
                out.prop("disabled", false);
            }

            recalcTotal(row);
        });
        //----------------------------
        // RECALC TOTAL
        //----------------------------
        function recalcTotal(row) {
            let type = row.find(".expense-type").val();
            let total = 0;

            if (type === "traveling") {
                total =
                    (parseFloat(row.find(".hq_allow").val()) || 0) +
                    (parseFloat(row.find(".ex_stn_allow").val()) || 0) +
                    (parseFloat(row.find(".out_stn_allow").val()) || 0) +
                    (parseFloat(row.find(".bus_ticket").val()) || 0);
            } else if (type === "leave" || type === "holiday") {
                total = 0;
            } else {
                total = parseFloat(row.find(".amount").val()) || 0;
            }

            row.find(".total").val(total.toFixed(2));
        }

        $("#dailyExpenseTable").on("input", ".amount, .hq_allow, .ex_stn_allow, .out_stn_allow, .bus_ticket",
            function() {
                recalcTotal($(this).closest("tr"));
            });


        //----------------------------
        // SAVE ROW
        //----------------------------
        $("#dailyExpenseTable").on("click", ".saveRow", function() {

            let btn = $(this);
            let row = btn.closest("tr");
            let month = $("#monthPicker").val();

            if (!month) return toastr.error("Select month first!");

            if (!validateRow(row)) return;

            let spinner = btn.find(".spinner-border");
            btn.prop("disabled", true);
            spinner.removeClass("d-none");

            let formData = new FormData();

            formData.append("_token", "{{ csrf_token() }}");
            formData.append("employee_id", "{{ auth()->user()->reference_id }}");

            formData.append("expenses[0][id]", row.find(".row-id").val());
            formData.append("expenses[0][date]", row.find(".exp-date").val());
            formData.append("expenses[0][type]", row.find(".expense-type").val());
            formData.append("expenses[0][distance]", row.find(".distance").val());
            formData.append("expenses[0][travel_from]", row.find(".travel_from").val());
            formData.append("expenses[0][travel_to]", row.find(".travel_to").val());
            formData.append("expenses[0][working_type]", row.find(".working-type").val());
            formData.append("expenses[0][amount]", row.find(".amount").val());
            formData.append("expenses[0][hq_allow]", row.find(".hq_allow").val());
            formData.append("expenses[0][ex_stn_allow]", row.find(".ex_stn_allow").val());
            formData.append("expenses[0][out_stn_allow]", row.find(".out_stn_allow").val());
            formData.append("expenses[0][bus_ticket]", row.find(".bus_ticket").val());
            formData.append("expenses[0][total]", row.find(".total").val());

            let fileInput = row.find(".file")[0];
            if (fileInput && fileInput.files.length > 0) {
                formData.append("expenses[0][file]", fileInput.files[0]);
            }

            $.ajax({
                url: "{{ route('employee-expense.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {

                    spinner.addClass("d-none");
                    btn.prop("disabled", false);

                    btn.text("Update")
                        .removeClass("btn-primary")
                        .addClass("btn-success");

                    if (res.saved_ids) {
                        row.find(".row-id").val(res.saved_ids[0]);
                    }

                    if (res.image) {
                        row.find(".view-file-link").remove();
                        row.find(".file").after(
                            `<a href="/storage/${res.image}" target="_blank" class="text-primary ms-2  view-file-link">View File</a>`
                        );
                    }

                    toastr.success("Saved Successfully");
                },
                error: function() {
                    spinner.addClass("d-none");
                    btn.prop("disabled", false);
                    toastr.error("Error saving!");
                }
            });

        });

        function generateMonthRows(month) {
            let year = month.split("-")[0];
            let mon = month.split("-")[1];

            let days = new Date(year, mon, 0).getDate();

            $("#expenseBody").html("");

            $.post("{{ route('employee-expense.fetch') }}", {
                employee_id: "{{ auth()->user()->reference_id }}",
                month: month,
                _token: "{{ csrf_token() }}"
            }, function(res) {

                let saved = {};
                res.data.forEach(x => {
                    if (!saved[x.start_date]) saved[x.start_date] = [];
                    saved[x.start_date].push(x);
                });
                let present = 0;
                let leave = 0;
                let holiday = 0;
                let sunday = 0;
                let halfday = 0;

                res.data.forEach(function(x) {

                    if (x.type === "leave") leave++;
                    else if (x.type === "holiday") holiday++;
                    else if (x.type === "weekly_off") sunday++;
                    else if (x.type === "halfday") halfday++;
                    else present++; // 👈 baaki sab present

                });

                $("#presentCount").text(present);
                $("#leaveCount").text(leave);
                $("#holidayCount").text(holiday);
                $("#sundayCount").text(sunday);
                $("#halfdayCount").text(halfday);

                $("#totalDays").text(present + leave + holiday + sunday + halfday);

                for (let d = 1; d <= days; d++) {
                    let date = `${month}-${String(d).padStart(2,'0')}`;

                    let dObj = new Date(date);
                    let dayName = dObj.toLocaleDateString('en-US', {
                        weekday: 'long'
                    });

                    if (saved[date]) {
                        saved[date].forEach(s => {
                            let r = $(rowTemplate);

                            r.find(".date-text").text(`${s.start_date} (${dayName})`);
                            r.find(".exp-date").val(s.start_date);

                            r.find(".exp-date").val(s.start_date);
                            r.find(".row-id").val(s.id);
                            r.find(".deleteRow").removeClass("d-none");
                            r.find(".expense-type").val(s.type).trigger("change");

                            r.find(".distance").val(s.distance);
                            r.find(".travel_from").val(s.travel_from);
                            r.find(".travel_to").val(s.travel_to);
                            r.find(".working-type").val(s.working_type);
                            r.find(".amount").val(s.amount);
                            r.find(".hq_allow").val(s.hq_allow);
                            r.find(".ex_stn_allow").val(s.ex_stn_allow);
                            r.find(".out_stn_allow").val(s.out_stn_allow);
                            r.find(".bus_ticket").val(s.bus_ticket_amount);
                            r.find(".total").val(s.total_amount);
                            r.find(".status").val(s.status);


                            if (s.status === "Verified") {
                                r.find("td:eq(12)").html(`
                                    <span class="badge bg-success">Verified</span>
                                `);

                                r.find("input, select").prop("disabled", true);
                                r.find(".amount").prop("disabled", true);
                                r.find(".file").prop("disabled", true);
                                r.find(".saveRow").hide();
                                r.find(".addMore").hide();
                                r.find(".deleteRow").hide();
                                r.css("background", "#f1f1f1");
                                $("#expenseBody").append(r);
                                return;
                            }
                            if (s.status === "Rejected") {
                                r.find("input, select").prop("disabled", false);
                                r.find(".amount").prop("disabled", false);
                                r.find(".file").prop("disabled", false);
                                r.find("td:eq(12)").html(`
 
                                    <div class="d-flex align-items-center gap-1">
                                        <span class="badge bg-danger">Rejected</span>
                                        <button class="btn btn-sm viewReason" data-reason="${s.reason}">
                                            <em class="fas fa-eye font-16"></em>
                                        </button>
                                    </div>
                                `);
                            }
                            if (s.status === "Submited") {
                                r.find("td:eq(12)").html(`
                                    <span class="badge bg-warning text-white">Submitted</span>
                                `);
                            }
                            let type = s.type;
                            if (type === "traveling") {
                                r.find(".amount").prop("disabled", true);
                                r.find(
                                        ".hq_allow, .ex_stn_allow, .out_stn_allow, .bus_ticket, .distance"
                                    )
                                    .prop("disabled", false);
                            } else if (type === "holiday" || type === "leave") {
                                r.find(".amount").prop("disabled", true);
                                r.find(".hq_allow, .ex_stn_allow, .out_stn_allow, .bus_ticket")
                                    .prop("disabled", true);
                            } else {
                                r.find(".amount").prop("disabled", false);
                                r.find(".hq_allow, .ex_stn_allow, .out_stn_allow, .bus_ticket")
                                    .prop("disabled", true);
                            }

                            r.find(".saveRow").text("Update")
                                .removeClass("btn-primary")
                                .addClass("btn-success");

                            if (s.image) {
                                r.find(".view-file-link").remove();
                                r.find(".file").after(
                                    `<a href="/storage/${s.image}" target="_blank" class="text-primary  view-file-link">View File</a>`
                                );
                            }

                            $("#expenseBody").append(r);

                        });

                    } else {
                        let r = $(rowTemplate);
                        r.find(".exp-date").val(date);
                        r.find(".date-text").text(`${date} (${dayName})`);

                        // ---- AUTO SELECT WEEKLY OFF ON SUNDAY ----
                        if (dayName === "Sunday") {
                            r.find(".expense-type").val("weekly_off").trigger("change");

                            r.find(".total").val("0.00");
                        }

                        $("#expenseBody").append(r);
                    }
                }

            });
        }


        $("#dailyExpenseTable").on("click", ".addMore", function() {
            let row = $(this).closest("tr");
            let date = row.find(".exp-date").val();

            let r = $(rowTemplate);
            r.find(".deleteRow").removeClass("d-none");
            // r.find(".exp-date").val(date);
            let dObj = new Date(date);
            let dayName = dObj.toLocaleDateString('en-US', {
                weekday: 'long'
            });
            r.find(".date-text").text(`${date} (${dayName})`);
            r.find(".exp-date").val(date);

            row.after(r);
        });

        // $("#submitAll").click(function() {

        //     let btn = $(this);
        //     let spinner = $("#submitSpinner");

        //     let month = $("#monthPicker").val();
        //     if (!month) return toastr.error("Select month first!");

        //     if (!confirm("Submit all expenses for this month?")) return;

        //     spinner.removeClass("d-none");
        //     btn.prop("disabled", true).text("Submitting...");

        //     let formData = new FormData();

        //     formData.append("_token", "{{ csrf_token() }}");
        //     formData.append("employee_id", "{{ auth()->user()->reference_id }}");

        //     let hasError = false;
        //     let rowIndex = 0;

        //     $("#expenseBody tr").each(function() {
        //         let row = $(this);
        //         row.find("input, select").removeClass("error-border");

        //         let type = row.find(".expense-type").val();
        //         let amount = row.find(".amount").val();
        //         let hq = row.find(".hq_allow").val();
        //         let ex = row.find(".ex_stn_allow").val();
        //         let out = row.find(".out_stn_allow").val();
        //         let bus = row.find(".bus_ticket").val();
        //         let distance = row.find(".distance").val();

        //         if (!type) {
        //             toastr.error("Some rows have empty Expense Type!");
        //             row.find(".expense-type").addClass("error-border");
        //             hasError = true;
        //             return false;
        //         }

        //         if (type === "traveling") {
        //             if (!hq || !ex || !out || !bus || !distance) {
        //                 toastr.error("Some Traveling rows are incomplete!");
        //                 if (!hq) row.find(".hq_allow").addClass("error-border");
        //                 if (!ex) row.find(".ex_stn_allow").addClass("error-border");
        //                 if (!out) row.find(".out_stn_allow").addClass("error-border");
        //                 if (!bus) row.find(".bus_ticket").addClass("error-border");
        //                 if (!distance) row.find(".distance").addClass("error-border");
        //                 hasError = true;
        //                 return false;
        //             }
        //         }

        //         if (type !== "traveling" && type !== "holiday" && type !== "leave" && type !== "weekly_off") {
        //             if (!amount) {
        //                 toastr.error("Some amount fields are empty!");
        //                 row.find(".amount").addClass("error-border");
        //                 hasError = true;
        //                 return false;
        //             }
        //         }

        //         // ADD TO FORM DATA
        //         formData.append(`expenses[${rowIndex}][id]`, row.find(".row-id").val());
        //         formData.append(`expenses[${rowIndex}][date]`, row.find(".exp-date").val());
        //         formData.append(`expenses[${rowIndex}][type]`, type);
        //         formData.append(`expenses[${rowIndex}][distance]`, distance);
        //         formData.append(`expenses[${rowIndex}][amount]`, amount);
        //         formData.append(`expenses[${rowIndex}][hq_allow]`, hq);
        //         formData.append(`expenses[${rowIndex}][ex_stn_allow]`, ex);
        //         formData.append(`expenses[${rowIndex}][out_stn_allow]`, out);
        //         formData.append(`expenses[${rowIndex}][bus_ticket]`, bus);
        //         formData.append(`expenses[${rowIndex}][total]`, row.find(".total").val());

        //         let fileInput = row.find(".file")[0];
        //         if (fileInput && fileInput.files.length > 0) {
        //             formData.append(`expenses[${rowIndex}][file]`, fileInput.files[0]);
        //         }

        //         rowIndex++;
        //     });

        //     if (hasError) {
        //         spinner.addClass("d-none");
        //         btn.prop("disabled", false).text("Submit All");
        //         return;
        //     }

        //     // SUBMIT FORM DATA REQUEST
        //     $.ajax({
        //         url: "{{ route('employee-expense.store') }}",
        //         type: "POST",
        //         data: formData,
        //         processData: false,
        //         contentType: false,
        //         success: function() {
        //             toastr.success("All expenses saved!");
        //             generateMonthRows(month);
        //         },
        //         error: function() {
        //             toastr.error("Error saving!");
        //         },
        //         complete: function() {
        //             spinner.addClass("d-none");
        //             btn.prop("disabled", false).text("Submit All");
        //         }
        //     });

        // });


        $("#submitAll").click(function() {

            let btn = $(this);
            let spinner = $("#submitSpinner");

            let month = $("#monthPicker").val();
            if (!month) return toastr.error("Select month first!");

            if (!confirm("Submit all expenses for this month?")) return;

            spinner.removeClass("d-none");
            btn.prop("disabled", true).text("Submitting...");

            let formData = new FormData();

            formData.append("_token", "{{ csrf_token() }}");
            formData.append("employee_id", "{{ auth()->user()->reference_id }}");

            let hasError = false;
            let rowIndex = 0;

            $("#expenseBody tr").each(function() {
                let row = $(this);
                row.find("input, select").removeClass("error-border");

                let type = row.find(".expense-type").val();
                let amount = row.find(".amount").val();
                let hq = row.find(".hq_allow").val();
                let ex = row.find(".ex_stn_allow").val();
                let out = row.find(".out_stn_allow").val();
                let bus = row.find(".bus_ticket").val();
                let distance = row.find(".distance").val();

                if (!type) {
                    toastr.error("Some rows have empty Expense Type!");
                    row.find(".expense-type").addClass("error-border");
                    hasError = true;
                    return false;
                }

                if (type === "traveling") {
                    if (!hq && !ex && !out && !bus || !distance) {
                        toastr.error("Some Traveling rows are incomplete!");
                        if (!hq) row.find(".hq_allow").addClass("error-border");
                        if (!ex) row.find(".ex_stn_allow").addClass("error-border");
                        if (!out) row.find(".out_stn_allow").addClass("error-border");
                        if (!bus) row.find(".bus_ticket").addClass("error-border");
                        if (!distance) row.find(".distance").addClass("error-border");
                        hasError = true;
                        return false;
                    }
                }

                if (type !== "traveling" && type !== "holiday" && type !== "leave" && type !==
                    "weekly_off" && type !== "sunday_claims") {
                    if (!amount) {
                        toastr.error("Some amount fields are empty!");
                        row.find(".amount").addClass("error-border");
                        hasError = true;
                        return false;
                    }
                }

                // ADD TO FORM DATA
                formData.append(`expenses[${rowIndex}][id]`, row.find(".row-id").val());
                formData.append(`expenses[${rowIndex}][date]`, row.find(".exp-date").val());
                formData.append(`expenses[${rowIndex}][type]`, type);
                formData.append(`expenses[${rowIndex}][distance]`, distance);
                formData.append(`expenses[${rowIndex}][travel_from]`, row.find(".travel_from")
                    .val());
                formData.append(`expenses[${rowIndex}][travel_to]`, row.find(".travel_to")
                    .val());
                formData.append(`expenses[${rowIndex}][working_type]`, row.find(".working-type")
                    .val());
                formData.append(`expenses[${rowIndex}][amount]`, amount);
                formData.append(`expenses[${rowIndex}][hq_allow]`, hq);
                formData.append(`expenses[${rowIndex}][ex_stn_allow]`, ex);
                formData.append(`expenses[${rowIndex}][out_stn_allow]`, out);
                formData.append(`expenses[${rowIndex}][bus_ticket]`, bus);
                formData.append(`expenses[${rowIndex}][total]`, row.find(".total").val());

                let fileInput = row.find(".file")[0];
                if (fileInput && fileInput.files.length > 0) {
                    formData.append(`expenses[${rowIndex}][file]`, fileInput.files[0]);
                }

                rowIndex++;
            });

            if (hasError) {
                spinner.addClass("d-none");
                btn.prop("disabled", false).text("Submit All");
                return;
            }

            // SUBMIT FORM DATA REQUEST
            $.ajax({
                url: "{{ route('employee-expense.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function() {
                    toastr.success("All expenses saved!");
                    generateMonthRows(month);
                },
                error: function() {
                    toastr.error("Error saving!");
                },
                complete: function() {
                    spinner.addClass("d-none");
                    btn.prop("disabled", false).text("Submit All");
                }
            });

        });


        //----------------------------
        // LOAD INITIAL MONTH
        //----------------------------
        let today = new Date();
        let cur = today.toISOString().slice(0, 7);
        $("#monthPicker").val(cur);
        generateMonthRows(cur);


        $("#monthPicker").change(function() {
            generateMonthRows($(this).val());
        });

        $(document).on("click", ".deleteRow", function() {

            let row = $(this).closest("tr");
            let id = row.find(".row-id").val();

            if (!confirm("Are you sure? This row will be deleted!")) {
                return;
            }

            if (!id) {
                row.remove();
                toastr.success("Row removed successfully");
                return;
            }

            // 🔥 If row is saved → delete from DB
            $.ajax({
                url: "/employee-expense/" + id,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    toastr.success("Deleted successfully");
                    row.remove();
                },
                error: function() {
                    toastr.error("Error deleting!");
                }
            });

        });

        $(document).on("click", ".viewReason", function() {

            let reason = $(this).data("reason");

            if (!reason || reason === "") {
                reason = "No reason provided.";
            }

            $("#rejectReasonText").text(reason);
            $("#rejectModal").modal("show");
        });


        $(document).on('keydown', '.distance, .amount, .hq_allow, .ex_stn_allow, .out_stn_allow, .bus_ticket',
            function(e) {
                if (e.key === '-' || e.key === 'e' || e.key === '+') {
                    e.preventDefault();
                }
            });

        // File select hone par name show karo
        $(document).on('change', '.file', function() {

            let file = this.files[0];
            let row = $(this).closest("td");

            if (file) {
                let fileName = file.name;

                // 🔥 Short name logic (max 20 char)
                let shortName = fileName.length > 20 ?
                    fileName.substring(0, 10) + '...' + fileName.slice(-7) :
                    fileName;

                row.find('.file-name').text(shortName);
            }
        });
    });
</script>
@endpush