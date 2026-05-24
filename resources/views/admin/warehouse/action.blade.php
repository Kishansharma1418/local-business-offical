<div class="d-flex justify-content-center align-items-center" style="gap: 12px;">
    <!-- Edit Button -->
    {{-- @can('edit-warehouse') --}}
    <a href="{{ route('warehouse.edit',  encrypt($row->id)  )}}" 
       class="bg-transparent p-0 border-0 hover-text-success" 
       title="Edit">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
    </a>
    {{-- @endcan --}}
    {{-- <a href="javascript:void(0);" 
        class="bg-transparent p-0 border-0 hover-text-danger deleteDocumentBtn" 
        data-id="{{ $row->id }}"
        title="Delete">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
    </a>
    --}}
     
  <a href="{{ route('warehouse.show', encrypt($row->id)) }}" 
    class="bg-transparent p-0 border-0 hover-text-success"title="View Warehouse Details">
         <em class="fas fa-eye  font-16"></em>
     </a>

</div>
