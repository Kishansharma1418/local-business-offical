
<div class="d-flex justify-content-center align-items-center" style="gap: 12px;">
    <!-- Edit Button -->
  
    @if(auth()->user()->hasRole('Store') && $row->status === 'PENDING_STORE')
    <a href="{{ route('store-issurance.edit',  encrypt($row->id)  )}}" 
       class="bg-transparent p-0 border-0 hover-text-success" 
       title="Edit">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
    </a>
    @endif
   
    <a href="{{ route('store-issurance.show', encrypt($row->id)) }}" 
        class="bg-transparent p-0 border-0 hover-text-success" title="View Store Issurance Details">
         <em class="fas fa-eye  font-16"></em>
     </a>

      <!-- assign team -->
    <a href="{{ route('createAssignTeam', $row->id) }}?module=Store" 

        class="bg-transparent p-0 border-0 hover-text-success "
      
        title="Assign Team">
            <em class="fas fa-users font-16"></em>
    </a>

     
</div>

