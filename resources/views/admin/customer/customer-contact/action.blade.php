<div class="d-flex justify-content-center" style="gap: 12px;">
    {{-- @can('edit-customer-contact') --}}
    <a href="{{ route('customer.contactedit.edit', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
    </a>
    {{-- @endcan --}}
    
      {{-- <a href="javascript:void(0);" 
        class="bg-transparent p-0 border-0 hover-text-danger deleteBranchBtn" 
        data-id="{{ $row->id }}"
        title="Delete">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
    </a> --}}
   
</div>
