<div class="d-flex justify-content-center" style="gap: 12px;">
     {{-- @can('edit-gst') --}}
     <a href="{{ route('tds.edit', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success">
         <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
     </a>
        {{-- @endcan --}}

  {{-- <a href="javascript:void(0);" 
        class="bg-transparent p-0 border-0 hover-text-danger deleteBranchBtn" 
        data-id="{{ $row->id }}"
        title="Delete">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
    </a> --}}
     <a href="{{ route('tds.show', encrypt($row->id)) }}" 
        class="bg-transparent p-0 border-0 hover-text-success"title="View Overtime Details">
         <em class="fas fa-eye  font-16"></em>
     </a>
 </div>
