        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
            <form class="modal-content bg-white" method="POST" enctype="multipart/form-data" id="editBankForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="class_route" value="{{ route('bank-details.update', $bank->id) }}">

                <div class="modal-header border-border-color-40 p-20">
                    <h1 class="modal-title fs-18 fw-medium mb-0" id="editBankModalLabel">Edit Bank</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-20 pb-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-20">
                                <label class="label">Bank Name <span class="text-danger">*</span></label>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingInput" name="name"
                                        placeholder="Bank Name" data-rule-required="true" value="{{ old('name', $bank->name) }}">
                                    <label for="floatingInput">Bank Name</label>
                                </div>
                            </div>
                        </div>

                     

                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Status</label>
                            <select class="form-select form-control" name="status">
                                <option value="1" {{ old('status', $bank->status) == '1' ? 'selected' : '' }}>Active
                                </option>
                                <option value="0" {{ old('status', $bank->status) == '0' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 p-20 pt-0">
                    <button type="button" class="btn btn-danger fw-normal text-white"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-normal text-white">Update</button>
                </div>
            </form>

        </div>
