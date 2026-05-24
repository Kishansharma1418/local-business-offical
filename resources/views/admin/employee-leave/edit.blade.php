<div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 600px;">
    <form class="modal-content bg-white" method="POST" id="editLeaveForm">
        @csrf
        @method('PUT')
        <input type="hidden" id="update_route" value="{{ route('leaves.update', $leave->id) }}">

        <div class="modal-header border-border-color-40 p-20">
            <h1 class="modal-title fs-18 fw-medium mb-0">Edit Leave</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body p-20 pb-0">
            <div class="row">

             @if(auth()->user()->hasRole('admin'))
            <div class="col-lg-6 mb-20">
                    <label class="label">Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-control" data-rule-required="true">
                        <option value="">Select Employee</option>
                        @foreach($employee as $emp)
                        <option value="{{ $emp->id }}" {{ $leave->employee_id == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                
                <div class="col-lg-6 mb-20">
                    <label class="label">Leave Category <span class="text-danger">*</span></label>
                    <select name="leave_category" class="form-control" data-rule-required="true">
                        <option value="medical" {{ $leave->leave_category == 'medical' ? 'selected' : '' }}>Medical
                        </option>
                        <option value="casual" {{ $leave->leave_category == 'casual' ? 'selected' : '' }}>Casual
                        </option>
                        <option value="paid" {{ $leave->leave_category == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="work from home"
                            {{ $leave->leave_category == 'work from home' ? 'selected' : '' }}>Work From Home</option>
                    </select>
                </div>

                <div class="col-lg-6 mb-20">
                    <label class="label">Leave Type</label>
                    <select name="leave_type"class="form-control" data-rule-required="true">
                        <option value="full day" {{ $leave->leave_type == 'full day' ? 'selected' : '' }}>Full Day
                        </option>
                        <option value="half day" {{ $leave->leave_type == 'half day' ? 'selected' : '' }}>Half Day
                        </option>
                    </select>
                </div>

             <div class="col-lg-6 mb-20">
                    <label class="label">Leave Date Range <span class="text-danger">*</span></label>
                    <input type="text" name="date_range" id="edit_date_range" class="form-control"
                        value="{{ old('date_range', $leave->start_date && $leave->end_date ? \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') : '') }}"
                        required>
                </div>



                <div class="col-lg-12 mb-20">
                    <label class="label">Description</label>
                    <textarea name="description" class="form-control" rows="2">{{ $leave->description }}</textarea>
                </div>

              
            </div>
        </div>

        <div class="modal-footer border-0 p-20 pt-0">
            <button type="button" class="btn btn-danger fw-normal text-white" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary fw-normal text-white">Update</button>
        </div>
    </form>
</div>
@push('scripts')
<!-- Required JS Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- Daterangepicker Initialization -->
<script>
$(document).ready(function () {
    $('#edit_date_range').daterangepicker({
        autoApply: true,
     minDate: moment().add(1, 'days').startOf('day'),
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    // ✅ Simple form validation (like holiday page)
    $('#editLeaveForm').on('submit', function(e) {
        const dateRange = $('#edit_date_range').val().trim();
        const category = $('select[name="leave_category"]').val();
        let isValid = true;

        if (dateRange === '') {
            $('#edit_date_range').addClass('is-invalid').removeClass('is-valid');
            isValid = false;
        } else {
            $('#edit_date_range').addClass('is-valid').removeClass('is-invalid');
        }

        if (!category) {
            $('select[name="leave_category"]').addClass('is-invalid').removeClass('is-valid');
            isValid = false;
        } else {
            $('select[name="leave_category"]').addClass('is-valid').removeClass('is-invalid');
        }

        if (!isValid) {
            e.preventDefault();
        } else {
            $(this).find('button[type="submit"]').prop('disabled', true).text('Processing...');
        }
    });
});
</script>
@endpush