<div class="dropdown d-flex justify-content-center align-items-center">
    <button class="bg-transparent border-0 p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="material-symbols-outlined fs-18 text-body">more_vert</i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2 bg-white">
        <li>
            {{-- @can('edit-finished-goods') --}}
                <a href="{{ route('finished-good.edit', encrypt($row->id)) }}"
                    class="dropdown-item  d-flex align-items-center gap-2" title="Edit">
                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                    <span>Edit</span>
                </a>
            {{-- @endcan --}}
        </li>

<li>
    <a href="{{ route('finished-good.show', encrypt($row->id)) }}"
        class="dropdown-item d-flex align-items-center gap-2"
        title="View Batch">

        <em class="fas fa-layer-group font-16 text-body"></em>
        <span>View Batch</span>
    </a>
</li>
        <li>
            {{-- <a href="javascript:void(0);" 
       class="dropdown-item d-flex align-items-center gap-2  deleteBranchBtn" 
       data-id="{{ $row->id }}" 
       title="Delete Employee">
        <i class="material-symbols-outlined fs-16 fw-normal" >delete</i>
        <span>Delete</span>
    </a> --}}
        </li>
        <li>
            {{-- @can('add-product') --}}
                <a href="{{ route('product-details.create', encrypt($row->id)) }}"
                    class="dropdown-item  d-flex align-items-center gap-2" title="Add Product Details">
                    <em class="fas fa-add font-16 text-body"></em>
                    <span>Product detail</span>
                </a>
            {{-- @endcan --}}
        </li>

        {{-- <li>
            <a href="{{ route('finished-good.show', encrypt($row->id)) }}"
                class="dropdown-item  d-flex align-items-center gap-2" title="View Product Details">
                <em class="fas fa-eye font-16 text-body"></em>
                <span>View Product</span>
            </a>
        </li> --}}

        <li>
          
                <a href="{{ route('batch-details.create', encrypt($row->id)) }}"
                    class="dropdown-item  d-flex align-items-center gap-2" title="Add Product Details">
                    <em class="fas fa-add font-16 text-body"></em>
                    <span>Add Batch</span>
                </a>
           
        </li>

    </ul>
</div>
