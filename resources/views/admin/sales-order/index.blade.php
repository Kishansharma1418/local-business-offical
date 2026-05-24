 <style>
     input.form-control.form-control-sm {
         height: 43px;
     }
 </style>
 @extends('include.master')
 @section('content')
     <div class="main-content-container overflow-hidden">
         <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
             <h3 class="mb-0">Sales Order List</h3>

             <nav aria-label="breadcrumb">
                 <ol class="breadcrumb align-items-center mb-0 lh-1">
                     <li class="breadcrumb-item">
                         <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                             <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                             <span class="text-body fs-14 hover">Dashboard</span>
                         </a>
                     </li>

                     <li class="breadcrumb-item active" aria-current="page">
                         <span class="text-secondary">Sales Order List</span>
                     </li>
                 </ol>
             </nav>
         </div>

         <div class="card bg-white rounded-10 border border-white mb-4">

             <div class="d-flex  align-items-center flex-wrap gap-3 p-20">

                 {{-- @can('add-sales-order') --}}
                 <a href="{{ route('sale-orders.create') }}"
                     class="text-decorntion-none btn btn-primary fw-normal text-white fs-16 border-0 p-3 fs-16 border-0">+
                     Add Sales Order </a>
                 {{-- @endcan --}}
                 <div class="d-flex align-items-center gap-2">
                     <select name="name" id="nameFilter" class="form-control form-select-sm"
                         style="width: 180px; height: 50px;">
                         <option value="">All Customers</option>
                         @foreach ($query as $t)
                             <option value="{{ $t->name }}">{{ $t->name }}</option>
                         @endforeach
                     </select>
                 </div>
             </div>
             <div class="default-table-area mx-minus-1 ">
                 <div class="table-responsive overflow-none">
                     <table class="table" id="salesOrderTable">
                         <thead>
                             <tr>
                                 <th scope="col" class="fw-medium">User</th>
                                 <th scope="col" class="fw-medium">Sales Order type</th>
                                 <!-- <th scope="col" class="fw-medium">Status</th> -->
                                 <th scope="col" class="fw-medium">Approval Status</th>
                                 <th scope="col" class="fw-medium">Created At</th>
                                 <th scope="col" class="fw-medium" style="text-align: center">Action</th>
                             </tr>
                         </thead>

                         <tbody>

                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
     </div>
     <div class="modal fade" id="salesEditReasonModal" tabindex="-1" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
             <form id="salesEditReasonForm" class="modal-content bg-white">
                 @csrf

                 <input type="hidden" id="salesOrderId">
                 <input type="hidden" id="salesEditUrl">

                 <!-- HEADER -->
                 <div class="modal-header border-border-color-40 p-20">
                     <h1 class="modal-title fs-18 fw-medium mb-0">
                         Reason for Editing Sales Order
                     </h1>
                     <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                 </div>

                 <div class="modal-body p-20 pb-0">
                     <div class="mb-20">
                         <label class="label">
                             Reason <span class="text-danger">*</span>
                         </label>

                         <div class="form-floating">
                             <textarea id="salesEditReason" class="form-control" rows="3" placeholder="Enter reason" style="height: 100px"
                                 required></textarea>
                             <label for="salesEditReason">Enter minimum 3 character </label>
                         </div>
                     </div>
                 </div>

                 <div class="modal-footer border-0 p-20 pt-0">
                     <button type="button" class="btn btn-danger fw-normal text-white" data-bs-dismiss="modal">
                         Cancel
                     </button>

                     <button type="submit" id="salesReasonSubmitBtn" class="btn btn-primary fw-normal text-white" disabled>
                         Submit & Continue
                     </button>
                 </div>

             </form>
         </div>
     </div>
 @endsection
 @push('scripts')
     <script>
         $(document).ready(function() {
             let dataTable = $('#salesOrderTable').DataTable({
                 processing: true,
                 serverSide: true,
                 autoWidth: true,
                 ajax: {
                     url: "{{ route('sale-orders.index') }}",
                     data: function(d) {
                         d.name = $('#nameFilter').val();
                     }
                 },
                 columns: [{
                         data: 'user',
                         name: 'user'
                     },
                     {
                         data: 'type',
                         name: 'type'
                     },
                     {
                         data: 'approval_status',
                         name: 'approval_status'
                     },
                     {
                         data: 'created_at',
                         name: 'created_at'
                     },
                     {
                         data: 'action',
                         orderable: false,
                         searchable: false
                     }
                 ],
                 dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +

                     "<'row'<'col-sm-12'tr>>" +
                     "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
             });
             $('#nameFilter').change(function() {
                 dataTable.draw();
             });

             $(document).on('click', '.open-sales-edit', function() {
                 $('#salesOrderId').val($(this).data('id'));
                 $('#salesEditUrl').val($(this).data('url'));
                 $('#salesEditReason').val('');
                 $('#salesReasonSubmitBtn').prop('disabled', true);
                 $('#salesEditReasonModal').modal('show');
             });

             $('#salesEditReason').on('input', function() {
                 $('#salesReasonSubmitBtn').prop(
                     'disabled',
                     $(this).val().trim().length < 3
                 );
             });

             $('#salesEditReasonForm').on('submit', function(e) {
                 e.preventDefault();

                 $.post("{{ route('sale-orders.save-edit-remark') }}", {
                     _token: "{{ csrf_token() }}",
                     sales_order_id: $('#salesOrderId').val(),
                     remark: $('#salesEditReason').val()
                 }, function() {
                     window.location.href = $('#salesEditUrl').val();
                 });
             });

         });
     </script>
 @endpush
