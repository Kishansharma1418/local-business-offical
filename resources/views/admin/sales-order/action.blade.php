<div class="d-flex justify-content-center align-items-center" style="gap: 12px;">

    <a href="javascript:void(0)" class="open-sales-edit" data-id="{{ $row->id }}"
        data-url="{{ route('sale-orders.edit', encrypt($row->id)) }}">
          <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
    </a>


    <a href="{{ route('sale-orders.show', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success"
        title="View Sales Order Details">
       <em class="fas fa-eye  font-16"></em>
    </a>


</div>
