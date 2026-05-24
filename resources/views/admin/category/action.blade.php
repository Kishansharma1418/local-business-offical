{{-- <div class="d-flex justify-content-center align-items-center gap-2">
    <!-- Edit Button -->
    <a href="{{ route('departments.edit', $row->id) }}" class="text-primary" title="Edit">
        <i class="ri-edit-2-fill fs-5"></i>
    </a>

    <!-- Delete Button -->
    <form action="{{ route('departments.destroy', $row->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link p-0 m-0" onclick="return confirm('Are you sure you want to delete this department?')">
            <i class="ri-delete-bin-5-fill fs-5 text-danger"></i>
        </button>
    </form>
</div> --}}
<div class="d-flex justify-content-center align-items-center" style="gap: 12px;">
    <!-- Edit Button -->
    {{-- @can('edit-product-category') --}}
    <a href="{{ route('category.edit',  encrypt($row->id)  )}}" 
       class="bg-transparent p-0 border-0 hover-text-success" 
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
    <a href="{{ route('category.show', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success">
         <em class="fas fa-eye  font-16"></em>
     </a>
</div>

