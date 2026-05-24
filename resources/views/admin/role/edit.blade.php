        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
            <form class="modal-content bg-white" method="Post" enctype="multipart/form-data" id="edit_role_form">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_route" value="{{ route('roles.update', $roles->id) }}">
                <div class="modal-header border-border-color-40 p-20">
                    <h1 class="modal-title fs-18 fw-medium mb-0" id="editModalLabel">Edit Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-20 pb-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-20">
                                <label class="label">Role Name</label>
                                <div class="form-floating">
                                    <input type="name" class="form-control" id="floatingInput" name="name"
                                        placeholder="Role Name" value="{{ $roles->name }}" data-rule-required="true">
                                    <label for="floatingInput">Role Name</label>
                                </div>
                            </div>
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
