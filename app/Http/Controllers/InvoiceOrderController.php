<?php

namespace App\Http\Controllers;

use App\Models\InvoiceOrder;
use Illuminate\Http\Request;
use App\Models\{SalesOrder, Customer, CustomerAddress, Branch, Employee, PaymentTerms, User, FinishedGood, BatchManagement, CustomerProductDiscount, SalesOrderDetails, SalesOrderApproval, InvoiceOrderDetail, DebitNote, CreditNote};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class InvoiceOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = InvoiceOrder::with('customer')->orderBy('id', 'DESC');

            if ($request->name) {
                $query->whereHas('customer', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->name . '%');
                });
            }
            return Datatables::of($query)
                ->addIndexColumn()
                ->filterColumn('user', function ($query, $keyword) {

                    $query->where(function ($q) use ($keyword) {

                        $q->where('code', 'like', "%{$keyword}%")


                            ->orWhereHas('customer', function ($c) use ($keyword) {
                                $c->where('name', 'like', "%{$keyword}%")
                                    ->orWhere('code', 'like', "%{$keyword}%")
                                    ->orWhere('email', 'like', "%{$keyword}%")
                                    ->orWhere('mobile_no', 'like', "%{$keyword}%");
                            })

                            ->orWhereHas('salesOrder', function ($s) use ($keyword) {
                                $s->where('code', 'like', "%{$keyword}%");
                            });
                    });
                })
                ->addColumn('date', fn($row) => formatDate($row->date))
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M, Y') : '-';
                })
                ->addColumn('code', function ($row) {
                    return $row->code ?? 'N/A';
                })
                ->addColumn('sales_order_number', function ($row) {
                    return $row->salesOrder->code ?? 'N/A';
                })

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
                ->editColumn('payment_status', function ($row) {
                    if ($row->payment_status == 'partial') {
                        return '<span class="badge bg-warning">Partial</span>';
                    } elseif ($row->payment_status == 'Paid') {
                        return '<span class="badge bg-success">Paid</span>';
                    } else {
                        return '<span class="badge bg-secondary">Pending</span>';
                    }
                })

                ->addColumn('delivered_qty', function ($row) {

                    $totals = InvoiceOrderDetail::where('invoice_order_id', $row->id)
                        ->selectRaw('SUM(quantity_delivered) as delivered, SUM(quantity_ordered) as ordered')
                        ->first();

                    return $totals->delivered > 0 ? $totals->delivered : $totals->ordered;
                })

                ->addcolumn('net_amount', function ($row) {

                    return '₹ ' . number_format($row->net_amount, 2);
                })

                // ->addcolumn('balance_due', function ($row) {
                //     $paymentReceived = $row->payments->sum(function($p){
                //         return $p->amount_paid + $p->amount_withheld;
                //     });

                //     $paymentDue = $row->net_amount - $paymentReceived;
                //     return '₹ ' . number_format($paymentDue, 2);
                // })

                ->addColumn('balance_due', function ($row) {

                    $paymentReceived = $row->payments->sum(function ($p) {
                        return $p->amount_paid + $p->amount_withheld;
                    });

                    $creditAdjusted = $row->creditNotes
                        ->sum('used_amount');

                    $balanceDue = $row->net_amount - ($paymentReceived + $creditAdjusted);

                    $balanceDue = max(0, $balanceDue);

                    return '₹ ' . number_format($balanceDue, 2);
                })

                ->addColumn('due_date', fn($row) => formatDate($row->due_date))
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
                    return view('admin.invoice-order.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action', 'status', 'user', 'approval_status', 'payment_status'])
                ->make(true);
        }
        $query = Customer::select('name')->groupBy('name')->get();
        return view('admin.invoice-order.index', compact('query'));
    }


    public function checkCustomerInvoiceCredit(Request $request)
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

        $outstanding = InvoiceOrder::where('customer_id', $customerId)
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('status', '1')->where('is_blocked', '0')->get();
        $employees = Employee::where('status', '1')->where('is_login', '1')->get();
        $finishedGoods = FinishedGood::where('status', '1')->get();
        $branches = Branch::where('status', 'Active')->get();
        $paymentTerms = PaymentTerms::where('status', '1')->get();

        return view('admin.invoice-order.create', compact('customers', 'employees', 'finishedGoods', 'branches', 'paymentTerms'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $salesOrder = SalesOrder::with('salesOrderDetails')
                ->findOrFail($request->sales_order_id);

            $code = $request->is_draft ? null :  $this->generateCode('invoice');
            $type = $request->is_draft ? 'draft' : 'final';

            $invoice = InvoiceOrder::create([
                'code'          => $code ?? null,
                'type'          => $type ?? 'draft',
                'sale_order_id' => $salesOrder->id ?? null,
                'customer_id'   => $salesOrder->customer_id,
                'branch_id'     => $salesOrder->branch_id,
                'sales_person_id' => $salesOrder->sales_person_id,
                'payment_terms_id' => $salesOrder->payment_terms_id,
                'approval_status'   => 'Pending',
                'status'            => 'Pending',
                'date'          => now(),
                'created_by'    => auth()->id(),
            ]);

            $invoiceTotal = 0;

            foreach ($request->items as $detailId => $qty) {

                if ($qty <= 0) continue;

                if (!in_array($detailId, $request->selected_items ?? [])) {
                    continue;
                }


                $detail = SalesOrderDetails::findOrFail($detailId);

                $remainingQty = $detail->quantity_ordered - $detail->quantity_delivered;

                if ($qty > $remainingQty) {
                    throw new \Exception(
                        "Invoice quantity exceeds remaining quantity for product: "
                            . $detail->product->name
                    );
                }

                $amount      = $qty * $detail->unit_price;
                $discAmt     = ($amount * $detail->discount_percent) / 100;
                $afterDisc   = $amount - $discAmt;
                $gstAmt      = ($afterDisc * $detail->gst_percent) / 100;
                $totalAmount = $afterDisc + $gstAmt;



                InvoiceOrderDetail::create([
                    'invoice_order_id'      => $invoice->id,
                    'product_id'            => $detail->product_id,
                    'batch_id'              => $detail->batch_id,
                    'quantity_delivered'    => $qty,
                    'unit_price'            => $detail->unit_price,
                    'discount_percent'      => $detail->discount_percent,
                    'discount_amount'       => $discAmt,
                    'gst_percent'           => $detail->gst_percent,
                    'gst_amount'            => $gstAmt,
                    'total_amount'           => $totalAmount,
                    'amount'               => $amount,
                    'created_by'            => auth()->id(),
                ]);

                $detail->quantity_delivered += $qty;
                $detail->save();

                $invoiceTotal += $totalAmount;
            }

            $invoice->update([
                'total_amount' => $invoiceTotal,
            ]);

            DB::commit();

            return redirect()
                ->route('invoice-orders.edit', encrypt($invoice->id))
                ->with('success', 'Invoice generated successfully. You can now edit the invoice.');
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function saveEditInvoiceRemark(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoice_orders,id',
            'remark'     => 'required|string|min:3',
        ]);


        InvoiceOrder::where('id', $request->invoice_id)
            ->update([
                'remark' => $request->remark,
                'updated_by' => auth()->id()
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);

        $invoice = InvoiceOrder::with([
            'customer',
            'salesOrder',
            'invoiceDetails.product',
            'createdBy',
            'paymentTerms'
        ])->findOrFail($id);

        $customerAddress = CustomerAddress::where('customer_id', $invoice->customer_id)->first();

        $debitNotes = DebitNote::with(['createdBy'])
            ->where('invoice_order_id', $invoice->id)
            ->latest()
            ->get();

        $creditnotes = CreditNote::with(['createdBy'])
            ->where('invoice_id', $invoice->id)
            ->latest()
            ->get();

        $products = FinishedGood::where('status', '1')->get();
        $paymentTerms = PaymentTerms::where('status', 1)->get();
        $employees = Employee::where('status', '1')->where('is_login', '1')->get();



        return view('admin.invoice-order.show', compact(
            'invoice',
            'customerAddress',
            'employees',
            'products',
            'debitNotes',
            'paymentTerms',
            'creditnotes'
        ));
    }


    /**
     * Show the form for editing the specified resource.
     */

    // old corected edit function
    // public function edit($id)
    // {
    //     $id = decrypt($id);

    //     $invoice = InvoiceOrder::with([
    //         'customer',
    //         'branch',
    //         'salesOrder.salesOrderDetails.product',
    //         'invoiceDetails'
    //     ])->findOrFail($id);

    //     $salesOrderDetails = $invoice->salesOrder?->salesOrderDetails;

    //     $maxQtyMap = [];

    //     foreach ($invoice->invoiceDetails as $detail) {

    //         $key = $detail->product_id . '_' . $detail->batch_id;

    //         $soDetail = $salesOrderDetails
    //             ->where('product_id', $detail->product_id)
    //             ->where('batch_id', $detail->batch_id)
    //             ->first();

    //         $maxQtyMap[$key] = $soDetail?->quantity_ordered ?? 0;
    //     }
    //         $paymentTerms = PaymentTerms::where('status', '1')->get();

    //     return view('admin.invoice-order.edit', [
    //         'invoice'        => $invoice,
    //         'invoiceDetails' => $invoice->invoiceDetails,
    //         'salesOrder'     => $invoice->salesOrder,
    //         'salesOrderDetails'=> $salesOrderDetails,
    //         'maxQtyMap'      => $maxQtyMap,
    //         'paymentTerms'   => $paymentTerms,
    //         'customers'      => Customer::where('status','1')->where('is_blocked', '0')->get(),
    //         'branches'       => Branch::where('status', 'Active')->get(),
    //         'finishedGoods'  => FinishedGood::where('status', '1')->get(),
    //     ]);
    // }

    public function edit($id)
    {
        $id = decrypt($id);

        $invoice = InvoiceOrder::with([
            'customer',
            'branch',
            'salesOrder.salesOrderDetails.product',
            'invoiceDetails'
        ])->findOrFail($id);

        $salesOrderDetails = collect();

        if ($invoice->salesOrder) {
            $salesOrderDetails = $invoice->salesOrder->salesOrderDetails;
        }


        $maxQtyMap = [];

        foreach ($invoice->invoiceDetails as $detail) {

            $key = $detail->product_id . '_' . $detail->batch_id;

            if ($salesOrderDetails->isNotEmpty()) {

                $soDetail = $salesOrderDetails
                    ->where('product_id', $detail->product_id)
                    ->where('batch_id', $detail->batch_id)
                    ->first();

                $maxQtyMap[$key] = $soDetail?->quantity_ordered ?? $detail->quantity_delivered;
            } else {
                $maxQtyMap[$key] = $detail->quantity_delivered;
            }
        }

        $paymentTerms = PaymentTerms::where('status', '1')->get();

        return view('admin.invoice-order.edit', [
            'invoice'        => $invoice,
            'invoiceDetails' => $invoice->invoiceDetails,
            'salesOrder'     => $invoice->salesOrder,
            'salesOrderDetails' => $salesOrderDetails,
            'maxQtyMap'      => $maxQtyMap,
            'paymentTerms'   => $paymentTerms,
            'customers'      => Customer::where('status', '1')->where('is_blocked', '0')->get(),
            'branches'       => Branch::where('status', 'Active')->get(),
            'finishedGoods'  => FinishedGood::where('status', '1')->get(),
        ]);
    }


    public function generateInvoiceOrderCode()
    {
        $lastOrder = InvoiceOrder::lockForUpdate()
            ->whereNotNull('code')
            ->orderBy('id', 'desc')
            ->first();

        if (!$lastOrder) {
            return 'Inv-101';
        }

        $lastNumber = (int) str_replace('Inv-', '', $lastOrder->code);

        return 'Inv-' . ($lastNumber + 1);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $invoice = InvoiceOrder::with('invoiceDetails')->findOrFail($id);

            $salesOrder = null;
            if (!empty($invoice->sale_order_id)) {
                $salesOrder = SalesOrder::with('salesOrderDetails')
                    ->where('id', $invoice->sale_order_id)
                    ->first();
            }

            $newType = $request->has('is_draft') ? 'draft' : 'final';


            if (
                $invoice->type === 'draft' &&
                $newType === 'final' &&
                empty($invoice->code)
            ) {
                $invoice->code = $this->generateCode('invoice');
            }

            $invoice->update([
                'customer_id' => $request->customer_id,
                'branch_id'   => $request->branch_id,
                'payment_terms_id' => $request->payment_terms_id,
                'date'        => $request->date,
                'type'        => $newType,
                'updated_by'  => auth()->id(),
            ]);

            foreach ($invoice->invoiceDetails as $oldDetail) {

                if ($invoice->sale_order_id) {

                    $soDetail = SalesOrderDetails::where('product_id', $oldDetail->product_id)
                        ->where('batch_id', $oldDetail->batch_id)
                        ->where('sales_order_id', $invoice->sale_order_id)
                        ->first();

                    if ($soDetail) {
                        $soDetail->quantity_delivered -= $oldDetail->quantity_delivered;

                        if ($soDetail->quantity_delivered < 0) {
                            $soDetail->quantity_delivered = 0;
                        }

                        $soDetail->save();
                    }
                }
            }


            InvoiceOrderDetail::where('invoice_order_id', $invoice->id)->delete();

            $subTotal = 0;

            // foreach ($request->items as $item) {

            //     if (empty($item['product_id']) || empty($item['quantity_delivered'])) {
            //         continue;
            //     }

            //     $qty = (float) $item['quantity_delivered'];
            //     if ($qty <= 0) continue;

            //     if ($salesOrder) {
            //         $soDetail = $salesOrder->salesOrderDetails
            //             ->where('product_id', $item['product_id'])
            //             ->where('batch_id', $item['batch_id'])
            //             ->first();

            //         if (!$soDetail) {
            //             throw new \Exception('Product not found in Sales Order');
            //         }
            //     }

            //     $price = (float) $item['unit_price'];
            //     $disc  = (float) ($item['discount_percent'] ?? 0);
            //     $gst   = (float) ($item['gst_percent'] ?? 0);

            //     $amount    = $qty * $price;
            //     $discAmt   = ($amount * $disc) / 100;
            //     $afterDisc = $amount - $discAmt;
            //     $gstAmt    = ($afterDisc * $gst) / 100;
            //     $total     = $afterDisc + $gstAmt;

            //     InvoiceOrderDetail::create([
            //         'invoice_order_id'   => $invoice->id,
            //         'product_id'         => $item['product_id'],
            //         'batch_id'           => $item['batch_id'],
            //         'quantity_delivered' => $qty,
            //         'unit_price'         => $price,
            //         'discount_percent'   => $disc,
            //         'discount_amount'    => $discAmt,
            //         'gst_percent'        => $gst,
            //         'gst_amount'         => $gstAmt,
            //         'total_amount'       => $total,
            //         'created_by'         => auth()->id(),
            //     ]);

            //     $subTotal += $total;
            // }

            // foreach ($request->items as $item) {

            //         if (
            //             empty($item['product_id']) ||
            //             empty($item['batch_id']) ||
            //             empty($item['quantity_delivered'])
            //         ) {
            //             continue;
            //         }

            //         $qty = (float) $item['quantity_delivered'];
            //         if ($qty <= 0) continue;

            //         $isFromSalesOrder = false;

            //         // if ($salesOrder) {
            //         //     $soDetail = $salesOrder->salesOrderDetails
            //         //         ->where('product_id', $item['product_id'])
            //         //         ->where('batch_id', $item['batch_id'])
            //         //         ->first();

            //         //     if ($soDetail) {
            //         //         $isFromSalesOrder = true;

            //         //         if ($qty > $soDetail->quantity_ordered) {
            //         //             throw new \Exception('Invoice quantity exceeds Sales Order quantity');
            //         //         }
            //         //     }
            //         // }




            //         $price = (float) $item['unit_price'];
            //         $disc  = (float) ($item['discount_percent'] ?? 0);
            //         $gst   = (float) ($item['gst_percent'] ?? 0);

            //         $amount    = $qty * $price;
            //         $discAmt   = ($amount * $disc) / 100;
            //         $afterDisc = $amount - $discAmt;
            //         $gstAmt    = ($afterDisc * $gst) / 100;
            //         $total     = $afterDisc + $gstAmt;

            //         InvoiceOrderDetail::create([
            //             'invoice_order_id'   => $invoice->id,
            //             'product_id'         => $item['product_id'],
            //             'batch_id'           => $item['batch_id'],
            //             'quantity_delivered' => $qty,
            //             'unit_price'         => $price,
            //             'discount_percent'   => $disc,
            //             'discount_amount'    => $discAmt,
            //             'gst_percent'        => $gst,
            //             'gst_amount'         => $gstAmt,
            //             'total_amount'       => $total,
            //             'created_by'         => auth()->id(),
            //         ]);

            //         $subTotal += $total;
            //     }


            foreach ($request->items as $item) {

                if (
                    empty($item['product_id']) ||
                    empty($item['batch_id']) ||
                    empty($item['quantity_delivered'])
                ) {
                    continue;
                }

                $qty = (float) $item['quantity_delivered'];
                if ($qty <= 0) continue;

                $soDetail = null;

                if ($invoice->sale_order_id) {

                    $soDetail = SalesOrderDetails::where('sales_order_id', $invoice->sale_order_id)
                        ->where('product_id', $item['product_id'])
                        ->where('batch_id', $item['batch_id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$soDetail) {
                        throw new \Exception('Product not found in Sales Order');
                    }

                    $remaining = $soDetail->quantity_ordered - $soDetail->quantity_delivered;

                    if ($qty > $remaining) {
                        throw new \Exception(
                            "Invoice quantity exceeds remaining Sales Order quantity. Remaining: {$remaining}"
                        );
                    }

                    $soDetail->quantity_delivered += $qty;
                    $soDetail->save();
                }

                $price = (float) $item['unit_price'];
                $disc  = (float) ($item['discount_percent'] ?? 0);
                $gst   = (float) ($item['gst_percent'] ?? 0);

                $amount    = $qty * $price;
                $discAmt   = ($amount * $disc) / 100;
                $afterDisc = $amount - $discAmt;
                $gstAmt    = ($afterDisc * $gst) / 100;
                $total     = $afterDisc + $gstAmt;

                InvoiceOrderDetail::create([
                    'invoice_order_id'   => $invoice->id,
                    'product_id'         => $item['product_id'],
                    'batch_id'           => $item['batch_id'],
                    'quantity_delivered' => $qty,
                    'unit_price'         => $price,
                    'discount_percent'   => $disc,
                    'discount_amount'    => $discAmt,
                    'gst_percent'        => $gst,
                    'gst_amount'         => $gstAmt,
                    'total_amount'       => $total,
                    'created_by'         => auth()->id(),
                ]);

                $subTotal += $total;
            }



            $discount = 0;

            if ($request->overall_discount_type === 'percent') {
                $discount = ($subTotal * min($request->overall_discount, 100)) / 100;
            }

            if ($request->overall_discount_type === 'amount') {
                $discount = min($request->overall_discount, $subTotal);
            }

            $invoice->update([
                'total_amount' => $subTotal,
                'net_amount'   => $subTotal - $discount,
                'overall_bill_discount_type'    => $request->overall_discount_type,
                'overall_bill_discount_percent' => $request->overall_discount,
                'overall_bill_discount_amount'  => $discount,
            ]);

            DB::commit();

            return redirect()
                ->route('invoice-orders.index', encrypt($invoice->id))
                ->with('success', 'Invoice Updated Successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            return back()->withInput()->with('error', $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvoiceOrder $invoiceOrder)
    {
        //
    }
    public function invoiceDetails($id)
    {
        $invoice = InvoiceOrder::with([
            'customer.states',
            'customer.getCustomerAddress.cities',
            'customer.getCustomerAddress.states',
            'customer.getCustomerAddress.countries',
            'branch',
            'salesPerson',
            'paymentTerms',
            'invoiceDetails.product'
        ])->findOrFail($id);

        return response()->json($invoice);
    }

    public function invoiceOrderPdf($id)
    {
        $id = decrypt($id);

        $invoiceOrder = InvoiceOrder::with([
            'customer',
            'salesOrder',
            'invoiceDetails.product',
            'createdBy',
            'payments',

        ])->findOrFail($id);

        $invoiceOrderDetails = $invoiceOrder->invoiceDetails;
        $customerAddress = CustomerAddress::where('customer_id', $invoiceOrder->customer_id)->first();


        $pdf = \PDF::loadView('admin.invoice-order.pdf', compact(
            'invoiceOrder',
            'invoiceOrderDetails',
            'customerAddress'
        ));

        return $pdf->download('invoice_order.pdf');
    }


    public function storeInvoiceDirectly(Request $request)
    {
        DB::beginTransaction();

        try {

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

            $code = $request->is_draft ? null :  $this->generateCode('invoice');
            $type = $request->is_draft ? 'draft' : 'final';

            $invoiceOrder = InvoiceOrder::create([
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

                InvoiceOrderDetail::create([
                    'invoice_order_id'      => $invoiceOrder->id,
                    'product_id'          => $item['product_id'],
                    'batch_id'            => $item['batch_id'],
                    'quantity_ordered'    => 0,
                    'quantity_delivered'  => $qty,
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
            }



            $netAmount = $totalOrderAmt - $overallDiscountAmount;
            if ($netAmount < 0) $netAmount = 0;

            $invoiceOrder->update([
                'total_amount'  => $totalOrderAmt,
                'tax_amount'    => $totalGstAmt,
                'net_amount'    => $netAmount,
                'outstanding_amount'  => $productPureTotal,
            ]);


            DB::commit();

            if ($request->is_draft) {
                return redirect()->route('invoice-orders.index')
                    ->with('success', 'Invoice Order saved as Draft!');
            }

            return redirect()->route('invoice-orders.index')
                ->with('success', 'Invoice Order created successfully.');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Error creating Sales Order: ' . $e->getMessage());
        }
    }
}
