
<div class="d-flex justify-content-center align-items-center" style="gap: 12px;">

    <a href="{{ route('invoice-orders.edit', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success" 
       title="Edit">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
    </a>
   
    <a href="{{ route('invoice-orders.show', encrypt($row->id)) }}" 
        class="bg-transparent p-0 border-0 hover-text-success" title="View Sales Order Details">
         <em class="fas fa-eye  font-16"></em>
     </a>

      
</div>