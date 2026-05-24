<style>
    .white-table table,
    .white-table thead,
    .white-table tbody,
    .white-table tr,
    .white-table th,
    .white-table td {
        background: #fff !important;
    }
</style>

@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">
                <i class="ri-percent-line text-primary me-2"></i>
                {{ $customer->name }} — Product Discounts
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none d-flex align-items-center">
                            <i class="ri-home-8-line text-primary me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('customers.index') }}" class="text-decoration-none">Customers</a>
                    </li>
                    <li class="breadcrumb-item active">Customer Discount</li>
                </ol>
            </nav>
        </div>

        <form method="POST" action="{{ route('customer-product-discount.store') }}">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer_id }}">

            <div class="overall-section mb-4">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Overall Discount (%)</label>
                        <input type="number" step="0.01" id="overallDiscount" name="overall_discount" class="form-control"
                            placeholder="Enter overall discount" value="{{ $overallDiscount->discount_percent ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary fw-normal text-white">
                            <i class="ri-check-double-line me-1"></i>Apply to All
                        </button>
                    </div>

                </div>
            </div>

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-semibold">Product-wise Discounts</h5>
                    <span class="text-muted small">
                        Showing {{ count($products) }} products
                    </span>
                </div>

                <div class="table-responsive white-table bg-white">
                    <table class="table table-bordered bg-white align-middle">
                        <thead class="table-light bg-white">
                            <tr>
                                <th style="width:55%">Product</th>
                                <th style="width:25%">Specific Discount (%)</th>
                                <th style="width:20%">Final Applied Discount</th>
                                <th style="width:20%">Saved</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white">
                            @foreach ($products as $product)
                                @php
                                    $specific = $discounts[$product->id]->discount_percent ?? null;
                                    $overall = $overallDiscount->discount_percent ?? null;
                                    $final = $specific ?? ($overall ?? 0);
                                @endphp
                                <tr>
                                    <td>{{ $product->name }} ({{ $product->code }})</td>
                                    <td>
                                        <input type="number" step="0.01" id="discount_{{ $product->id }}" name="discounts[{{ $product->id }}]"
                                            class="form-control bg-white" value="{{ $specific ?? '' }}"
                                            placeholder="e.g. 10.00">
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $specific ? 'success' : 'secondary' }}">
                                            {{ $final }}%
                                            @if ($specific)
                                                <small class="text-light">(specific)</small>
                                            @elseif($overall)
                                                <small class="text-light">(overall)</small>
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary save-discount text-white"
                                            data-product-id="{{ $product->id }}">
                                            Save
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
               
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            $('button[type="submit"]').on('click', function(e) {
                if ($(this).text().includes('Apply to All')) {
                    if (!confirm("Are you sure you want to apply this discount to all products?")) {
                        e.preventDefault();
                    }
                }
            });

            $('.save-discount').on('click', function(e) {
                e.preventDefault();

                let btn = $(this);
                let productId = btn.data('product-id');
                let discount = $(`input[name="discounts[${productId}]"]`).val();
                let customerId = $('input[name="customer_id"]').val();

                btn.prop('disabled', true).html('<i class="ri-loader-4-line ri-spin"></i> Saving...');


                $.ajax({
                    url: "{{ route('customer-product-discount.store') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        customer_id: customerId,
                        product_id: productId,
                        discount_percent: discount
                    },
                    success: function(response) {
                        if (response.success == true) {
                            btn.removeClass('btn-primary').addClass('btn-success text-white')
                                .html('<i class="ri-check-line"></i> Saved');

                            toastr.success('Discount updated successfully!', {
                                timeOut: 1500,
                                closeButton: true,
                                progressBar: true
                            });
                            setTimeout(function() {
                                btn.removeClass('btn-success text-white').addClass(
                                    'btn-primary text-white').html('Save');
                                btn.prop('disabled', false);
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        console.error("Error saving discount:", xhr.responseText);
                        btn.prop('disabled', false).html('Save');
                    }
                });
            });

        });
       
    </script>

<script>
    $(document).ready(function(){

        $(document).on('input', '#overallDiscount', function () {
            let val = parseFloat($(this).val());
            if (isNaN(val)) val = "";
            if (val < 0) val = 0;
            if (val > 100) val = 100;
            $(this).val(val);
        });

        $(document).on('input', 'input[name^="discounts"]', function () {
            let val = parseFloat($(this).val());
            if (isNaN(val)) val = "";
            if (val < 0) val = 0;
            if (val > 100) val = 100;
            $(this).val(val);
        });

    });
</script>

@endpush
