<div class="d-flex justify-content-center" style="gap: 12px;">

    {{-- @can('edit-broker') --}}
    <a href="{{ route('brokers.edit', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success"
        title="Edit Broker">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
    </a>
  
     <a href="{{ route('brokers.show', encrypt($row->id)) }}" 
        class="bg-transparent p-0 border-0 hover-text-success" title="View Batch Details">
         <em class="fas fa-eye  font-16"></em>
     </a>
    {{-- @endcan --}}

    {{-- @can('delete-broker') --}}
    {{--
    <a href="javascript:void(0);"
       class="bg-transparent p-0 border-0 hover-text-danger deleteBrokerBtn"
       data-id="{{ $row->id }}"
       title="Delete Broker">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
    </a>
    --}}
    {{-- @endcan --}}

</div>
