
<div class="d-flex justify-content-center align-items-center" style="gap: 12px;">

    <a href="{{ route('credit-notes.edit', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success" 
       title="Edit">
        <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
    </a>
   
    <a href="{{ route('credit-notes.show', encrypt($row->id)) }}" 
        class="bg-transparent p-0 border-0 hover-text-success" title="View Credit Note Details">
         <em class="fas fa-eye  font-16"></em>
     </a>

     @if($row->status != 'closed')
        <a href="javascript:void(0)"
            class="openRefundModal text-warning"
            title="Refund"
            data-credit-note-id="{{ $row->id }}"
            data-balance-due="{{ $row->balance_due }}"
            data-credit-note-number="{{ $row->credit_note_number }}"
            data-customer-id="{{ $row->customer_id }}"
            data-customer-name="{{ $row->customer->name ?? '-' }}"
            data-amount="{{ $row->net_amount }}">
                <i class="ri-refund-2-line fs-18"></i>
        </a>
    @endif
      
</div>  