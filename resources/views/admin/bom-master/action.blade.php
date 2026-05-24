
<div class="d-flex justify-content-center align-items-center" style="gap: 12px;">

@if($row->status == 0)
    <a href="{{ route('bom-master.edit',  encrypt($row->id)  )}}" 
       class="bg-transparent p-0 border-0 hover-text-success" 
       title="Edit">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
    </a>
    @endif
   
    <a href="{{ route('bom-master.show', encrypt($row->id)) }}" 
        class="bg-transparent p-0 border-0 hover-text-success" title="View BOM Master Details">
         <em class="fas fa-eye  font-16"></em>
     </a>

     <a href="{{route('production-process.create', encrypt($row->id))}}">
        <i class="material-symbols-outlined fs-16 fw-normal text-body" title="Add Production Process">playlist_add</i>
     </a>
     
</div>

