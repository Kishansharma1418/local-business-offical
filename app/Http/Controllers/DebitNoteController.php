<?php

namespace App\Http\Controllers;

use App\Models\{DebitNoteDetail, BatchManagement};
use App\Models\DebitNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebitNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function batches($productId)
    {
        return response()->json(
            BatchManagement::where('product_id', $productId)
                ->get([
                    'id',
                    'batch_number',
                    'manufacturing_date',
                    'expiry_date',
                    'available_quantity',
                    'unit_cost',
                    'gst_percent',
                ])
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $debitNote = DebitNote::create([
                'debit_note_number' => 'DN-' . now()->format('Ymd') . '-' . rand(1000, 9999),
                'customer_id' => $request->customer_id,
                'invoice_order_id' => $request->invoice_order_id,
                'branch_id' => $request->branch_id,
                'sales_person_id' => $request->sales_person_id,
                'payment_term_id' => $request->payment_terms_id,
                'debit_note_date' => $request->debit_note_date,
                'reference_number' => $request->reference_number,
                'reason_type' => $request->reason_type,
                'created_by' => auth()->id(),
            ]);

            $total = 0;
            $tax = 0;

            foreach ($request->items as $item) {
                $amount = $item['quantity'] * $item['unit_price'];
                $discAmt = $amount * (($item['discount_percent'] ?? 0) / 100);
                $gstAmt = ($amount - $discAmt) * (($item['gst_percent'] ?? 0) / 100);
                $net = $amount - $discAmt + $gstAmt;

                $batch = BatchManagement::where('batch_number', $item['batch_id'])
                    ->first();
                $mfgDate = $batch ? $batch->manufacturing_date : null;
                $expDate = $batch ? $batch->expiry_date : null;

                DebitNoteDetail::create([
                    'debit_note_id' => $debitNote->id,
                    'product_id' => $item['product_id'],
                    'batch_id' => $item['batch_id'],
                    'quantity' => $item['quantity'],
                    'manufacturing_date' => $mfgDate ?? null,
                    'expiry_date' => $expDate ?? null,
                    'unit_price' => $item['unit_price'] ?? 0,
                    'discount_percent' => $item['discount_percent'] ?? 0,
                    'discount_amount' => $discAmt,
                    'gst_percent' => $item['gst_percent'] ?? 0,
                    'gst_amount' => $gstAmt,
                    'total_amount' => $net,
                    'created_by' => auth()->id(),
                ]);

                $total += $net;
                $tax += $gstAmt;
            }

            $debitNote->update([
                'total_amount' => $total,
                'tax_amount' => $tax,
                'net_amount' => $total
            ]);
        });

        return back()->with('success', 'Debit Note created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(DebitNote $debitNote)
    {
        //
    }

    public function details($id)
    {
        $debitNote = DebitNote::with(['customer.getCustomerAddress.cities',
            'customer.getCustomerAddress.states',
            'customer.getCustomerAddress.countries',
            'salesPerson',
            'branch',
            'customer',
            'debitNoteDetails.product'])->findOrFail($id);

        return response()->json($debitNote);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dn = DebitNote::with('debitNoteDetails.product')->findOrFail($id);
        return response()->json($dn);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $debitNote = DebitNote::lockForUpdate()->findOrFail($id);

            $debitNote->update([
                'debit_note_date' => $request->debit_note_date,
                'reference_number' => $request->reference_number,
                'reason_type' => $request->reason_type,
                'payment_term_id' => $request->payment_terms_id,
                'updated_by' => auth()->id(),
            ]);

            $debitNote->debitNoteDetails()->delete();

            $total = 0;
            $tax = 0;

            foreach ($request->items as $item) {
                $amount = $item['quantity'] * $item['unit_price'];
                $discAmt = $amount * (($item['discount_percent'] ?? 0) / 100);
                $gstAmt = ($amount - $discAmt) * (($item['gst_percent'] ?? 0) / 100);
                $net = $amount - $discAmt + $gstAmt;

                $batch = BatchManagement::where('batch_number', $item['batch_id'])->first();

                $mfgDate = $batch ? $batch->manufacturing_date : null;
                $expDate = $batch ? $batch->expiry_date : null;

                DebitNoteDetail::create([
                    'debit_note_id' => $debitNote->id,
                    'product_id' => $item['product_id'],
                    'manufacturing_date' => $mfgDate ?? null,
                    'expiry_date' => $expDate ?? null,
                    'batch_id' => $item['batch_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_percent' => $item['discount_percent'],
                    'discount_amount' => $discAmt,
                    'gst_percent' => $item['gst_percent'],
                    'gst_amount' => $gstAmt,
                    'total_amount' => $net,
                    'created_by' => auth()->id(),
                ]);

                $total += $net;
                $tax += $gstAmt;
            }

            $debitNote->update([
                'total_amount' => $total,
                'tax_amount' => $tax,
                'net_amount' => $total,
            ]);
        });

        return back()->with('success', 'Debit Note updated successfully');
    }

    public function debitNotePdf($id)
    {
        $debitNote = DebitNote::with([
            'customer.getCustomerAddress.cities',
            'customer.getCustomerAddress.states',
            'customer.getCustomerAddress.countries',
            'salesPerson',
            'branch',
            'customer',
            'debitNoteDetails.product'
        ])->findOrFail($id);

        $debitNoteDetails = $debitNote->debitNoteDetails;

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.invoice-order.debit-note.pdf', compact('debitNote', 'debitNoteDetails'));
        return $pdf->download('debit_note_' . $debitNote->debit_note_number . '.pdf');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DebitNote $debitNote)
    {
        //
    }
}
