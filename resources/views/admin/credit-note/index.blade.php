   <style>
       input.form-control.form-control-sm {
           height: 43px;
       }

       .avatar {
           width: 48px;
           height: 48px;
       }

       .modal-content {
           border: none;
       }

       .input-group-text {
           background: #fff;
           font-weight: 600;
       }
   </style>
   @extends('include.master')
   @section('content')
       <div class="main-content-container overflow-hidden">
           <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
               <h3 class="mb-0">Credit Note List</h3>

               <nav aria-label="breadcrumb">
                   <ol class="breadcrumb align-items-center mb-0 lh-1">
                       <li class="breadcrumb-item"> 
                           <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                               <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                               <span class="text-body fs-14 hover">Dashboard</span>
                           </a>
                       </li>

                       <li class="breadcrumb-item active" aria-current="page">
                           <span class="text-secondary">Credit Note List</span>
                       </li>
                   </ol>
               </nav>
           </div>

           <!-- <div class="modal fade" id="refundModal" tabindex="-1">
                                        <div class="modal-dialog modal-md modal-dialog-centered">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title">Create Refund</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <form id="refundForm">
                                                    @csrf
                                                    <div class="modal-body">

                                                        <input type="hidden" name="credit_note_id" id="credit_note_id">
                                                        <input type="hidden" name="customer_id" id="customer_id">

                                                        <div class="mb-3">
                                                            <label class="form-label">Refund Date</label>
                                                            <input type="date" name="refund_order_date" class="form-control" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Refund Amount</label>
                                                            <input type="number" step="0.01" name="amount" id="refund_amount"
                                                                class="form-control" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Payment Method</label>
                                                            <select name="payment_method" class="form-control">
                                                                <option value="cash">Cash</option>
                                                                <option value="bank">Bank</option>
                                                                <option value="upi">UPI</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Reference Number</label>
                                                            <input type="text" name="reference_number" class="form-control">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Remarks</label>
                                                            <textarea name="remarks" class="form-control"></textarea>
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary text-white">Save Refund</button>
                                                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div> -->

           <div class="modal fade" id="refundModal" tabindex="-1">
               <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
                   <div class="modal-content bg-white rounded-10">

                       <!-- HEADER -->
                       <div class="modal-header border-border-color-40 p-20">
                           <h5 class="modal-title fs-18 fw-medium mb-0">
                               Refund (<span id="cnNumber"></span>)
                           </h5>
                           <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                       </div>

                       <form id="refundForm">
                           @csrf

                           <!-- BODY -->
                           <div class="modal-body p-20 pb-0">

                               <!-- CUSTOMER + CREDIT NOTE -->
                               <div class="row mb-20">
                                   <div class="col-md-6">
                                       <div class="d-flex align-items-center gap-3">
                                           <div class="avatar bg-light rounded-circle d-flex align-items-center justify-content-center"
                                               style="width:40px;height:40px;">
                                               <i class="ri-user-3-line fs-4 text-muted"></i>
                                           </div>
                                           <div>
                                               <div class="text-muted fs-13">Customer Name</div>
                                               <div class="fw-medium" id="customerName">-</div>
                                           </div>
                                       </div>
                                   </div>

                                   <div class="col-md-6 text-end">
                                       <div class="text-muted fs-13">Credit Note Number</div>
                                       <div class="fw-medium" id="creditNoteNumber">-</div>
                                   </div>
                               </div>

                               <!-- AMOUNT SECTION -->
                               <div class="bg-light rounded-10 mt-3">
                                   <div class="row align-items-center">
                                       <div class="col-md-6 mb-4">
                                           <label class="label mb-2">
                                               Amount <span class="text-danger">*</span>
                                           </label>
                                           <div class="input-group">
                                               <span class="input-group-text">CAD</span>
                                               <input type="number" step="0.01" name="amount" id="refund_amount"
                                                   required min='0' class="form-control fw-medium"
                                                   data-rule-required="true">
                                           </div>
                                       </div>

                                       <div class="col-md-6 text-end">
                                           <div class="text-muted fs-13">Balance</div>
                                           <div class="fw-medium" id="balanceAmount">0.00</div>
                                       </div>
                                   </div>
                               </div>

                               <!-- DATE + PAYMENT -->
                               <div class="row mb-20">
                                   <div class="col-md-6">
                                       <label class="label mb-2">
                                           Refunded On <span class="text-danger">*</span>
                                       </label>
                                       <input type="date" name="refund_order_date" value="{{ date('Y-m-d') }}"
                                           class="form-control">
                                   </div>

                                   <div class="col-md-6">
                                       <label class="label mb-2">Payment Mode</label>
                                       <select name="payment_method" class="form-select form-control">
                                           <option value="cash">Cash</option>
                                           <option value="bank">Bank</option>
                                           <option value="upi">UPI</option>
                                       </select>
                                   </div>
                               </div>

                               <!-- REFERENCE -->
                               <div class="row mb-20">
                                   <div class="col-md-12">
                                       <label class="label mb-2">Reference #</label>
                                       <input type="text" name="reference_number" class="form-control">
                                   </div>
                               </div>

                               <!-- DESCRIPTION -->
                               <div class="mb-20">
                                   <label class="label mb-2">Description</label>
                                   <textarea name="remarks" rows="3" class="form-control"></textarea>
                               </div>

                               <input type="hidden" name="credit_note_id" id="credit_note_id">
                               <input type="hidden" name="customer_id" id="customer_id">

                           </div>

                           <!-- FOOTER -->
                           <div class="modal-footer border-0 p-20 pt-0">
                               <button type="button" class="btn btn-danger fw-normal text-white"
                                   data-bs-dismiss="modal">Cancel</button>
                               <button type="submit" class="btn btn-primary fw-normal text-white">
                                   Save
                               </button>
                           </div>
                       </form>

                   </div>
               </div>
           </div>

           <div class="card bg-white rounded-10 border border-white mb-4">

               <div class="d-flex  align-items-center flex-wrap gap-3 p-20">

                   <a href="{{ route('credit-notes.create') }}"
                       class="text-decorntion-none btn btn-primary fw-normal text-white fs-16 border-0 p-3 fs-16 border-0">+
                       Add Credit Note </a>
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

                                   <th scope="col" class="fw-medium">Date</th>
                                   <th scope="col" class="fw-medium">Credit Note Number</th>
                                   <th scope="col" class="fw-medium">Reference Number</th>
                                   <!-- <th scope="col" class="fw-medium">Credit Type</th> -->
                                   <th scope="col" class="fw-medium">Customer</th>
                                   <th scope="col" class="fw-medium">Invoice Number</th>
                                   <th scope="col" class="fw-medium">Status</th>
                                   <th scope="col" class="fw-medium">Net Amount</th>
                                   <th scope="col" class="fw-medium">Balance</th>


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
   @endsection

   @push('scripts')
       <script type="text/javascript">
           $(document).ready(function() {

               if (!$.fn.DataTable.isDataTable('#salesOrderTable')) {
                   var dataTable = $('#salesOrderTable').DataTable({
                       processing: true,
                       serverSide: true,
                       autoWidth: true,
                       scrollX: false,
                       // responsive: true,
                       lengthMenu: [10, 20, 50, 100],
                       language: {
                           processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border"></div></div></div>'
                       },
                       ajax: {
                           url: "{{ route('credit-notes.index') }}",
                           data: function(d) {
                               d.name = $('#nameFilter').val();
                           }
                       },
                       columns: [

                           {
                               data: 'credit_note_date',
                               name: 'credit_note_date'
                           },
                           {
                               data: 'credit_note_number',
                               name: 'credit_note_number'
                           },
                           {
                               data: 'reference_number',
                               name: 'reference_number',
                               render: function(data, type, row) {
                                   return data ? data : 'N/A';
                               }
                           },
                           // {
                           //     data: 'credit_type',
                           //     name: 'credit_type'
                           // },
                           {
                               data: 'user',
                               name: 'user'
                           },
                           {
                               data: 'code',
                               name: 'code'
                           },

                           {
                               data: 'status',
                               name: 'status'
                           },
                           {
                               data: 'net_amount',
                               name: 'net_amount'
                           },
                           {
                               data: 'balance_due',
                               name: 'balance_due'
                           },

                           {
                               data: 'action',
                               name: 'action',

                           }
                       ],
                       dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +

                           "<'row'<'col-sm-12'tr>>" +
                           "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
                   });
               }
               $('#nameFilter').change(function() {
                   dataTable.draw();
               });

           });
       </script>

       <script type="text/javascript">
           $(document).on('click', '.openRefundModal', function() {
               let creditNoteId = $(this).data('credit-note-id');
               let creditNoteNumber = $(this).data('credit-note-number');
               let customerId = $(this).data('customer-id');
               let customerName = $(this).data('customer-name');
               let amount = $(this).data('amount');
               let balanceDue = $(this).data('balance-due');

               $('#credit_note_id').val(creditNoteId);
               $('#customer_id').val(customerId);

               $('#cnNumber').text(creditNoteNumber);
               $('#creditNoteNumber').text(creditNoteNumber);
               $('#customerName').text(customerName);

               $('#refund_amount').val(balanceDue.toFixed(2));
               $('#balanceAmount').text(parseFloat(balanceDue).toFixed(2));

               $('#refundModal').modal('show');
           });

           $('#refund_amount').on('input', function() {
               let max = parseFloat($('#balanceAmount').text());
               let val = parseFloat($(this).val());

               if (val > max) {
                   toastr.error('Refund amount cannot exceed balance');
                   $(this).val(max);
               }
           });

           $('#refundForm').on('submit', function(e) {
               e.preventDefault();

               let form = $(this);

               $.ajax({
                   url: "{{ route('refund-orders.store') }}",
                   type: "POST",
                   data: form.serialize(),
                   beforeSend: function() {
                       form.find('button[type=submit]').prop('disabled', true).text('Saving...');
                   },
                   success: function(res) {

                       if (res.status) {
                           toastr.success(res.message);

                           $('#refundModal').modal('hide');
                           form[0].reset();

                           $('#salesOrderTable').DataTable().ajax.reload(null, false);
                       }
                   },
                   error: function(xhr) {

                       if (xhr.responseJSON && xhr.responseJSON.message) {
                           toastr.error(xhr.responseJSON.message);
                       } else {
                           toastr.error('Something went wrong');
                       }
                   },
                   complete: function() {
                       form.find('button[type=submit]').prop('disabled', false).text('Save');
                   }
               });
           });
       </script>
   @endpush
