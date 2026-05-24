{{-- <a href="javascript:void(0);" 
   class="me-2 text-primary editBranchBtn" 
   data-id="{{ $row->id }}" 
   data-branchcode="{{ $row->branch_code }}"
   data-branchname="{{ $row->branch_name }}"
   data-status="{{ $row->status }}"
   title="Edit">
   <i class="ri-edit-2-fill fs-5"></i>
</a>
<div class="d-flex justify-content-center" style="gap: 12px;">
        <a href="javascript:void(0);" data-route="{{ route('bank-details.edit', $row->id) }}" id="edit_value"
                class="bg-transparent p-0 border-0 hover-text-success">
                <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
            </a>


 </div> --}}
{{-- <a href="javascript:void(0);" 
   class="text-danger deleteBranchBtn" 
   data-id="{{ $row->id }}" 
   title="Delete">
   <i class="ri-delete-bin-5-fill fs-5"></i>
</a> --}}


<div class="d-flex justify-content-center align-items-center" style="gap: 12px;">
    <!-- Edit Button -->
    {{-- @can('edit-branch') --}}
    <a href="{{ route('branches.edit',  encrypt($row->id)  )}}" 
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
    <a href="{{ route('branches.show', encrypt($row->id)) }}" 
        class="bg-transparent p-0 border-0 hover-text-success"title="View Branch Details">
         <em class="fas fa-eye  font-16"></em>
     </a>
</div>
