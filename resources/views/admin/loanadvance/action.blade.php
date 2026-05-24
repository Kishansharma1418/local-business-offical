<div class="d-flex justify-content-center" style="gap: 12px;">
    {{-- @can('edit-employee-holiday') --}}
     {{-- <a href="{{ route('loan-advances.edit', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success">
         <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
     </a> --}}
     {{-- @endcan --}}
   <a href="{{ route('loan-advances.show', encrypt($row->id)) }}" 
        class="bg-transparent p-0 border-0 hover-text-success"title="View Branch Details">
         <em class="fas fa-eye  font-16"></em>
     </a>
 </div>

