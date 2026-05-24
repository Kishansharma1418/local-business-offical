<?php

namespace App\Http\Controllers;

use App\Models\{PurchaseOrder, Vendor, Broker, Branch, Currency, PaymentTerms, Uom, RawMaterial, PurchaseOrderDetail, GstRate, PurchaseOrderApprovel, RawMaterailBatch};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PurchaseOrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();

            if ($user->hasRole('admin')) {

                $query = PurchaseOrder::with('vendor')
                    ->orderBy('id', 'DESC');
            } elseif ($user->hasRole('HEAD QA')) {

                $query = PurchaseOrder::with('vendor')
                    ->whereIn('status', ['quarantine', 'in_qa'])
                    ->orderBy('id', 'DESC');
            } elseif ($user->hasRole('Store')) {

                // ✅ ONLY STORE → expected delivery date se sorting
                $query = PurchaseOrder::with('vendor')
                    ->orderByRaw('expected_delivery_date IS NULL, expected_delivery_date ASC')
                    ->orderBy('id', 'DESC');
            } else {

                $query = PurchaseOrder::with('vendor')
                    ->orderBy('id', 'DESC');
            }


            return Datatables::of($query)
                ->filterColumn('user', function ($query, $keyword) {
                    $query->whereHas('vendor', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                            ->orWhere('code', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%");
                    });
                })
                ->addIndexColumn()

              ->editColumn('created_at', fn($row) => formatDate($row->created_at))

                ->addColumn('user', function ($row) {

                    $user  = '<div class="d-flex align-items-center " style="gap:15px;">';

                    $user .= '   <div class="flex-grow-1">';

                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Name:</strong> ' . ($row->vendor->name ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Vendor Code:</strong> ' . ($row->vendor->code ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Email:</strong> ' . ($row->vendor->email ?? '-') . '</p>';
                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Phone:</strong> ' . ($row->vendor->phone ?? '-') . '</p>';

                    $user .= '   </div>';
                    $user .= '</div>';

                    return $user;
                })
                ->editColumn('status', function ($row) {

                    $status = strtolower($row->status);

                    $badge = 'secondary';                 // default badge
                    $label = ucfirst($status);            // default label

                    switch ($status) {
                        case 'draft':
                            $badge = 'secondary';
                            $label = 'Pending Approval';
                            break;

                        case 'approved':
                            $badge = 'info';
                            $label = 'Pending Purchase';
                            break;

                        case 'sent':
                            $badge = 'primary';
                            $label = 'Issue to Vendor';
                            break;

                        case 'accepted':
                            $badge = 'success';
                            $label = 'Store Check';
                            break;

                        case 'partialreceived':
                            $badge = 'warning';
                            $label = 'Partial Received';
                            break;

                        case 'completed':
                            $badge = 'success';
                            $label = 'Completed';
                            break;

                        case 'rejected':
                            $badge = 'danger';
                            $label = 'Rejected';
                            break;

                        case 'quarantine':
                            $badge = 'dark';
                            $label = 'QA Approval';
                            break;

                        case 'in_qa':
                            $badge = 'info';
                            $label = 'In QA';
                            break;
                    }
                    $issueDate =formatDate($row->updated_at, 'd-m-Y h:i A');

                    return '
                        <div>
                            <span class="badge bg-' . $badge . '">' . $label . '</span>
                            <br>
                            <small class="text-muted">' . $issueDate . '</small>
                        </div>
                    ';

                    return '<span class="badge bg-' . $badge . '">' . $label . '</span>';
                })

                ->addColumn('action', function ($row) {
                    $type = "action";
                    return view('admin.purchase-order.action', compact('row', 'type'))->render();
                })
                ->addColumn('expected_delivery_date', function ($row) {
                    return $row->expected_delivery_date
                        ? \Carbon\Carbon::parse($row->expected_delivery_date)->format('d M, Y')
                        : '-';
                })
                ->rawColumns(['action', 'status', 'user', 'approval_status', 'expected_delivery_date'])
                ->make(true);
        }
        $query = Vendor::select('name')->groupBy('name')->get();
        return view('admin.purchase-order.index', compact('query'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Vendor::where('status', 'active')->get();
        $brokers = Broker::where('status', 'Active')->get();
        $bramches = Branch::where('status', 'Active')
            ->where('branch_type', 'Head Office')->get();
        $currencies = Currency::get();
        $paymentTerms = PaymentTerms::where('status', '1')->get();
        $uoms = Uom::where('status', '1')->get();
        $rawMaterials = RawMaterial::with(['category', 'subCategory'])
            ->where('status', '1')
            ->get();
        $defaultCurrencyId = Currency::where('code', 'INR')->value('country');
        $gstRates = GstRate::get();

        return view('admin.purchase-order.create', compact('vendors', 'brokers', 'bramches', 'currencies', 'paymentTerms', 'uoms', 'rawMaterials', 'defaultCurrencyId', 'gstRates'));
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $po = PurchaseOrder::create([
                'po_number'       => $this->generateCode('purchase_order'),
                'po_date'         => $request->po_date,
                'vendor_id'       => $request->vendor_id,
                'broker_id'       => $request->broker_id,
                'branch_id'       => $request->branch_id,
                'currency_id'     => $request->currency_id,
                'delivery_date'   => $request->delivery_date,
                'payment_term_id' => $request->payment_term_id,
                'delivery_term'   => $request->delivery_term,
                'status'          => 'draft',
                'created_by'      => auth()->id(),
            ]);


            $grossTotal     = 0;
            $totalDiscount = 0;
            $totalTax      = 0;

            foreach ($request->items as $item) {

                $qty              = (float) $item['quantity_ordered'];
                $price            = (float) $item['unit_price'];
                $discountPercent  = (float) ($item['discount'] ?? 0);
                $gstPercent       = $item['gst_percent'];

                $rowTotal = $qty * $price;

                $discountAmount = ($rowTotal * $discountPercent) / 100;

                $taxable = $rowTotal - $discountAmount;

                $gstAmount = 0;
                if ($gstPercent !== 'NA') {
                    $gstAmount = $taxable * ((float)$gstPercent / 100);
                }

                $lineTotal = $taxable + $gstAmount;

                PurchaseOrderDetail::create([
                    'purchase_order_id' => $po->id,
                    'raw_material_id'   => $item['raw_material_id'],
                    'quantity_ordered'  => $qty,
                    'uom_id'            => $item['uom_id'],
                    'unit_price'        => $price,

                    'discount_percent'  => $discountPercent,
                    'discount_amount'   => $discountAmount,

                    'gst_percent'       => $gstPercent,
                    'gst_amount'        => $gstAmount,
                    'total_price'       => $lineTotal,
                    'notes'             => $item['notes'] ?? null,
                    'created_by'        => auth()->id(),
                ]);

                $grossTotal     += $rowTotal;
                $totalDiscount  += $discountAmount;
                $totalTax       += $gstAmount;
            }

            $po->update([
                'total_amount'    => $grossTotal,
                'discount_amount' => $totalDiscount,
                'tax_amount'      => $totalTax,
                'net_amount'      => ($grossTotal - $totalDiscount + $totalTax),
            ]);


            DB::commit();
            return redirect()->route('purchase-order.index')
                ->with('success', 'Purchase Order Created Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);
        $purchaseorderapprovals = PurchaseOrderApprovel::where('purchase_order_id', $id)->get();
        $po = PurchaseOrder::with('details')->findOrFail($id);

        return view('admin.purchase-order.show', [
            'po' => $po,
            'vendors' => Vendor::where('status', 'active')->get(),
            'brokers' => Broker::all(),
            'branches' => Branch::all(),
            'currencies' => Currency::all(),
            'paymentTerms' => PaymentTerms::all(),
            'rawMaterials' => RawMaterial::all(),
            'uoms' => Uom::all(),
            'purchaseorderapprovals' => $purchaseorderapprovals,
            'defaultCurrencyId' => 'INR'

        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $po = PurchaseOrder::with('details')->findOrFail($id);
        $vendors = Vendor::where('status', 'active')->get();
        $brokers = Broker::where('status', 'Active')->get();
        $branches = Branch::where('status', 'Active')->where('branch_type','Head Office')->get();
        $currencies = Currency::all();
        $paymentTerms = PaymentTerms::where('status', '1')->get();
        $gstRates = GstRate::all();
        $rawMaterials = RawMaterial::with(['category', 'subCategory'])
            ->where('status', '1')
            ->get();
        $uoms = Uom::where('status', '1')->get();
        $defaultCurrencyId = 'INR';
        return view('admin.purchase-order.edit', compact('po', 'vendors', 'brokers', 'branches', 'currencies', 'paymentTerms', 'uoms', 'rawMaterials', 'defaultCurrencyId', 'gstRates'));
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $po = PurchaseOrder::findOrFail($id);

            $po->update([
                // 'po_number'       => $this->generateCode('purchase_order'),
                'po_date'         => $request->po_date,
                'vendor_id'       => $request->vendor_id,
                'broker_id'       => $request->broker_id,
                'branch_id'       => $request->branch_id,
                'currency_id'     => $request->currency_id,
                'delivery_date'   => $request->delivery_date,
                'payment_term_id' => $request->payment_term_id,
                'delivery_term'   => $request->delivery_term,
                'updated_by'      => auth()->id(),
            ]);

            PurchaseOrderDetail::where('purchase_order_id', $po->id)->delete();

            $grossTotal     = 0;
            $totalDiscount  = 0;
            $totalTax       = 0;

            foreach ($request->items as $item) {

                $qty             = (float) $item['quantity_ordered'];
                $price           = (float) $item['unit_price'];
                $discountPercent = (float) ($item['discount'] ?? 0);
                $gstPercent      = $item['gst_percent'];

                $rowTotal = $qty * $price;

                $discountAmount = ($rowTotal * $discountPercent) / 100;

                $taxable = $rowTotal - $discountAmount;

                $gstAmount = 0;
                if ($gstPercent !== 'NA') {
                    $gstAmount = $taxable * ((float) $gstPercent / 100);
                }

                $lineTotal = $taxable + $gstAmount;

                PurchaseOrderDetail::create([
                    'purchase_order_id' => $po->id,
                    'raw_material_id'   => $item['raw_material_id'],
                    'quantity_ordered'  => $qty,
                    'uom_id'            => $item['uom_id'],
                    'unit_price'        => $price,
                    'discount_percent'  => $discountPercent,
                    'discount_amount'   => $discountAmount,
                    'gst_percent'       => $gstPercent,
                    'gst_amount'        => $gstAmount,
                    'total_price'       => $lineTotal,
                    'notes'             => $item['notes'] ?? null,
                    'updated_by'        => auth()->id(),
                ]);

                $grossTotal    += $rowTotal;
                $totalDiscount += $discountAmount;
                $totalTax      += $gstAmount;
            }

            $po->update([
                'total_amount'    => $grossTotal,
                'discount_amount' => $totalDiscount,
                'tax_amount'      => $totalTax,
                'net_amount'      => ($grossTotal - $totalDiscount + $totalTax),
            ]);

            DB::commit();

            return redirect()
                ->route('purchase-order.index')
                ->with('success', 'Purchase Order Updated Successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }


    public function purchaseOrderPdf($id)
    {
        $id = decrypt($id);
        $purchaseOrder = PurchaseOrder::with('details.rawMaterial', 'details.uom', 'vendor.countries', 'branch', 'currency', 'paymentTerm')->findOrFail($id);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.purchase-order.pdf', compact('purchaseOrder'));
        $filename = 'Purchase_Order_' . $purchaseOrder->po_number . '.pdf';

        return $pdf->download($filename);
    }

    public function changeStatus(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $po = PurchaseOrder::findOrFail($id);

            $newStatus = strtolower($request->status);
            $currentStatus = strtolower($po->status);

            $allowedTransitions = [
                'draft'            => ['approved', 'rejected'],
                'approved'         => ['sent'],
                'sent'             => ['accepted'],
                'accepted'         => ['partialreceived', 'completed'],
                'partialreceived'  => ['completed'],
            ];

            if (
                !isset($allowedTransitions[$currentStatus]) ||
                !in_array($newStatus, $allowedTransitions[$currentStatus])
            ) {
                return response()->json([
                    'message' => 'Invalid status transition'
                ], 422);
            }


            switch ($newStatus) {

                case 'approved':
                    $this->authorize('approve', $po);
                    break;

                case 'sent':
                    $this->authorize('issue', $po);
                    break;

                case 'accepted':
                    $this->authorize('accept', $po);

                    $request->validate([
                        'invoice' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                        'expected_delivery_date' => 'required|date',
                        'invoice_number' => 'required|string|max:255'
                    ]);
                    if ($request->hasFile('invoice')) {

                        $file = $request->file('invoice');
                        $filename = 'invoice_' . $po->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                        $file->move(public_path('uploads/invoices'), $filename);

                        $po->invoice_file = $filename;
                    }
                    $po->invoice_number = $request->invoice_number;
                    $po->expected_delivery_date = $request->expected_delivery_date;

                    break;

                case 'partialreceived':
                case 'completed':
                    $this->authorize('receive', $po);
                    break;

                case 'rejected':
                    $this->authorize('reject', $po);
                    break;
            }

            $po->status = $newStatus;
            $po->updated_by = auth()->id();
            $po->save();

            PurchaseOrderApprovel::create([
                'purchase_order_id' => $po->id,
                'status'            => ucfirst($newStatus),
                'comments'          => $request->reason,
                'approved_id'       => auth()->id(),
                'created_by'        => auth()->id(),
                'updated_by'        => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'You are not authorized'
            ], 403);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }


    // public function receiveItems(Request $request, $id)
    // {
    //     $po = PurchaseOrder::with('details')->findOrFail($id);

    //     if (strtolower(trim($po->status)) === 'quarantine') {
    //         return back()->with('error', 'Items already received.');
    //     }

    //     DB::beginTransaction();

    //     try {

    //         foreach ($request->received_qty as $detailId => $qty) {

    //             if ($qty > 0) {

    //                 $batchNo = $request->batch_no[$detailId] ?? null;

    //                 if (!$batchNo) {
    //                     throw new \Exception("Batch number required.");
    //                 }

    //                 $detail = $po->details->where('id', $detailId)->first();

    //                 $remaining = $detail->quantity_ordered - ($detail->received_quantity ?? 0);

    //                 if ($qty > $remaining) {
    //                     throw new \Exception("Receive qty exceeds remaining qty.");
    //                 }

    //                 $detail->received_quantity += $qty;
    //                 $detail->batch_no = $batchNo;

    //                 $detail->status = 'pending'; 

    //                 $detail->save();
    //             }
    //         }

    //         $po->status = 'quarantine';
    //         $po->save();

    //         DB::commit();

    //         return back()->with('success','Items moved to Quarantine.');

    //     } catch (\Exception $e) {

    //         DB::rollBack();
    //         return back()->with('error',$e->getMessage());
    //     }
    // }

    public function receiveItems(Request $request, $id)
    {
        $po = PurchaseOrder::with('details')->findOrFail($id);

        if (strtolower(trim($po->status)) === 'quarantine') {
            return back()->with('error', 'Items already received.');
        }

        DB::beginTransaction();

        try {

            $anyReceived = false;

            foreach ($request->received_qty as $detailId => $qty) {

                if ($qty > 0) {

                    $detail = $po->details->where('id', $detailId)->first();

                    if (!$detail) continue;

                    $remaining = $detail->quantity_ordered - ($detail->received_quantity ?? 0);

                    if ($qty > $remaining) {
                        throw new \Exception("Receive qty exceeds remaining qty for item: " . $detail->rawMaterial->name);
                    }

                    $batchNo = $request->batch_no[$detailId] ?? null;
                    $mfgDate = $request->mfg_date[$detailId] ?? null;
                    $expiryDate = $request->expiry_date[$detailId] ?? null;

                    if (!$batchNo || !$mfgDate || !$expiryDate) {
                        throw new \Exception("Batch, MFG and Expiry required.");
                    }

                    if ($expiryDate <= $mfgDate) {
                        throw new \Exception("Expiry must be greater than MFG date.");
                    }

                    $detail->received_quantity += $qty;
                    $detail->batch_no = $batchNo;
                    $detail->mfg_date = $mfgDate;
                    $detail->expiry_date = $expiryDate;
                    $detail->status = 'pending';
                    $detail->updated_by = auth()->id();
                    $detail->save();

                    $anyReceived = true;
                }
            }

            if (!$anyReceived) {
                throw new \Exception("Please enter at least one receive quantity.");
            }

            $allReceived = $po->details->every(function ($item) {
                return $item->received_quantity >= $item->quantity_ordered;
            });

            $po->status = $allReceived ? 'quarantine' : 'partialreceived';
            $po->save();
            $this->saveApproval($po->id, $po->status, 'Items received in store');
            DB::commit();

            return back()->with('success', 'Items moved successfully.');
        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }


    // public function storeSample(Request $request, $id)
    // {
    //     $po = PurchaseOrder::with('details')->findOrFail($id);

    //     foreach($request->qa_received_qty as $detailId => $qty) {

    //         $detail = $po->details->where('id', $detailId)->first();

    //         $detail->qa_received_qty = $qty;
    //         $detail->qa_uom_id = $request->qa_uom_id[$detailId];
    //         $detail->qa_status = 'sampled';
    //         $detail->save();
    //     }

    //     $po->status = 'in_qa';
    //     $po->save();

    //     return back()->with('success','Items moved to QA.');
    // }

    public function storeSample(Request $request, $id)
    {
        $po = PurchaseOrder::with('details')->findOrFail($id);

        foreach ($request->qa_received_qty as $detailId => $qty) {

            $detail = $po->details->where('id', $detailId)->first();

            if (!$detail) {
                continue;
            }

            if ($qty > $detail->received_quantity) {
                return back()->with(
                    'error',
                    'Sample quantity cannot be greater than received quantity for item: '
                        . $detail->rawMaterial->name
                );
            }

            if ($qty < 0) {
                return back()->with(
                    'error',
                    'Sample quantity cannot be negative.'
                );
            }

            $detail->qa_received_qty = $qty;
            $detail->qa_uom_id = $request->qa_uom_id[$detailId];
            $detail->qa_status = 'sampled';
            $detail->save();
        }

        $po->status = 'in_qa';
        $po->save();
        $this->saveApproval($po->id, 'in_qa', 'Sample sent to QA');
        return back()->with('success', 'Items moved to QA.');
    }


    public function storeReport(Request $request, $id)
    {
        $po = PurchaseOrder::with('details')->findOrFail($id);

        foreach ($request->qa_status as $detailId => $status) {

            $detail = $po->details->where('id', $detailId)->first();

            $detail->analysis_report_no = $request->analysis_report_no[$detailId];
            $detail->qa_status = $status;
            $detail->qa_remarks = $request->qa_remarks[$detailId] ?? null;

            if ($request->hasFile("qa_report_file.$detailId")) {

                $file = $request->file("qa_report_file.$detailId");

                $filename = time() . '_' . $detailId . '.' . $file->getClientOriginalExtension();

                $file->storeAs('qa_reports', $filename, 'public');

                $detail->qa_report_file = $filename;
            }

            $detail->save();
        }

        $po->status = 'store_check';
        $po->save();
        $this->saveApproval($po->id, 'store_check', 'QA completed');
        return back()->with('success', 'QA Completed with Report Upload.');
    }


    public function generateGrnNumber()
    {
        $lastGrn = RawMaterailBatch::whereNotNull('grn_no')
            ->selectRaw("MAX(CAST(SUBSTRING(grn_no, 5) AS UNSIGNED)) as max_grn")
            ->lockForUpdate()
            ->value('max_grn');

        if (!$lastGrn) {
            return 'GRN-101';
        }

        return 'GRN-' . ($lastGrn + 1);
    }


    private function saveApproval($poId, $status, $comment = null)
    {
        \App\Models\PurchaseOrderApprovel::create([
            'purchase_order_id' => $poId,
            'status'            => ucfirst(str_replace('_', ' ', $status)),
            'comments'          => $comment,
            'approved_id'       => auth()->id(),
            'created_by'        => auth()->id(),
            'updated_by'        => auth()->id(),
        ]);
    }

    public function stockIn(Request $request, $id)
    {
        $po = PurchaseOrder::with('details')->findOrFail($id);

        if (!$request->has('stock_in_items')) {
            return back()->with('error', 'No items selected for stock in.');
        }

        DB::beginTransaction();

        try {

            $grnNumber = $this->generateGrnNumber();

            foreach ($request->stock_in_items as $detailId) {

                $detail = $po->details->where('id', $detailId)->first();

                if ($detail && $detail->qa_status == 'pass') {

                    $receivedQty = $detail->received_quantity ?? 0;
                    $sampleQty   = $detail->qa_received_qty ?? 0;

                    $finalQty = $receivedQty - $sampleQty;

                    // if ($finalQty <= 0) {
                    //     throw new \Exception("Invalid final quantity for item ID {$detailId}");
                    // }

                    $rawMaterial = RawMaterial::find($detail->raw_material_id);

                    if ($rawMaterial) {

                        $oldStock = $rawMaterial->stock_all ?? 0;

                        $rawMaterial->update([
                            'stock_old' => $oldStock,
                            'stock_new' => $finalQty,
                            'branch_id' => $po->branch_id,
                            'stock_all' => $oldStock + $finalQty,
                        ]);
                    }

                    RawMaterailBatch::create([
                        'raw_material_id' => $detail->raw_material_id,
                        'purchase_order_id' => $detail->purchase_order_id,
                        'batch_no' => $detail->batch_no,
                        'branch_id' => $po->branch_id,
                        'uom_id' => $detail->uom_id,
                        'expiry_date' => $detail->expiry_date,
                        'referance_no' => 'PO' . $detail->purchase_order_id . '-' . $detail->id,
                        'analytic_report_no' => $detail->analysis_report_no,
                        'grn_no' => $grnNumber,
                        'quantity' => $finalQty,
                    ]);
                }
            }


            $po->update(['status' => 'completed']);
            $this->saveApproval($po->id, 'completed', 'Stock added to inventory');
            DB::commit();

            return back()->with('success', 'Stock Updated Successfully.');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
