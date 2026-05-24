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
        <li class="px-3 text-muted small fw-semibold">Employee</li>

        <li>
            {{-- @can('edit-employee-detail') --}}
            <a href="{{ route('employee.edit', encrypt($row->id)) }}"
                class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 hover-bg-light"
                title="Edit Employee">
                <i class="material-symbols-outlined fs-17 text-primary">edit</i>
                <span>Edit Details</span>
            </a>
            {{-- @endcan --}}
        </li>

        <li>
            <a href="{{ route('employee.show', encrypt($row->id)) }}"
                class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 hover-bg-light"
                title="View Employee">
                <i class="fas fa-eye text-success fs-14"></i>
                <span>View Profile</span>
            </a>
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>

        <li class="px-3 text-muted small fw-semibold">Finance</li>

        <li>
            {{-- @can('add-bank-detail') --}}
            <a href="{{ route('employee.bank.create', encrypt($row->id)) }}"
                class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 hover-bg-light"
                title="Add Bank Details">
                <i class="fas fa-university text-info fs-14"></i>
                <span>Bank Details</span>
            </a>
            {{-- @endcan --}}
        </li>

        <li>
            {{-- @can('view-salary-revision') --}}
            <a href="{{ route('employee.revisionsalarylist.index', $row->id) }}"
                class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 hover-bg-light"
                title="Salary Revision">
                <i class="fas fa-rupee-sign text-warning fs-14"></i>
                <span>Salary Revision</span>
            </a>
            {{-- @endcan --}}
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>

        <li class="px-3 text-muted small fw-semibold">Additional</li>

        <li>
            {{-- @can('add-employee-document') --}}
            <a href="{{ route('employee.document.create', encrypt($row->id)) }}"
                class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 hover-bg-light"
                title="Add Documents">
                <i class="fas fa-file-alt text-secondary fs-14"></i>
                <span>Documents</span>
            </a>
            {{-- @endcan --}}
        </li>
        <li>
            <a href="{{ route('employee.assets.index', encrypt($row->id)) }}"
                class="dropdown-item d-flex align-items-center gap-2 py-2 rounded-2 hover-bg-light">
                <i class="fas fa-laptop text-primary fs-14"></i>
                <span>Asset Management</span>
            </a>
        </li>
        <li>
<a href="{{ route('employee.allowance.form', encrypt($row->id)) }}" 
   class="dropdown-item">
   <i class="fas fa-money-bill-wave me-2"></i> Allowance
</a>
     </li>
    </ul>
</div>