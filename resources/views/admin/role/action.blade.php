 <div class="d-flex justify-content-center" style="gap: 12px;">

    <a href="javascript:void(0);" data-route="{{ route('roles.edit', $row->id) }}" id="edit_value"
                class="bg-transparent p-0 border-0 hover-text-success">
                <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
            </a>

      <a href="{{ route('assignPermission',$row->id)}}"
            ><em class="fas fa-add  font-16"></em></a>
 {{-- <a href="javascript:void(0);" 
        class="bg-transparent p-0 border-0 hover-text-danger deleteBranchBtn" 
        data-id="{{ $row->id }}"
        title="Delete">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
    </a> --}}

 </div>
