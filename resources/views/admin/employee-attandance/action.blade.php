<div class="d-flex justify-content-center" style="gap: 12px;">
       {{-- <a href="{{ route('employee-attandance.edit', encrypt($row->employee_id)) }}" 
       class="btn btn-sm btn-outline-success"
       title="Edit Attendance">
        <i class="material-symbols-outlined fs-16">edit</i>
    </a> --}}
    {{-- @can('edit-employee-attendence') --}}
        <a href="{{ route('employee-attandance.edit', ['employee_attandance' => encrypt($row->employee_id), 'month' => $row->month]) }}" 
        class="bg-transparent p-0 border-0 hover-text-success"
        title="Edit">
            <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
        </a>
        {{-- @endcan --}}
        <a href="{{ route('employee-attandance.show', ['employee_attandance' => encrypt($row->employee_id), 'month' => $row->month]) }}" >

            <em class="fas fa-eye font-16"></em>
        </a>

  
 </div>
