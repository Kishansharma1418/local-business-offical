<?php

namespace App\Http\Controllers;

use App\Models\{CreditNote, Customer, Employee, FinishedGood, Branch, PaymentTerms, InvoiceOrder, CreditNoteDetail, InvoiceOrderDetail, CustomerAddress, BatchManagement};
use App\Models\RefundOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CreditNoteController extends Controller
{
    /**
     * Display a listing of the resources.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = CreditNote::with('customer')->orderBy('id', 'DESC');

            if ($request->name) {
                $query->whereHas('customer', function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->name . '%');
                });
            }
            return Datatables::of($query)
                ->addIndexColumn()
                ->filterColumn('user', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        // 🔹 Customer search
                        $q
                            ->whereHas('customer', function ($c) use ($keyword) {
                                $c
                                    ->where('name', 'like', "%{$keyword}%")
                                    ->orWhere('code', 'like', "%{$keyword}%")
                                    ->orWhere('email', 'like', "%{$keyword}%")
                                    ->orWhere('mobile_no', 'like', "%{$keyword}%");
                            })
                            // 🔹 Invoice code search (THIS IS WHAT YOU WANT)
                            ->orWhereHas('invoice', function ($i) use ($keyword) {
                                $i->where('code', 'like', "%{$keyword}%");
                            });
                    });
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M, Y') : '-';
                })
                ->addColumn('code', function ($row) {
                    return $row->credit_note_number ?? 'N/A';
                })
                ->addColumn('code', function ($row) {
                    return $row->invoice?->code ?? '-';
                })
                ->addColumn('user', function ($row) {
                    $user = '<div class="d-flex align-items-center " style="gap:15px;">';

                    $user .= '   <div class="flex-grow-1">';

                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Name:</strong> ' . ($row->customer->name ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Customer Code:</strong> ' . ($row->customer->code ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Email:</strong> ' . ($row->customer->email ?? '-') . '</p>';
                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Phone:</strong> ' . ($row->customer->mobile_no ?? '-') . '</p>';

                    $user .= '   </div>';
                    $user .= '</div>';

                    return $user;
                })
                ->addColumn('credit_type', function ($row) {
                    return ucfirst($row->type);
                })
                ->addColumn('credit_note_date', fn($row) => formatDate($row->credit_note_date))
                ->addColumn('delivered_qty', function ($row) {
                    $totalDelivered = CreditNoteDetail::where('credit_note_id', $row->id)->sum('quantity');
                    return $totalDelivered;
                })
                ->addcolumn('net_amount', function ($row) {
                    return '₹ ' . number_format($row->net_amount, 2);
                })
                //   ->addcolumn('reference_number', function ($row) {
                //     return '₹ ' . number_format($row->reference_number, 2);
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
                ->addColumn('balance_due', function ($row) {
                    $refundAmount = RefundOrder::where('credit_note_id', $row->id)->sum('amount');
                    $balanceDue = $row->net_amount - $refundAmount;
                    return '₹ ' . number_format($balanceDue, 2);
                })
                ->addColumn('action', function ($row) {
                    $type = 'action';
                    return view('admin.credit-note.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action', 'status', 'user', 'approval_status'])
                ->make(true);
        }

        $query = Customer::select('name')->groupBy('name')->get();
        return view('admin.credit-note.index', compact('query'));
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
        $invoice = InvoiceOrder::where('type', 'final')->get();
        return view('admin.credit-note.create', compact('customers', 'employees', 'finishedGoods', 'branches', 'paymentTerms', 'invoice'));
    }

    public function customerInvoices($customerId)
    {
        return InvoiceOrder::where('customer_id', $customerId)
            ->whereHas('invoiceDetails', function ($q) {
                $q
                    ->select(DB::raw('SUM(quantity_delivered)'))
                    ->havingRaw('SUM(quantity_delivered) > 0');
            })
            ->select('id', 'code', 'date', 'net_amount')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function invoiceItems($invoiceId)
    {
        $invoice = InvoiceOrder::with('invoiceDetails.product')->findOrFail($invoiceId);

        return response()->json([
            'items' => $invoice->invoiceDetails->map(function ($item) {
                return [
                    'invoice_detail_id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'batch_no' => $item->batch_id,
                    'quantity' => $item->quantity_delivered,
                    'price' => $item->unit_price,
                    'discount' => $item->discount_percent,
                    'gst' => $item->gst_percent,
                    'total' => $item->total_amount,
                ];
            })
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $rules = [
    //         'customer_id'        => 'required|exists:customers,id',
    //         'branch_id'          => 'required|exists:branches,id',
    //         'credit_note_date'   => 'required|date',
    //         // 'type'               => 'required|in:invoice,direct',
    //         'items'              => 'required|array|min:1',
    //         'items.*.quantity'   => 'required|numeric|min:1',
    //         'items.*.unit_price' => 'required|numeric|min:0',
    //         'items.*.invoice_detail_id' => 'nullable|exists:invoice_order_details,id',
    //     ];
    //     // if ($request->type === 'invoice') {
    //         // $rules['invoice_id'] = 'required|exists:invoice_orders,id';
    //     // }
    //     $request->validate($rules);
    //     DB::beginTransaction();
    //     try {
    //         $invoice = null;
    //         // if ($request->type === 'invoice') {
    //             $invoice = InvoiceOrder::lockForUpdate()->findOrFail($request->invoice_id);
    //         // }
    //         $subtotal = 0;
    //         foreach ($request->items as $item) {
    //             $qty      = (float) $item['quantity'];
    //             $price    = (float) $item['unit_price'];
    //             $discount = (float) ($item['discount_percent'] ?? 0);
    //             $gst      = (float) ($item['gst_percent'] ?? 0);
    //             $amount  = $qty * $price;
    //             $discAmt = $amount * ($discount / 100);
    //             $gstAmt  = ($amount - $discAmt) * ($gst / 100);
    //             $subtotal += ($amount - $discAmt + $gstAmt);
    //         }
    //         // if ($invoice) {
    //         //     if ($subtotal > $invoice->net_amount) {
    //         //         throw new \Exception("Credit Note amount cannot exceed Invoice Net Amount.");
    //         //     }
    //         // }
    //         $creditNote = CreditNote::create([
    //             'credit_note_number' => $this->generateCode('credit_note'),
    //             'customer_id'        => $request->customer_id,
    //             'branch_id'          => $request->branch_id,
    //             'invoice_id'         => $invoice ? $invoice->id : null,
    //             // 'type'               => $request->type ?? '',
    //             'credit_note_date'   => $request->credit_note_date,
    //             'total_amount'       => $subtotal,
    //             'net_amount'         => $subtotal,
    //             'remarks'            => $request->remarks,
    //             'sales_person_id'    => $request->sales_person_id,
    //             'created_by'         => Auth::id(),
    //             'reason_type'        => $request->reason_type,
    //             'reference_number'   => $request->reference_number,
    //             'status'             => 'open',
    //             'payment_status'     => 'Pending',
    //         ]);
    //         // dd($request->items);
    //     //    foreach ($request->items as $item) {
    //     //         $invoiceItem = null;
    //     //         if ($invoice) {
    //     //             $invoiceItem = InvoiceOrderDetail::where('id', $item['invoice_detail_id'])
    //     //                 ->where('invoice_order_id', $invoice->id)
    //     //                 ->first();
    //     //             if (!$invoiceItem) {
    //     //                 throw new \Exception('Invalid invoice item selected.');
    //     //             }
    //     //         }
    //     //         $qty      = (float) $item['quantity'];
    //     //         $price    = (float) $item['unit_price'];
    //     //         $discount = (float) ($item['discount_percent'] ?? 0);
    //     //         $gst      = (float) ($item['gst_percent'] ?? 0);
    //     //         $amount  = $qty * $price;
    //     //         $discAmt = $amount * ($discount / 100);
    //     //         $gstAmt  = ($amount - $discAmt) * ($gst / 100);
    //     //         $total   = $amount - $discAmt + $gstAmt;
    //     //         CreditNoteDetail::create([
    //     //             'credit_note_id'   => $creditNote->id,
    //     //             'product_id'       => $invoiceItem ? $invoiceItem->product_id : ($item['product_id'] ?? null),
    //     //             'batch_id'         => $invoiceItem ? $invoiceItem->batch_id : ($item['batch_id'] ?? null),
    //     //             'quantity'         => $qty,
    //     //             'unit_price'       => $price,
    //     //             'discount_percent' => $discount,
    //     //             'gst_percent'      => $gst,
    //     //             'total_amount'     => $total,
    //     //         ]);
    //     //     }
    //     foreach ($request->items as $item) {
    //             $invoiceItem = null;
    //             if ($invoice && !empty($item['invoice_detail_id'])) {
    //                 $invoiceItem = InvoiceOrderDetail::where('id', $item['invoice_detail_id'])
    //                     ->where('invoice_order_id', $invoice->id)
    //                     ->first();
    //                 if (!$invoiceItem) {
    //                     throw new \Exception('Invalid invoice item selected.');
    //                 }
    //             }
    //             $qty      = (float) $item['quantity'];
    //             $price    = (float) $item['unit_price'];
    //             $discount = (float) ($item['discount_percent'] ?? 0);
    //             $gst      = (float) ($item['gst_percent'] ?? 0);
    //             $amount  = $qty * $price;
    //             $discAmt = $amount * ($discount / 100);
    //             $gstAmt  = ($amount - $discAmt) * ($gst / 100);
    //             $total   = $amount - $discAmt + $gstAmt;
    //             CreditNoteDetail::create([
    //                 'credit_note_id'   => $creditNote->id,
    //                 'product_id'       => $invoiceItem
    //                                         ? $invoiceItem->product_id
    //                                         : ($item['product_id'] ?? null),
    //                 'batch_id'         => $invoiceItem
    //                                         ? $invoiceItem->batch_id
    //                                         : ($item['batch_id'] ?? null),
    //                 'quantity'         => $qty,
    //                 'unit_price'       => $price,
    //                 'discount_percent' => $discount,
    //                 'gst_percent'      => $gst,
    //                 'total_amount'     => $total,
    //             ]);
    //         }
    //         // if ($invoice) {
    //         //     $invoice->net_amount -= $subtotal;
    //         //     if ($invoice->net_amount <= 0) {
    //         //         $invoice->net_amount = 0;
    //         //         $invoice->payment_status = 'Paid';
    //         //     } else {
    //         //         $invoice->payment_status = 'Partial';
    //         //     }
    //         //     $invoice->updated_by = Auth::id();
    //         //     $invoice->save();
    //         // }
    //         DB::commit();
    //         return redirect()
    //             ->route('credit-notes.index')
    //             ->with('success', 'Credit Note created successfully.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()
    //             ->withInput()
    //             ->withErrors(['error' => $e->getMessage()]);
    //     }
    // }
    public function store(Request $request)
    {
        // $rules = [
        //     'customer_id'        => 'required|exists:customers,id',
        //     'branch_id'          => 'required|exists:branches,id',
        //     'credit_note_date'   => 'required|date',

        //     // 'items'              => 'required|array|min:1',
        //     // 'items.*.quantity'   => 'required|numeric|min:1',
        //     // 'items.*.unit_price' => 'required|numeric|min:0',

        //     // 'items.*.invoice_detail_id' => 'nullable|exists:invoice_order_details,id',

        //     // 'items.*.product_id' => 'nullable|exists:finished_goods,id',
        //     // 'items.*.batch_id'   => 'nullable|exists:batch_management,id',
        // ];

        // if ($request->invoice_id) {
        //     $rules['invoice_id'] = 'exists:invoice_orders,id';
        // }

        // $request->validate($rules);

        $rules = [
            'customer_id' => 'required|exists:customers,id',
            'branch_id' => 'required|exists:branches,id',
            'credit_note_date' => 'required|date',
        ];

        if ($request->invoice_id) {
            $rules['invoice_id'] = 'exists:invoice_orders,id';
        } else {
            $rules['items'] = 'required|array|min:1';
            $rules['items.*.quantity'] = 'required|numeric|min:1';
            $rules['items.*.unit_price'] = 'required|numeric|min:0';

            $rules['items.*.product_id'] = 'required|exists:finished_goods,id';
            $rules['items.*.batch_id'] = 'required';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            $invoice = null;
            if ($request->invoice_id) {
                $invoice = InvoiceOrder::lockForUpdate()
                    ->findOrFail($request->invoice_id);
            }

            $subtotal = 0;

            foreach ($request->items as $item) {
                $qty = (float) $item['quantity'];
                $price = (float) $item['unit_price'];
                $discount = (float) ($item['discount_percent'] ?? 0);
                $gst = (float) ($item['gst_percent'] ?? 0);

                $amount = $qty * $price;
                $discAmt = $amount * ($discount / 100);
                $gstAmt = ($amount - $discAmt) * ($gst / 100);

                $subtotal += ($amount - $discAmt + $gstAmt);
            }

            $creditNote = CreditNote::create([
                'credit_note_number' => $this->generateCode('credit_note'),
                'customer_id' => $request->customer_id,
                'branch_id' => $request->branch_id,
                'invoice_id' => $invoice?->id,
                'credit_note_date' => $request->credit_note_date,
                'total_amount' => $subtotal,
                'net_amount' => $subtotal,
                'remarks' => $request->remarks,
                'sales_person_id' => $request->sales_person_id,
                'reason_type' => $request->reason_type,
                'reference_number' => $request->reference_number,
                'status' => 'open',
                'payment_status' => 'Pending',
                'created_by' => Auth::id(),
            ]);

            foreach ($request->items as $item) {
                $invoiceItem = null;

                $isManual = empty($item['invoice_detail_id']) ? 1 : 0;

                if (!$isManual && $invoice) {
                    $invoiceItem = InvoiceOrderDetail::where('id', $item['invoice_detail_id'])
                        ->where('invoice_order_id', $invoice->id)
                        ->firstOrFail();
                }

                $isManual = empty($item['invoice_detail_id']) ? 1 : 0;

                if ($isManual) {
                    if (empty($item['product_id']) || empty($item['batch_id'])) {
                        throw new \Exception('Product and Batch are required for manual items.');
                    }
                }

                $qty = (float) $item['quantity'];
                $price = (float) $item['unit_price'];
                $discount = (float) ($item['discount_percent'] ?? 0);
                $gst = (float) ($item['gst_percent'] ?? 0);

                $amount = $qty * $price;
                $discAmt = $amount * ($discount / 100);
                $gstAmt = ($amount - $discAmt) * ($gst / 100);
                $total = $amount - $discAmt + $gstAmt;

                $batch = BatchManagement::where('batch_number', $invoiceItem
                        ? $invoiceItem->batch_id
                        : $item['batch_id'])
                    ->first();
                $mfgDate = $batch ? $batch->manufacturing_date : null;
                $expDate = $batch ? $batch->expiry_date : null;

                CreditNoteDetail::create([
                    'credit_note_id' => $creditNote->id,
                    'invoice_detail_id' => $item['invoice_detail_id'] ?? null,
                    'is_manual_item' => $isManual,
                    'product_id' => $invoiceItem
                        ? $invoiceItem->product_id
                        : $item['product_id'],
                    'batch_id' => $invoiceItem
                        ? $invoiceItem->batch_id
                        : $item['batch_id'],
                    'manufacturing_date' => $mfgDate,
                    'expiry_date' => $expDate,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'discount_percent' => $discount,
                    'discount_amount' => $discAmt,
                    'gst_percent' => $gst,
                    'gst_amount' => $gstAmt,
                    'total_amount' => $total,
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('credit-notes.index')
                ->with('success', 'Credit Note created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);

        $creditNote = CreditNote::with([
            'creditNoteDetails.product',
            'customer',
            'branch',
            'salesPerson',
            'refundOrders'
        ])->findOrFail($id);

        $customerAddress = CustomerAddress::where('customer_id', $creditNote->customer_id)->first();

        $creditNoteDetails = $creditNote->creditNoteDetails;

        $openInvoices = InvoiceOrder::where('customer_id', $creditNote->customer_id)
            ->whereHas('invoiceDetails', function ($q) {
                $q
                    ->select(DB::raw('SUM(quantity_delivered)'))
                    ->havingRaw('SUM(quantity_delivered) > 0');
            })
            ->whereIn('payment_status', ['pending', 'partial'])
            ->orderBy('date')
            ->get();

        return view('admin.credit-note.show', compact(
            'creditNote',
            'creditNoteDetails',
            'openInvoices',
            'customerAddress'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $creditNote = CreditNote::with('creditNoteDetails.product', 'creditNoteDetails.batch')->findOrFail($id);
        $customers = Customer::where('status', '1')->where('is_blocked', '0')->get();
        $employees = Employee::where('status', '1')->where('is_login', '1')->get();
        $finishedGoods = FinishedGood::where('status', '1')->get();
        $branches = Branch::where('status', 'Active')->get();
        return view('admin.credit-note.edit', compact('creditNote', 'customers', 'employees', 'finishedGoods', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     $rules = [
    //         'customer_id'        => 'required|exists:customers,id',
    //         'branch_id'          => 'required|exists:branches,id',
    //         'credit_note_date'   => 'required|date',
    //         'type'               => 'required|in:invoice,direct',
    //         'items'              => 'required|array|min:1',
    //         'items.*.quantity'   => 'required|numeric|min:1',
    //         'items.*.unit_price' => 'required|numeric|min:0',
    //     ];
    //     if ($request->type === 'invoice') {
    //         $rules['invoice_id'] = 'required|exists:invoice_orders,id';
    //     }
    //     $request->validate($rules);
    //     DB::beginTransaction();
    //     try {
    //         $id = decrypt($id);
    //         $creditNote = CreditNote::lockForUpdate()->findOrFail($id);
    //         // if ($creditNote->invoice_id) {
    //         //     $oldInvoice = InvoiceOrder::lockForUpdate()->find($creditNote->invoice_id);
    //         //     if ($oldInvoice) {
    //         //         $oldInvoice->net_amount += $creditNote->net_amount;
    //         //         $oldInvoice->payment_status = 'Partial';
    //         //         $oldInvoice->save();
    //         //     }
    //         // }
    //         $invoice = null;
    //         if ($request->type === 'invoice') {
    //             $invoice = InvoiceOrder::lockForUpdate()->findOrFail($request->invoice_id);
    //         }
    //         $subtotal = 0;
    //         foreach ($request->items as $item) {
    //             $qty      = (float) $item['quantity'];
    //             $price    = (float) $item['unit_price'];
    //             $discount = (float) ($item['discount_percent'] ?? 0);
    //             $gst      = (float) ($item['gst_percent'] ?? 0);
    //             $amount  = $qty * $price;
    //             $discAmt = $amount * ($discount / 100);
    //             $gstAmt  = ($amount - $discAmt) * ($gst / 100);
    //             $subtotal += ($amount - $discAmt + $gstAmt);
    //         }
    //         $creditNote->update([
    //             'customer_id'      => $request->customer_id,
    //             'branch_id'        => $request->branch_id,
    //             'invoice_id'       => $invoice ? $invoice->id : null,
    //             'type'             => $request->type,
    //             'credit_note_date' => $request->credit_note_date,
    //             'total_amount'     => $subtotal,
    //             'net_amount'       => $subtotal,
    //             'remarks'          => $request->remarks,
    //             'reason_type'      => $request->reason_type,
    //             'reference_number'  => $request->reference_number,
    //             'sales_person_id'  => $request->sales_person_id,
    //             'updated_by'       => Auth::id(),
    //         ]);
    //         CreditNoteDetail::where('credit_note_id', $creditNote->id)->delete();
    //         // dd( $request->items);
    //         foreach ($request->items as $item) {
    //             $invoiceItem = null;
    //             // if ($invoice) {
    //             //     $invoiceItem = InvoiceOrderDetail::where('id', $item['invoice_detail_id'])
    //             //         ->where('invoice_order_id', $invoice->id)
    //             //         ->first();
    //             //     if (!$invoiceItem) {
    //             //         throw new \Exception('Invalid invoice item selected.');
    //             //     }
    //             // }
    //             $qty      = (float) $item['quantity'];
    //             $price    = (float) $item['unit_price'];
    //             $discount = (float) ($item['discount_percent'] ?? 0);
    //             $gst      = (float) ($item['gst_percent'] ?? 0);
    //             $amount  = $qty * $price;
    //             $discAmt = $amount * ($discount / 100);
    //             $gstAmt  = ($amount - $discAmt) * ($gst / 100);
    //             $total   = $amount - $discAmt + $gstAmt;
    //             CreditNoteDetail::create([
    //                 'credit_note_id'   => $creditNote->id,
    //                 'product_id'       => $invoiceItem ? $invoiceItem->product_id : ($item['product_id'] ?? null),
    //                 'batch_id'         => $invoiceItem ? $invoiceItem->batch_id : ($item['batch_id'] ?? null),
    //                 'quantity'         => $qty,
    //                 'unit_price'       => $price,
    //                 'discount_percent' => $discount,
    //                 'gst_percent'      => $gst,
    //                 'total_amount'     => $total,
    //             ]);
    //         }
    //         // if ($invoice) {
    //         //     $invoice->net_amount -= $subtotal;
    //         //     if ($invoice->net_amount <= 0) {
    //         //         $invoice->net_amount = 0;
    //         //         $invoice->payment_status = 'Paid';
    //         //     } else {
    //         //         $invoice->payment_status = 'Partial';
    //         //     }
    //         //     $invoice->updated_by = Auth::id();
    //         //     $invoice->save();
    //         // }
    //         DB::commit();
    //         return redirect()
    //             ->route('credit-notes.index')
    //             ->with('success', 'Credit Note updated successfully.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()
    //             ->withInput()
    //             ->withErrors(['error' => $e->getMessage()]);
    //     }
    // }
    public function update(Request $request, $id)
    {
        $rules = [
            'customer_id' => 'required|exists:customers,id',
            'branch_id' => 'required|exists:branches,id',
            'credit_note_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.invoice_detail_id' => 'nullable|exists:invoice_order_details,id',
            'items.*.product_id' => 'nullable|exists:finished_goods,id',
        ];

        if ($request->invoice_id) {
            $rules['invoice_id'] = 'exists:invoice_orders,id';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            $id = decrypt($id);

            $creditNote = CreditNote::lockForUpdate()->findOrFail($id);
            $invoice = null;
            if ($request->invoice_id) {
                $invoice = InvoiceOrder::lockForUpdate()
                    ->findOrFail($request->invoice_id);
            }

            $subtotal = 0;

            foreach ($request->items as $item) {
                $qty = (float) $item['quantity'];
                $price = (float) $item['unit_price'];
                $discount = (float) ($item['discount_percent'] ?? 0);
                $gst = (float) ($item['gst_percent'] ?? 0);

                $amount = $qty * $price;
                $discAmt = $amount * ($discount / 100);
                $gstAmt = ($amount - $discAmt) * ($gst / 100);

                $subtotal += ($amount - $discAmt + $gstAmt);
            }

            $creditNote->update([
                'customer_id' => $request->customer_id,
                'branch_id' => $request->branch_id,
                'invoice_id' => $invoice?->id,
                'credit_note_date' => $request->credit_note_date,
                'total_amount' => $subtotal,
                'net_amount' => $subtotal,
                'remarks' => $request->remarks,
                'sales_person_id' => $request->sales_person_id,
                'reason_type' => $request->reason_type,
                'reference_number' => $request->reference_number,
                'updated_by' => Auth::id(),
            ]);

            CreditNoteDetail::where('credit_note_id', $creditNote->id)->delete();

            foreach ($request->items as $item) {
                $invoiceItem = null;
                $isManual = empty($item['invoice_detail_id']);

                if (!$isManual && $invoice) {
                    $invoiceItem = InvoiceOrderDetail::where('id', $item['invoice_detail_id'])
                        ->where('invoice_order_id', $invoice->id)
                        ->firstOrFail();
                }

                if ($isManual) {
                    if (empty($item['product_id']) || empty($item['batch_id'])) {
                        throw new \Exception('Product and Batch required for manual item.');
                    }
                }

                $qty = (float) $item['quantity'];
                $price = (float) $item['unit_price'];
                $discount = (float) ($item['discount_percent'] ?? 0);
                $gst = (float) ($item['gst_percent'] ?? 0);

                $amount = $qty * $price;
                $discAmt = $amount * ($discount / 100);
                $gstAmt = ($amount - $discAmt) * ($gst / 100);
                $total = $amount - $discAmt + $gstAmt;

                $batch = BatchManagement::where('batch_number', $invoiceItem
                        ? $invoiceItem->batch_id
                        : $item['batch_id'])
                    ->first();
                $mfgDate = $batch ? $batch->manufacturing_date : null;
                $expDate = $batch ? $batch->expiry_date : null;

                CreditNoteDetail::create([
                    'credit_note_id' => $creditNote->id,
                    'invoice_detail_id' => $item['invoice_detail_id'] ?? null,
                    'is_manual_item' => $isManual ? 1 : 0,
                    'product_id' => $invoiceItem
                        ? $invoiceItem->product_id
                        : $item['product_id'],
                    'batch_id' => $invoiceItem
                        ? $invoiceItem->batch_id
                        : $item['batch_id'],
                    'quantity' => $qty,
                    'manufacturing_date' => $mfgDate,
                    'expiry_date' => $expDate,
                    'unit_price' => $price,
                    'discount_percent' => $discount,
                    'discount_amount' => $discAmt,
                    'gst_percent' => $gst,
                    'gst_amount' => $gstAmt,
                    'total_amount' => $total,
                    'created_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()
                ->route('credit-notes.index')
                ->with('success', 'Credit Note updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function creditNotePdf($id)
    {
        $id = decrypt($id);

        $creditNote = CreditNote::with([
            'creditNoteDetails.product',
            'customer',
            'branch',
            'salesPerson'
        ])->findOrFail($id);

        $customerAddress = CustomerAddress::where('customer_id', $creditNote->customer_id)->first();

        $creditNoteDetails = $creditNote->creditNoteDetails;

        $pdf = \PDF::loadView('admin.credit-note.pdf', compact(
            'creditNote',
            'creditNoteDetails',
            'customerAddress'
        ));

        return $pdf->download('credit_note_' . $creditNote->credit_note_number . '.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CreditNot $creditNot)
    {
        //
    }

    public function details($id)
    {
        $creditNote = CreditNote::with([
            'customer.states',
            'customer.getCustomerAddress.cities',
            'customer.getCustomerAddress.states',
            'creditNoteDetails.product',
        ])->findOrFail($id);

        return response()->json([
            'credit_note_number' => $creditNote->credit_note_number,
            'credit_note_date' => $creditNote->credit_note_date,
            'net_amount' => $creditNote->net_amount,
            'customer' => $creditNote->customer,
            'credit_note_details' => $creditNote->creditNoteDetails,
            // PDF download ke liye
            'encrypted_id' => encrypt($creditNote->id),
        ]);
    }

    public function storeRefundOrder(Request $request)
    {
        $request->validate([
            'credit_note_id' => 'required',
            'customer_id' => 'required',
            'refund_order_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required'
        ]);

        DB::beginTransaction();

        try {
            $creditNote = CreditNote::findOrFail($request->credit_note_id);

            $refundedAmount = RefundOrder::where('credit_note_id', $creditNote->id)->sum('amount');

            $remainingBalance = $creditNote->net_amount - $refundedAmount;

            if ($request->amount > $remainingBalance) {
                return response()->json([
                    'status' => false,
                    'message' => 'Refund amount exceeds available balance'
                ], 422);
            }

            RefundOrder::create([
                'refund_order_number' => 'RF-' . now()->format('YmdHis'),
                'customer_id' => $request->customer_id,
                'credit_note_id' => $creditNote->id,
                'refund_order_date' => $request->refund_order_date,
                'amount' => $request->amount,
                'balance' => $remainingBalance - $request->amount,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'remarks' => $request->remarks,
                'status' => 'completed',
                'created_by' => auth()->id(),
            ]);

            if (($remainingBalance - $request->amount) <= 0) {
                $creditNote->update(['status' => 'closed']);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Refund created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function applyToInvoice(Request $request, $id)
    {
        $creditNoteId = decrypt($id);
        $creditNote = CreditNote::with('refundOrders')->findOrFail($creditNoteId);

        $request->validate([
            'apply' => 'required|array',
            'apply.*' => 'nullable|numeric|min:0.01'
        ]);

        DB::beginTransaction();

        try {
            $remainingCredit = $creditNote->balance_due;
            $totalApplied = 0;

            foreach ($request->apply as $invoiceId => $amount) {
                if (empty($amount) || $amount <= 0) {
                    continue;
                }

                $invoice = InvoiceOrder::lockForUpdate()->findOrFail($invoiceId);

                // if ($amount > $invoice->balance_amount) {
                //     throw new \Exception(
                //         "Applied amount exceeds Invoice balance ({$invoice->code})"
                //     );
                // }

                $invoiceMax = $invoice->balance_amount > 0
                    ? $invoice->balance_amount
                    : $invoice->total_amount;

                if ($amount > $invoiceMax) {
                    throw new \Exception(
                        "Applied amount exceeds Invoice balance ({$invoice->code})"
                    );
                }

                if ($amount > $remainingCredit) {
                    throw new \Exception(
                        'Applied amount exceeds available Credit Note balance'
                    );
                }

                // $invoice->balance_amount -= $amount;

                // if ($invoice->balance_amount == 0) {
                //     $invoice->payment_status = 'Paid';
                // }
                $invoice->balance_amount = $invoiceMax - $amount;

                if ($invoice->balance_amount <= 0) {
                    $invoice->balance_amount = 0;
                    $invoice->payment_status = 'Paid';
                } else {
                    $invoice->payment_status = 'Partial';
                }

                $invoice->save();

                $remainingCredit -= $amount;
                $totalApplied += $amount;

                RefundOrder::create([
                    'credit_note_id' => $creditNote->id,
                    'invoice_order_id' => $invoice->id,
                    'customer_id' => $creditNote->customer_id,
                    'refund_order_date' => now(),
                    'refund_order_number' => 'Invoice-' . time(),
                    'payment_method' => 'adjustment',
                    'amount' => $amount,
                    'balance' => $remainingCredit,
                    'remarks' => 'Credit Note applied to Invoice',
                    'status' => 'applied',
                    'created_by' => Auth::id(),
                ]);
            }

            $creditNote->balance_due = $remainingCredit;
            $creditNote->status = $remainingCredit == 0 ? 'closed' : 'open';
            $creditNote->used_amount += $totalApplied;
            $creditNote->save();

            DB::commit();

            return redirect()
                ->route('credit-notes.show', encrypt($creditNote->id))
                ->with('success', 'Credit Note applied successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
