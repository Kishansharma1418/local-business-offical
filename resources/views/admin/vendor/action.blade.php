
<div class="d-flex justify-content-center" style="gap: 12px;">
     <a href="{{ route('vendor.edit', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success">
         <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
     </a>

  <a href="{{ route('vendor.show', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success">
        <em class="fas fa-eye  font-16"></em>
    </a>
 </div>
