<style>
    .hover-bg-light:hover {
        background-color: #f5f6f7 !important;
    }

    .dropdown-menu .dropdown-item i {
        width: 18px;
        text-align: center;
    }

    .dropdown-menu hr.dropdown-divider {
        margin: 6px 0;
        border-color: #eaeaea;
    }
</style>

<div class="dropdown d-flex justify-content-center align-items-center">
    <button class="bg-transparent border-0 p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="material-symbols-outlined fs-20 text-body">more_vert</i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 py-2" style="min-width: 210px;">
        <li class="px-3 text-muted small fw-semibold">Customer</li>

        <!-- ✏️ Edit -->
        <li>
            {{-- @can('edit-customer-detail') --}}
                <a href="{{ route('customers.edit', encrypt($row->id)) }}"
                    class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 hover-bg-light"
                    title="Edit Customer">
                    <i class="material-symbols-outlined fs-17 text-primary">edit</i>
                    <span>Edit Details</span>
                </a>
            {{-- @endcan --}}
        </li>

        <!-- 👁️ View -->
        <li>
            <a href="{{ route('customers.show', encrypt($row->id)) }}"
                class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 hover-bg-light"
                title="View Customer">
                <i class="fas fa-eye text-success fs-14"></i>
                <span>View Profile</span>
            </a>
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>

        <li class="px-3 text-muted small fw-semibold">Related Data</li>

        

          <li>
            <a href="javascript:void(0)" 
               class="dropdown-item customerOutstandingBtn"
               data-customer-id="{{ $row->id }}"
               data-due="{{ $row->due_invoices_count }}">
                <i class="fas fa-file text-warning"></i> Customer Outstanding
            </a>
        </li>


        <li>
            {{-- @can('view-customer-contact') --}}
                <a href="{{ route('customer.contactlist.index', $row->id) }}"
                    class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 hover-bg-light"
                    title="Add Customer Contact">
                    <i class="fas fa-phone text-warning fs-14"></i>
                    <span>Add Contact</span>
                </a>
            {{-- @endcan --}}
        </li>

        <li>
            {{-- @can('add-customer-discount') --}}
                <a href="{{ route('customer-product-discount.create', encrypt($row->id)) }}"
                    class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 hover-bg-light"
                    title="Add Customer Discount">
                    <i class="fas fa-tag text-success fs-14"></i>
                    <span>Add Customer Discount</span>
                </a>
            {{-- @endcan --}}
        </li>

        {{-- <li><hr class="dropdown-divider"></li> --}}

        {{-- <li class="px-3 text-muted small fw-semibold">Actions</li> --}}

        <!-- 🗑️ Delete (optional) -->
        {{-- <li>
            <a href="javascript:void(0);" 
               class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 hover-bg-light text-danger deleteCustomerBtn" 
               data-id="{{ $row->id }}"
               title="Delete Customer">
                <i class="fas fa-trash-alt"></i>
                <span>Delete Customer</span>
            </a>
        </li> --}}
    </ul>
</div>
  
