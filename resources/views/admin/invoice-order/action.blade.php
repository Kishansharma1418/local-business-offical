<div class="d-flex justify-content-center align-items-center" style="gap: 12px;">

    <a href="javascript:void(0)" class="open-invoice-edit" data-id="{{ $row->id }}"
        data-url="{{route('invoice-orders.edit', encrypt($row->id))}}">
        <i class="material-symbols-outlined fs-16">edit</i>
    </a>

    <a href="{{ route('invoice-orders.show', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success"
        title="View Invoice Order Details">
        <em class="fas fa-eye  font-16"></em>
    </a>

</div>
