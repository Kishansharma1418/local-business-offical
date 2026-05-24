<div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
    <form class="modal-content bg-white" method="POST" enctype="multipart/form-data" id="editTermForm">
        @csrf
        @method('PUT')

        <input type="hidden" id="class_route" value="{{ route('payment-terms.update', $term->id) }}">

        <div class="modal-header border-border-color-40 p-20">
            <h1 class="modal-title fs-18 fw-medium mb-0">Edit Payment Terms</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body p-20 pb-0">
            <div class="row">

                {{-- Name --}}
                <div class="col-lg-12 mb-20">
                    <label class="label">Name <span class="text-danger">*</span></label>
                    <div class="form-floating">
                        <input type="text" class="form-control" name="name" placeholder="Payment Term Name"
                            data-rule-required="true" value="{{ old('name', $term->name) }}">
                        <label>Payment Term Name</label>
                    </div>
                </div>

                {{-- Days --}}
                <div class="col-lg-12 mb-20">
                    <label class="label">Days <span class="text-danger">*</span></label>
                    <div class="form-floating">
                        <input type="number" class="form-control days" name="days" placeholder="Enter Days"
                            min="0" data-rule-required="true" value="{{ old('days', $term->days) }}">
                        <label>Days</label>
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-lg-12 mb-20">
                    <label class="label fs-16 mb-2">Status</label>
                    <select class="form-select form-control" name="status">
                        <option value="1" {{ old('status', $term->status) == '1' ? 'selected' : '' }}>Active
                        </option>
                        <option value="0" {{ old('status', $term->status) == '0' ? 'selected' : '' }}>Inactive
                        </option>
                    </select>
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
    <script type="text/javascript">
        $(document).ready(function() {
            
            $(document).on('keydown', '.days',
                function(e) {
                    if (e.key === '-' || e.key === 'e' || e.key === '+') {
                        e.preventDefault();
                    }
                });

        });
    </script>
