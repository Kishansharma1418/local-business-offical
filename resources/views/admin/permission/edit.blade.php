        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
            <form class="modal-content bg-white" method="Post" enctype="multipart/form-data" id="edit_permission_form">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_route" value="{{ route('permission.update', $permisison->id) }}">
                <div class="modal-header border-border-color-40 p-20">
                    <h1 class="modal-title fs-18 fw-medium mb-0" id="editModalLabel">Edit Permission</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-20 pb-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-20">
                                <label class="label">Permisison Name</label>
                                <div class="form-floating">
                                    <input type="name" class="form-control" id="floatingInput" name="name"
                                        placeholder="permisison Name" value="{{ $permisison->name }}" data-rule-required="true">
                                    <label for="floatingInput">permisison Name</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-12">
                            <div class="mb-20">
                                <label class="label">Main Group</label>
                                <div class="form-floating">
                                    <input type="name" class="form-control" id="floatingInput" name="main_group" value="{{$permisison->main_group}}"
                                        placeholder="Main Group" data-rule-required="true">
                                    <label for="floatingInput">Main Group</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="mb-20">
                                <label class="label">Sub Group</label>
                                <div class="form-floating">
                                    <input type="name" class="form-control" id="floatingInput" name="sub_group" value="{{$permisison->sub_group}}"
                                        placeholder="Main Group" data-rule-required="true">
                                    <label for="floatingInput">Sub Group</label>
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
