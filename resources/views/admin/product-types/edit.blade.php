<div class="modal-dialog modal-dialog-centered" style="max-width:550px;">
    <form class="modal-content bg-white" id="editProductTypeForm">
        @csrf
        @method('PUT')

        <input type="hidden" id="class_route" value="{{ route('product-types.update', $productType->id) }}">

        <div class="modal-header p-20">
            <h5 class="modal-title">Edit Product Type</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body p-20 pb-0">

            <div class="mb-20">
                <label class="label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" value="{{ $productType->name }}">
            </div>



            <div class="mb-20">
                <label class="label">Description</label>
                <input type="text" class="form-control" name="description" value="{{ $productType->description }}">
            </div>


            <div class="mb-20">
                <label class="label">Status</label>
                <select class="form-select form-control" name="status">
                    <option value="1" {{ $productType->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $productType->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

        </div>

        <div class="modal-footer p-20 pt-0 border-0">
            <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">
                Cancel
            </button>
            <button type="submit" class="btn btn-primary text-white">
                Update
            </button>
        </div>
    </form>
</div>
