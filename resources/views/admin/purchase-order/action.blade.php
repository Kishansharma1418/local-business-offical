
<div class="d-flex justify-content-center" style="gap: 12px;">
    @role('admin')
     <a href="{{ route('purchase-order.edit', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success">
         <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
     </a>
     @endrole
       
   <a href="{{ route('purchase-order.show', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success">
        <em class="fas fa-eye  font-16"></em>
    </a>
     <a href="{{route('purchase-order.pdf', encrypt($row->id))}}">
            <i class="material-symbols-outlined fs-16 text-body hover-text-danger">picture_as_pdf</i>
     </a>

 </div>
