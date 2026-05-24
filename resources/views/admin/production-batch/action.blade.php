
<div class="d-flex justify-content-center align-items-center" style="gap: 12px;">
    <!-- Edit Button -->
   
@if($row->status === 'REJECTED' || $row->status === 'DRAFT')
    <a href="{{ route('production-batch.edit',  encrypt($row->id)  )}}" 
       class="bg-transparent p-0 border-0 hover-text-success" 
       title="Edit">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
    </a>
  @endif
   
    <a href="{{ route('production-batch.show', encrypt($row->id)) }}" 
        class="bg-transparent p-0 border-0 hover-text-success" title="View Requisition Batch Details">
         <em class="fas fa-eye  font-16"></em>
     </a>


@if($row->status === 'PENDING_CHEMIST')
   <a href="{{ route('createAssignTeam', $row->id) }}?module=Requisition"

        class="bg-transparent p-0 border-0 hover-text-success "
      
        title="Assign Team">
            <em class="fas fa-users font-16"></em>
    </a>
@endif

     
</div>

