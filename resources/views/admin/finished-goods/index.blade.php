<style>
    input.form-control.form-control-sm {
        height: 43px;
    }

    @keyframes blink {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.3;
        }

        100% {
            opacity: 1;
        }
    }

    /* Danger – fast blink */
    .blink-danger {
        animation: blink 1s infinite;
    }

    /* Warning – medium blink */
    .blink-warning {
        animation: blink 1.5s infinite;
    }

    /* Success – slow blink */
    .blink-success {
        animation: blink 2.5s infinite;
    }
</style>
@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Finished Goods List</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="text-secondary">Finished Goods List</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Card -->
    <div class="card bg-white rounded-10 border border-white mb-4">

        <!-- Add New Button -->
        <div class="d-flex  align-items-center flex-wrap gap-3 p-20">
            {{-- @can('add-finished-goods') --}}
            <a href="{{ route('finished-good.create') }}"
                class="btn btn-primary d-flex align-items-center justify-content-center px-3 py-3  shadow-sm"
                style="color: #fff; font-size: 14px; height: 50px;">
                + Add Finished Goods
            </a>
            <a href="{{ url('/finished-good/sample-download') }}" class="btn btn-success">
                Download Sample
            </a>

            <form action="{{ url('/finished-good/import') }}" method="POST" enctype="multipart/form-data" class="mt-2">
                @csrf
                <input type="file" name="file" class="form-control">
                <button class="btn btn-primary mt-2">Import</button>
            </form>
            <div class="d-flex align-items-center gap-2">
                <select name="category_id" id="category_id" class="form-control form-select-sm w-auto "
                    style="margin-left: 10px; height: 50px;">
                    <option value="">All Categories</option>
                    @foreach ($categoriesCat as $e)
                    <option value="{{ $e->id }}">{{ $e->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex align-items-center gap-2">
                <select name="sub_category_id" id="sub_category_id" class="form-control form-select-sm w-auto"
                    style="margin-left: 10px; height:50px;">
                    <option value="">All Sub Categories</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="default-table-area mx-minus-1">
            <div class="table-responsive overflow-none">
                <table class="table" id="finishedGoodsTable">
                    <thead>
                        <tr>

                            <th>Product Details</th>
                            <th>Category</th>

                            <th>Total Available Quantity</th>
                            <th>Batch Count</th>
                            <th style="display:none;">Sort</th>
                            <th>Record Lavel</th>
                            <th>total stock value</th>


                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                <!-- Batch Detail Modal -->
                <div class="modal fade" id="batchExpandModal" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="batchModalTitle">Batch Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-0">
                                <table class="table table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Batch No</th>
                                            <th>MFG Date</th>
                                            <th>Expiry Date</th>
                                            <th>MRP</th>
                                            <th class="text-success">Inward</th>
                                            <th class="text-danger">Outward</th>
                                            <th class="text-primary">Balance</th>
                                            <th class="text-warning">Total Stock Value</th>
                                        </tr>
                                    </thead>
                                    <tbody id="batchModalBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#finishedGoodsTable').DataTable({
            processing: true,
            serverSide: false,

            ajax: {
                url: "{{ route('finished-good.index') }}",
                data: function(d) {
                    d.name = $('#nameFilter').val();
                    d.category_id = $('#category_id').val();
                    d.sub_category_id = $('#sub_category_id').val();
                }
            },
            order: [
                [4, 'asc']
            ],
            columns: [

                {
                    data: 'product_detail',
                    name: 'product_detail'

                },


                {
                    data: 'category',
                    name: 'category',
                    defaultContent: '-'
                },


                {
                    data: 'total_batch_quantity',
                    name: 'total_batch_quantity',

                },
                {
                    data: 'batch_count',
                    name: 'batch_count',
                    className: 'text-center',
                    render: function(data) {
                        return `${data} </span>`;
                    }
                },
                {
                    data: 'stock_status_order',
                    name: 'stock_status_order',
                    visible: false,
                    searchable: false,
                    orderable: true
                },

                {
                    data: 'record_level',
                    name: 'record_level',
                    orderData: [4],
                    render: function(data, type, row) {

                        let totalQty = Number(
                            String(row.total_batch_quantity).replace(/,/g, '')
                        );

                        let minLevel = Number(
                            String(row.record_level).replace(/,/g, '')
                        );

                        if (isNaN(totalQty)) totalQty = 0;
                        if (isNaN(minLevel)) minLevel = 0;

                        let statusText = '';
                        let badgeClass = '';

                        if (totalQty === 0) {
                            statusText = 'Out of Stock';
                            badgeClass = 'badge bg-danger blink-danger';
                        } else if (totalQty < minLevel) {
                            statusText = 'Low Stock';
                            badgeClass = 'badge bg-warning text-dark blink-warning';
                        } else {
                            statusText = 'Sufficient Stock';
                            badgeClass = 'badge bg-success blink-success';
                        }

                        return `
                                <div class="text-center">
                                    <div class="fw-bold mb-1">
                                        ${minLevel}
                                    </div>
                                    <span class="${badgeClass} px-3 py-1">
                                        ${statusText}
                                    </span>
                                </div>
                            `;
                    }
                },
                {
                    data: 'product_detail',
                    name: 'total_value',
                    orderable: true,
                    searchable: false,
                    render: function(data, type, row) {
                        let batches = [];
                        try {
                            // product_detail mein data-batches attribute se parse karo
                            let tempDiv = document.createElement('div');
                            tempDiv.innerHTML = data;
                            let dataDiv = tempDiv.querySelector('[data-batches]');
                            if (dataDiv) {
                                batches = JSON.parse(dataDiv.getAttribute('data-batches'));
                            }
                        } catch (e) {
                            batches = [];
                        }

                        let totalValue = batches.reduce(function(sum, b) {
                            return sum + (Number(b.total_cost) || 0);
                        }, 0);

                        return `<strong>₹${totalValue.toLocaleString('en-IN')}</strong>`;
                    }
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +

                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",

        });
        $('#nameFilter').change(function() {
            table.draw();
        });
        $('#sub_category_id').change(function() {
            table.draw();
        });

        $(document).on('click', '.deleteBranchBtn', function() {
            if (confirm('Are you sure want to delete this product?')) {
                var id = $(this).data('id');

                $.ajax({
                    url: "{{ url('finished-good') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        console.log(res);
                        table.ajax.reload();
                        toastr.success(res.message);
                    }
                });
            }
        });

    });
</script>
<script>
    $(document).ready(function() {

        let categories = @json($categoriesCat);
        $('#category_id').on('change', function() {
            let categoryId = $(this).val();
            let subCategorySelect = $('#sub_category_id');

            subCategorySelect.empty()
                .append('<option value="">All Sub Categories</option>');

            if (!categoryId) {
                table.ajax.reload();
                return;
            }

            let category = categories.find(c => c.id == categoryId);

            if (category && category.subcategories) {
                category.subcategories.forEach(function(sub) {
                    subCategorySelect.append(
                        `<option value="${sub.id}">${sub.category_name}</option>`
                    );
                });
            }

            table.ajax.reload();
        });
        $('#sub_category_id').on('change', function() {
            table.ajax.reload();
        });
        // Product row click pe batch expand
        // Product row click pe modal open
        $('#finishedGoodsTable tbody').on('click', 'tr', function(e) {

            // ✅ Action column pe click ho toh modal mat kholo
            if ($(e.target).closest('td:last-child, .action-btn, a, button').length) {
                return;
            }

            let $dataDiv = $(this).find('[data-batches]');
            if (!$dataDiv.length) return;

            let productName = $(this).find('strong').first().text().trim();
            $('#batchModalTitle').text('Batches — ' + productName);

            let batches = [];
            try {
                batches = JSON.parse($dataDiv.attr('data-batches'));
            } catch (e) {
                batches = [];
            }

            let tbody = '';
            if (!batches.length) {
                tbody = '<tr><td colspan="9" class="text-center text-muted">No batches found</td></tr>';
            } else {
                batches.forEach(function(b, i) {
                    tbody += `
                <tr>
                    <td>${i + 1}</td>
                    <td><strong>${b.batch}</strong></td>
                    <td>${b.mfg}</td>
                    <td>${b.exp}</td>
                    <td>₹${b.mrp}</td>
                    <td class="text-success fw-bold">${b.inward}</td>
                    <td class="text-danger fw-bold">${b.outward}</td>
                    <td class="text-primary fw-bold">${b.balance}</td>
                    <td class="text-warning fw-bold">₹${Number(b.total_cost).toLocaleString('en-IN')}</td>
                </tr>`;
                });
            }

            $('#batchModalBody').html(tbody);
            new bootstrap.Modal(document.getElementById('batchExpandModal')).show();
        });

        // ✅ Modal close pe action buttons fix
        $('#batchExpandModal').on('hidden.bs.modal', function() {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            // DataTable redraw karo
            $('#finishedGoodsTable').DataTable().draw(false);
        });
    });
</script>
@endpush