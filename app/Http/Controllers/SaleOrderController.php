<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{SalesOrder, Customer, CustomerAddress, Branch, Employee, PaymentTerms, User, FinishedGood, BatchManagement, CustomerProductDiscount, SalesOrderDetails, SalesOrderApproval, InvoiceOrder, FinishedGoodStockLedger};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Exception;


class SaleOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SalesOrder::with('customer')->orderBy('id', 'DESC');

            if ($request->name) {
                $query->whereHas('customer', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->name . '%');
                });
            }
            return Datatables::of($query)
                ->addIndexColumn()
                ->filterColumn('user', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->whereHas('customer', function ($custQuery) use ($keyword) {
                            $custQuery->where('name', 'like', "%{$keyword}%")
                                ->orWhere('code', 'like', "%{$keyword}%")
                                ->orWhere('email', 'like', "%{$keyword}%")
                                ->orWhere('mobile_no', 'like', "%{$keyword}%");
                        });
                    });
                })
              ->editColumn('created_at', fn($row) => formatDate($row->created_at))

                ->addColumn('user', function ($row) {

                    $user  = '<div class="d-flex align-items-center " style="gap:15px;">';

                    $user .= '   <div class="flex-grow-1">';

                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Name:</strong> ' . ($row->customer->name ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Customer Code:</strong> ' . ($row->customer->code ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Email:</strong> ' . ($row->customer->email ?? '-') . '</p>';
                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Phone:</strong> ' . ($row->customer->mobile_no ?? '-') . '</p>';

                    $user .= '   </div>';
                    $user .= '</div>';

                    return $user;
                })
                // ->editColumn('status', function ($row) {
                //     if ($row->status == 'Cancelled') {
                //         return '<span class="badge bg-danger">Cancelled</span>';
                //     } elseif ($row->status == 'PartiallyFulfilled') {
                //         return '<span class="badge bg-warning">Partially Fulfilled</span>';
                //     } elseif ($row->status == 'Completed') {
                //         return '<span class="badge bg-success">Completed</span>';
                //     }
                //     else {
                //         return '<span class="badge bg-secondary">Pending</span>';
                //     }
                // })

                ->editColumn('approval_status', function ($row) {
                    if ($row->approval_status == 'Rejected') {
                        return '<span class="badge bg-danger">Rejected</span>';
                    } elseif ($row->approval_status == 'Approved') {
                        return '<span class="badge bg-success">Approved</span>';
                    } else {
                        return '<span class="badge bg-warning">Pending</span>';
                    }
                })

                ->addColumn('action', function ($row) {
                    $type = "action";
                    return view('admin.sales-order.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action', 'status', 'user', 'approval_status'])
                ->make(true);
        }
        $query = Customer::select('name')->groupBy('name')->get();
        return view('admin.sales-order.index', compact('query'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('status', '1')->where('is_blocked', '0')->get();
        $employees = Employee::where('status', '1')->where('is_login', '1')->get();
        $finishedGoods = FinishedGood::where('status', '1')->get();
        $branches = Branch::where('status', 'Active')->get();
        $paymentTerms = PaymentTerms::where('status', '1')->get();
        $salesOrder = SalesOrder::all();

        return view('admin.sales-order.create', compact('customers', 'employees', 'finishedGoods', 'branches', 'paymentTerms'));
    }

    /**
     * Get customer specific product discounts
     */
    public function getCustomerProductDiscount(Request $request)
    {
        $customerId = $request->query('customer_id');
        $productId  = $request->query('product_id');

        $discount = 0;

        if ($customerId && $productId) {
            $prdDisc = CustomerProductDiscount::where('customer_id', $customerId)
                ->where('finish_goods_id', $productId)
                ->first();

            if ($prdDisc && $prdDisc->discount_percent !== null) {
                $discount = floatval($prdDisc->discount_percent);
            } else {

                $cust = Customer::find($customerId);
                if ($cust && isset($cust->overall_discount) && $cust->overall_discount !== null) {
                    $discount = floatval($cust->overall_discount);
                } else {
                    $overall = CustomerProductDiscount::where('customer_id', $customerId)
                        ->where('discount_type', 'overall')
                        ->first();
                    if ($overall && $overall->discount_percent !== null) {
                        $discount = floatval($overall->discount_percent);
                    }
                }
            }
        }

        return response()->json(['discount' => $discount]);
    }

    /**
     *  batches for a finished good product.
     */
    public function getProductBatches($productId)
    {

        $batches = BatchManagement::where('product_id', $productId)
            ->where('available_quantity', '>', 0)
            ->get([
                'id',
                'batch_number',
                'manufacturing_date',
                'expiry_date',
                'available_quantity',
                'unit_cost',
                'gst_percent',
            ]);

        return response()->json($batches);
    }

    /**
     * Check customer credit limit and outstanding
     */
    public function checkCustomerCredit(Request $request)
    {
        $customerId = $request->query('customer_id');

        $customer = Customer::find($customerId);
        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found'
            ]);
        }

        $creditLimit = floatval($customer->credit_limit ?? 0);

        $outstanding = SalesOrder::where('customer_id', $customerId)
            ->whereIn('payment_status', ['Pending', 'Partial'])
            ->sum('net_amount');

        if ($outstanding < 0) $outstanding = 0;

        return response()->json([
            'status' => true,
            'credit_limit' => $creditLimit,
            'outstanding'  => $outstanding
        ]);
    }

    /**
     * Generate a new sales order code.
     */
    public function generateSalesOrderCode()
    {
        $lastOrder = SalesOrder::orderBy('id', 'DESC')->first();

        if (!$lastOrder) {
            return 'SO-101';
        }

        $lastNumber = intval(str_replace('SO-', '', $lastOrder->code));

        $nextNumber = $lastNumber + 1;

        return 'SO-' . $nextNumber;
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            // $subtotal = 0;
            // foreach ($request->items as $it) {
            //     if (!$it['product_id']) continue;
            //     $subtotal += floatval($it['quantity_ordered']) * floatval($it['unit_price']);
            // }

            // $overallPercent = floatval($request->overall_discount);
            // $overallAmount  = floatval($request->overall_discount);


            // if ($overallPercent < 0) $overallPercent = 0;
            // if ($overallPercent > 100) $overallPercent = 100;


            // if ($overallAmount < 0) $overallAmount = 0;


            // if ($overallPercent > 0 && $subtotal > 0) {
            //     $overallAmount = ($subtotal * $overallPercent) / 100;
            // }

            // if ($overallAmount > 0 && $subtotal > 0) {
            //     $overallPercent = ($overallAmount / $subtotal) * 100;
            //     if ($overallPercent > 100) $overallPercent = 100;
            // }


            $subtotal = 0;

            foreach ($request->items as $it) {
                if (!$it['product_id']) continue;

                $qty   = floatval($it['quantity_ordered']);
                $price = floatval($it['unit_price']);

                $subtotal += ($qty * $price);
            }

            $discountType  = $request->overall_bill_discount_type;
            $discountValue = floatval($request->overall_discount);

            $overallDiscountAmount = 0;

            if ($discountType === 'percent') {

                if ($discountValue < 0) $discountValue = 0;
                if ($discountValue > 100) $discountValue = 100;

                $overallDiscountAmount = ($subtotal * $discountValue) / 100;
            } elseif ($discountType === 'amount') {

                if ($discountValue < 0) $discountValue = 0;

                if ($discountValue > $subtotal) {
                    $discountValue = $subtotal;
                }

                $overallDiscountAmount = $discountValue;
            }

            $code = $request->is_draft ? null :  $this->generateCode('sales_order');
            $type = $request->is_draft ? 'draft' : 'final';

            $salesOrder = SalesOrder::create([
                'code'              => $code,
                'date'              => $request->date,
                'customer_id'       => $request->customer_id,
                'branch_id'         => $request->branch_id,
                'payment_terms_id'  => $request->payment_terms_id,

                'sales_person_id'   => $request->sales_person_id,
                'approval_status'   => 'Pending',
                'status'            => 'Pending',
                'overall_bill_discount_amount'  => $overallDiscountAmount,
                'overall_bill_discount_percent' => $discountValue,
                'overall_bill_discount_type'    => $discountType,
                'type'              => $type,
                'created_by'        => auth()->id(),
            ]);


            $totalOrderAmt = 0;
            $totalGstAmt   = 0;

            $productPureTotal = 0;
            foreach ($request->items as $item) {

                if (!$item['product_id']) continue;
                $batch = BatchManagement::where('batch_number', $item['batch_id'])
                    ->where('product_id', $item['product_id'])
                    ->first();

                $mfgDate = $batch->manufacturing_date ?? null;
                $expDate = $batch->expiry_date ?? null;

                $qty      = floatval($item['quantity_ordered']);
                $price    = floatval($item['unit_price']);
                $discount = floatval($item['discount_percent']);
                $gst      = floatval($item['gst_percent']);

                $pureAmount = $qty * $price;
                $productPureTotal += $pureAmount;
                $amount  = $qty * $price;
                $discAmt = ($amount * $discount) / 100;
                $afterDisc = $amount - $discAmt;
                $gstAmt = ($afterDisc * $gst) / 100;
                $total  = $afterDisc + $gstAmt;

                $totalOrderAmt += $total;
                $totalGstAmt   += $gstAmt;

                SalesOrderDetails::create([
                    'sales_order_id'      => $salesOrder->id,
                    'product_id'          => $item['product_id'],
                    'batch_id'            => $item['batch_id'],
                    'quantity_ordered'    => $qty,
                    'quantity_delivered'  => 0,
                    'unit_price'          => $price,
                    'discount_percent'    => $discount,
                    'discount_amount'     => $discAmt,
                    'gst_percent'         => $gst,
                    'gst_amount'          => $gstAmt,
                    'total_amount'        => $total,
                    'manufacturing_date'  => $mfgDate,
                    'expiry_date'         => $expDate,
                    'status'              => 'Pending',
                    'created_by'          => auth()->id(),
                ]);
                if ($batch) {
                    \App\Models\FinishedGoodStockLedger::addEntry([
                        'date'             => $request->date ?? now()->toDateString(),
                        'product_id'       => $item['product_id'],
                        'batch_id'         => $batch->id,
                        'transaction_type' => 'Sale',
                        'inward_qty'       => 0,
                        'outward_qty'      => $qty,
                        'reference_id'     => $salesOrder->id,
                    ]);

                    // ✅ Batch ki available_quantity bhi kam karo
                    $batch->decrement('available_quantity', $qty);
                }
            }

            // ✅ SalesOrderDetails::create() ke baad ye add karo

            $netAmount = $totalOrderAmt - $overallDiscountAmount;
            if ($netAmount < 0) $netAmount = 0;

            $salesOrder->update([
                'total_amount'  => $totalOrderAmt,
                'tax_amount'    => $totalGstAmt,
                'net_amount'    => $netAmount,
                'outstanding_amount'  => $productPureTotal,
            ]);


            DB::commit();

            if ($request->is_draft) {
                return redirect()->route('sale-orders.index')
                    ->with('success', 'Sales Order saved as Draft!');
            }

            return redirect()->route('sale-orders.index')
                ->with('success', 'Sales Order created successfully.');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Error creating Sales Order: ' . $e->getMessage());
        }
    }

    public function details($id)
    {
        $order = SalesOrder::with([
            'customer.states',
            'customer.getCustomerAddress.cities',
            'customer.getCustomerAddress.states',
            'customer.getCustomerAddress.countries',
            'salesPerson',
            'branch',
            'paymentTerms',
            'salesOrderDetails.product',

        ])->findOrFail($id);

        return response()->json([
            'encrypted_id'   => encrypt($order->id),
            'code'           => $order->code,
            'date'           => $order->date,
            'customer'       => $order->customer,

            'sales_person'   => $order->salesPerson,
            'branch'         => $order->branch,
            'payment_terms'  => $order->paymentTerms,
            'items'          => $order->salesOrderDetails,
            'net_amount'     => $order->net_amount,
        ]);
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id  = decrypt($id);
        $salesOrder = SalesOrder::with(['customer', 'branch', 'salesPerson', 'paymentTerms', 'createdBy', 'updatedBy'])->findOrFail($id);

        $salesOrderDetails = SalesOrderDetails::where('sales_order_id', $id)->get();

        $salesOrderApprovals = SalesOrderApproval::where('sales_order_id', $id)->get();
        $customerAddress = CustomerAddress::where('customer_id', $salesOrder->customer_id)->first();
        $invoiceOrders = InvoiceOrder::with('invoiceDetails.product')
            ->where('sale_order_id', $id)
            ->latest()
            ->get();

        return view('admin.sales-order.show', compact('salesOrder', 'salesOrderDetails', 'salesOrderApprovals', 'customerAddress', 'invoiceOrders'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $salesOrder = SalesOrder::findOrFail($id);
        $salesOrderDetails = SalesOrderDetails::where('sales_order_id', $id)->get();
        $customers = Customer::where('status', '1')->where('is_blocked', '0')->get();
        $customerCreditLimit = 0;
        if ($salesOrder->customer_id) {
            $customer = Customer::find($salesOrder->customer_id);
            if ($customer) {
                $customerCreditLimit = $customer->credit_limit;
            }
        }
        $customerOutstanding = 0;
        if ($salesOrder->customer_id) {
            $customer = Customer::find($salesOrder->customer_id);
            if ($customer) {
                $customerOutstanding = $customer->net_amount;
            }
        }

        $employees = Employee::where('status', '1')->where('is_login', '1')->get();
        $finishedGoods = FinishedGood::where('status', '1')->get();
        $branches = Branch::where('status', 'Active')->get();
        $paymentTerms = PaymentTerms::where('status', '1')->get();
        return view('admin.sales-order.edit', compact('salesOrder', 'customers', 'employees', 'finishedGoods', 'branches', 'paymentTerms', 'salesOrderDetails', 'customerCreditLimit', 'customerOutstanding'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     DB::beginTransaction();
    //     try {

    //         $salesOrder = SalesOrder::findOrFail($id);

    //         $subtotal = 0;
    //         foreach ($request->items as $it) {
    //             if (!$it['product_id']) continue;
    //             $subtotal += floatval($it['quantity_ordered']) * floatval($it['unit_price']);
    //         }


    //         $overallPercent = floatval($request->overall_discount);
    //         $overallAmount  = floatval($request->overall_bill_discount_amount);

    //         if ($overallPercent < 0) $overallPercent = 0;
    //         if ($overallPercent > 100) $overallPercent = 100;

    //         if ($overallAmount < 0) $overallAmount = 0;

    //         if ($overallPercent > 0 && $subtotal > 0) {
    //             $overallAmount = ($subtotal * $overallPercent) / 100;
    //         }
    //         if ($overallAmount > 0 && $subtotal > 0) {
    //             $overallPercent = ($overallAmount / $subtotal) * 100;
    //             if ($overallPercent > 100) $overallPercent = 100;
    //         }

    //         $type = $request->is_draft ? 'draft' : 'final';

    //         if (!$request->is_draft && empty($salesOrder->code)) {
    //             $salesOrder->code = $this->generateSalesOrderCode();
    //         }

    //         $salesOrder->update([
    //             'date'              => $request->date,
    //             'customer_id'       => $request->customer_id,
    //             'branch_id'         => $request->branch_id,
    //             'payment_terms_id'  => $request->payment_terms_id,
    //             'sales_person_id'   => $request->sales_person_id,
    //             'type'              => $type,
    //             'overall_bill_discount_amount'  => $overallAmount,
    //             'overall_bill_discount_percent' => $overallPercent,
    //             'approval_status'   => 'Pending',
    //             'updated_by'        => auth()->id(),
    //         ]);

    //         $totalOrderAmt = 0;
    //         $totalGstAmt   = 0;

    //         $receivedItemIds = [];

    //         foreach ($request->items as $item) {

    //             if (!$item['product_id']) continue;

    //             $batch = BatchManagement::where('batch_number', $item['batch_id'])
    //                 ->where('product_id', $item['product_id'])
    //                 ->first();

    //             $mfgDate = $batch->manufacturing_date ?? null;
    //             $expDate = $batch->expiry_date ?? null;

    //             $qty        = floatval($item['quantity_ordered']);
    //             $price      = floatval($item['unit_price']);
    //             $discount   = floatval($item['discount_percent']);
    //             $gst        = floatval($item['gst_percent']);

    //             $amount     = $qty * $price;
    //             $discAmt    = $amount * ($discount / 100);
    //             $afterDisc  = $amount - $discAmt;
    //             $gstAmt     = $afterDisc * ($gst / 100);
    //             $total      = $afterDisc + $gstAmt;

    //             $totalOrderAmt += $total;
    //             $totalGstAmt   += $gstAmt;

    //             if (!empty($item['id'])) {

    //                 $detail = SalesOrderDetails::find($item['id']);

    //                 if ($detail) {
    //                     $detail->update([
    //                         'product_id'          => $item['product_id'],
    //                         'batch_id'            => $item['batch_id'],
    //                         'quantity_ordered'    => 1,
    //                         'unit_price'          => $price,
    //                         'discount_percent'    => $discount,
    //                         'discount_amount'     => $discAmt,
    //                         'gst_percent'         => $gst,
    //                         'gst_amount'          => $gstAmt,
    //                         'total_amount'        => $total,
    //                         'manufacturing_date'  => $mfgDate,
    //                         'expiry_date'         => $expDate,
    //                         'status'              => 'Pending',
    //                         'updated_by'          => auth()->id(),
    //                     ]);

    //                     $receivedItemIds[] = $item['id'];
    //                 }

    //             } else {
    //                 $newDetail = SalesOrderDetails::create([
    //                     'sales_order_id'      => $salesOrder->id,
    //                     'product_id'          => $item['product_id'],
    //                     'batch_id'            => $item['batch_id'],
    //                     'quantity_ordered'    => 1,
    //                     'quantity_delivered'  => 0,
    //                     'unit_price'          => $price,
    //                     'discount_percent'    => $discount,
    //                     'discount_amount'     => $discAmt,
    //                     'gst_percent'         => $gst,
    //                     'gst_amount'          => $gstAmt,
    //                     'total_amount'        => $total,
    //                     'manufacturing_date'  => $mfgDate,
    //                     'expiry_date'         => $expDate,
    //                     'status'              => 'Pending',
    //                     'created_by'          => auth()->id(),
    //                 ]);

    //                 $receivedItemIds[] = $newDetail->id;
    //             }
    //         }

    //         SalesOrderDetails::where('sales_order_id', $salesOrder->id)
    //             ->whereNotIn('id', $receivedItemIds)
    //             ->delete();

    //         $salesOrder->update([
    //             'total_amount' => $totalOrderAmt,
    //             'tax_amount'   => $totalGstAmt,
    //             'net_amount'   => $totalOrderAmt,
    //             'outstanding_amount' => $totalOrderAmt,
    //         ]);

    //         DB::commit();

    //         if ($request->is_draft) {
    //             return redirect()->route('sale-orders.index')->with('success', 'Sales Order updated as Draft!');
    //         }

    //         return redirect()->route('sale-orders.index')->with('success', 'Sales Order updated successfully.');

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error updating Sales Order: ' . $e->getMessage()
    //         ]);
    //     }
    // }


    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $salesOrder = SalesOrder::findOrFail($id);

            $subtotal = 0;

            foreach ($request->items as $it) {
                if (!isset($it['product_id']) || !$it['product_id']) continue;

                $qty   = floatval($it['quantity_ordered'] ?? 0);
                $price = floatval($it['unit_price'] ?? 0);

                $subtotal += ($qty * $price);
            }

            // $overallPercent = floatval($request->overall_discount ?? 0);
            // $overallAmount  = ($subtotal * $overallPercent) / 100;

            // if ($overallPercent > 100) $overallPercent = 100;
            // if ($overallPercent < 0)   $overallPercent = 0;


            $overallType = $request->overall_discount_type;
            $overallVal  = floatval($request->overall_discount);
            $overallDiscountAmount = 0;

            if ($overallVal < 0) $overallVal = 0;

            if ($overallType === 'percent') {

                if ($overallVal > 100) $overallVal = 100;

                $overallDiscountAmount = ($subtotal * $overallVal) / 100;
            } elseif ($overallType === 'amount') {

                if ($overallVal > $subtotal) $overallVal = $subtotal;

                $overallDiscountAmount = $overallVal;
            }

            $type = $request->is_draft ? 'draft' : 'final';

            if (!$request->is_draft && empty($salesOrder->code)) {
                $salesOrder->code = $this->generateCode('sales_order');
            }

            $salesOrder->update([
                'date'              => $request->date,
                'customer_id'       => $request->customer_id,
                'branch_id'         => $request->branch_id,
                'payment_terms_id'  => $request->payment_terms_id,
                'sales_person_id'   => $request->sales_person_id,
                'type'              => $type,
                'overall_bill_discount_amount'  => $overallDiscountAmount,
                'overall_bill_discount_percent' => $overallVal,
                'overall_bill_discount_type'    => $overallType,
                'approval_status'   => 'Pending',
                'updated_by'        => auth()->id(),
            ]);

            $totalOrderAmt = 0;
            $totalGstAmt   = 0;

            $receivedIds = [];

            foreach ($request->items as $item) {

                if (!isset($item['product_id']) || !$item['product_id']) continue;

                $qty       = floatval($item['quantity_ordered'] ?? 1);
                $price     = floatval($item['unit_price'] ?? 0);
                $discount  = floatval($item['discount_percent'] ?? 0);
                $gst       = floatval($item['gst_percent'] ?? 0);

                $amount    = ($qty * $price);
                $discAmt   = $amount * ($discount / 100);
                $afterDisc = $amount - $discAmt;
                $gstAmt    = $afterDisc * ($gst / 100);
                $total     = $afterDisc + $gstAmt;

                $totalOrderAmt += $total;
                $totalGstAmt   += $gstAmt;

                $batch = BatchManagement::where('batch_number', $item['batch_id'])
                    ->where('product_id', $item['product_id'])
                    ->first();

                $mfg = $batch->manufacturing_date ?? null;
                $exp = $batch->expiry_date ?? null;


                if (!empty($item['id'])) {
                    $detail = SalesOrderDetails::find($item['id']);

                    if ($detail) {
                        $detail->update([
                            'product_id'          => $item['product_id'],
                            'batch_id'            => $item['batch_id'],
                            'quantity_ordered'    => $qty,
                            'unit_price'          => $price,
                            'discount_percent'    => $discount,
                            'discount_amount'     => $discAmt,
                            'gst_percent'         => $gst,
                            'gst_amount'          => $gstAmt,
                            'total_amount'        => $total,
                            'manufacturing_date'  => $mfg,
                            'expiry_date'         => $exp,
                            'status'              => 'Pending',
                            'updated_by'          => auth()->id(),
                        ]);

                        $receivedIds[] = $detail->id;
                    }
                } else {
                    $new = SalesOrderDetails::create([
                        'sales_order_id'      => $salesOrder->id,
                        'product_id'          => $item['product_id'],
                        'batch_id'            => $item['batch_id'],
                        'quantity_ordered'    => $qty,
                        'quantity_delivered'  => 0,
                        'unit_price'          => $price,
                        'discount_percent'    => $discount,
                        'discount_amount'     => $discAmt,
                        'gst_percent'         => $gst,
                        'gst_amount'          => $gstAmt,
                        'total_amount'        => $total,
                        'manufacturing_date'  => $mfg,
                        'expiry_date'         => $exp,
                        'status'              => 'Pending',
                        'created_by'          => auth()->id(),
                    ]);

                    $receivedIds[] = $new->id;
                }
            }

            SalesOrderDetails::where('sales_order_id', $salesOrder->id)
                ->whereNotIn('id', $receivedIds)
                ->delete();

            $salesOrder->update([
                'total_amount'        => $totalOrderAmt,
                'tax_amount'          => $totalGstAmt,
                'net_amount'          => $totalOrderAmt - $overallDiscountAmount,
                'outstanding_amount'  => $amount,
            ]);

            DB::commit();

            return redirect()->route('sale-orders.index')
                ->with('success', 'Sales Order Updated Successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Error creating Sales Order: ' . $e->getMessage());
        }
    }


    /**
     * Save edited remark for the sales order.
     */
    public function saveEditRemark(Request $request)
    {
        $request->validate([
            'sales_order_id' => 'required|exists:sales_orders,id',
            'remark' => 'required|string|min:3',
        ]);

        SalesOrder::where('id', $request->sales_order_id)
            ->update([
                'remark' => $request->remark,
                'updated_by' => auth()->id(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Change approval status of the sales order.
     */
    public function changeStatus(Request $request, $id)
    {
        $salesOrder = SalesOrder::findOrFail($id);

        $status = $request->status;
        $reason = $request->reason;

        $salesOrder->approval_status = $status;
        $salesOrder->save();

        SalesOrderApproval::create([
            'sales_order_id' => $salesOrder->id,
            'approval_status' => $status,
            'remark'          => $reason,
            'approved_id'     => auth()->id(),
            'action_date'     => now(),
            'created_by'      => auth()->id(),
            'updated_by'      => auth()->id(),

        ]);

        return response()->json(['success' => true]);
    }


    public function salesOrderPdf($id)
    {
        $id = decrypt($id);
        $salesOrder = SalesOrder::with(['customer', 'branch', 'salesPerson', 'paymentTerms', 'createdBy', 'updatedBy'])->findOrFail($id);

        $salesOrderDetails = SalesOrderDetails::where('sales_order_id', $id)->get();

        $salesOrderApprovals = SalesOrderApproval::where('sales_order_id', $id)->get();

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.sales-order.pdf', compact('salesOrder', 'salesOrderDetails', 'salesOrderApprovals'));

        return $pdf->download('sales_order_' . $salesOrder->code . '.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
