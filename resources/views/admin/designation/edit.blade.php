        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
            <form class="modal-content bg-white" method="POST" enctype="multipart/form-data" id="edit_uom_form">
                @csrf
                @method('PUT')
                <input type="hidden" id="class_route" value="{{ route('designation.update', $designation->id) }}">

                <div class="modal-header border-border-color-40 p-20">
                    <h1 class="modal-title fs-18 fw-medium mb-0" id="exampleModalLabel">Edit Designation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-20 pb-0">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-20">
                                <label class="label">Designation Name <span class="text-danger">*</span></label>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingInput" name="name"
                                        placeholder="Designation Name" data-rule-required="true"
                                        value="{{ old('name', $designation->name) }}">
                                    <label for="floatingInput">Designation Name</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-20">
                                <label class="label">Designation Code <span class="text-danger">*</span></label>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingInput" name="code"
                                        value="{{ old('code', $designation->code) }}" placeholder="Designation Code"
                                        data-rule-required="true">
                                    <label for="floatingInput">Designation Code</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Department<span class="text-danger">*</span></label>
                            <select name="department_id" data-rule-required="true" class="form-select form-control">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id', $designation->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-20">
                                <label class="label"> Description</label>
                                <div class="form-floating">
                                    <textarea name="description" class="form-control" id="description" cols="3" rows="16"
                                        placeholder="Example: Multiple strips/tablets in one box">{{ old('description', $designation->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Status</label>
                            <select class="form-select form-control" name="status">
                                <option value="1"
                                    {{ old('status', $designation->status) == '1' ? 'selected' : '' }}>Active
                                </option>
                                <option value="0"
                                    {{ old('status', $designation->status) == '0' ? 'selected' : '' }}>
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

