<div class="d-flex justify-content-center" style="gap: 12px;">
    {{-- @can('edit-employee-holiday') --}}
     <a href="{{ route('employee-holiday.edit', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success">
         <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
     </a>
     {{-- @endcan --}}


{{-- <a href="javascript:void(0);" 
   class="deleteBranchBtn" 
   data-id="{{ encrypt($row->id) }}">
    <i class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
</a> --}}

 </div>
