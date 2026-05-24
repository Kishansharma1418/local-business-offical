<div class="d-flex justify-content-center align-items-center" style="gap: 12px;">
    <!-- Edit Button -->
    {{-- @can('edit-product-category') --}}
    <a href="{{ route('rawmaterial.edit', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success"
        title="Edit">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
    </a>
    {{-- @endcan --}}

    {{-- <a href="javascript:void(0);" 
        class="bg-transparent p-0 border-0 hover-text-danger deleteBranchBtn" 
        data-id="{{ $row->id }}"
        title="Delete">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
    </a> --}}
    {{-- <a href="{{ route('rawmaterial.show', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success">
        <em class="fas fa-eye  font-16"></em>
    </a> --}}

    <a href="{{ route('raw-material-batch.index', ['raw_material_id' => encrypt($row->id)]) }}"
        class="bg-transparent p-0 border-0 hover-text-primary" title="View Batch">

        <i class="material-symbols-outlined fs-16 fw-normal text-body">
            inventory_2
        </i>

    </a>
    {{-- <a href="{{ route('rawmaterial.show', encrypt($row->id)) }}" title="View Batch">

        <i class="material-symbols-outlined fs-16 fw-normal text-body">
            inventory
        </i>

    </a> --}}
</div>
