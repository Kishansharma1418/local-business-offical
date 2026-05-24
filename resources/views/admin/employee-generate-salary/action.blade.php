<div class="d-flex justify-content-center" style="gap: 12px;">
     {{-- <a href="{{ route('employee-attandance.edit', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success">
         <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
     </a> --}}
     
    <a href="{{ route('salary-generate.show', encrypt($row->id)) }}" class="bg-transparent p-0 border-0 hover-text-success"
        title="View Department Details">
        <em class="fas fa-eye  font-16"></em>
    </a>

    <a href="{{ route('salary.pdf', $row->id) }}" class="bg-transparent p-0 border-0 hover-text-success"
        title="Download Salary PDF">
        <em class="fas fa-download font-16"></em>
    </a>

 </div>
