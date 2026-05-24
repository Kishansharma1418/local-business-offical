<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Payment,Customer,InvoiceOrder};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
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
        $customers = Customer::where('status','1')->where('is_blocked', '0')->get();
        return view('admin.payment.create',compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_order_id' => 'required|exists:invoice_orders,id',
            'amount_paid'      => 'required|numeric', 
        ]);

        DB::beginTransaction();
        try {

            $invoice = InvoiceOrder::lockForUpdate()->findOrFail($request->invoice_order_id);

            $totalReceived = $invoice->payments()
                ->selectRaw('SUM(amount_paid + amount_withheld) as total')
                ->value('total');

            $remaining = $invoice->total_amount - $totalReceived;

            $grossAmount = $request->amount_paid;
            $withheld    = $request->tax_deduction === 'yes'
            ? ($request->amount_withheld ?? 0)
            : 0;

            $gross = $grossAmount + $withheld;

            if ($gross > $remaining + 0.01) {
                return back()->withErrors([
                    'amount_paid' => 'Payment exceeds remaining invoice amount'
                ]);
            }

            $actualPaid = $grossAmount;


            // $actualPaid = $grossAmount - $withheld;
                $receiptPath = null;
            if ($request->hasFile('upload_receipt')) {
                $receiptPath = $request->file('upload_receipt')
                    ->store('payments', 'public');
            }

            Payment::create([
                'invoice_order_id' => $invoice->id,
                'customer_id'      => $request->customer_id,
                'code'             => $request->code,
                'amount_paid'      => $actualPaid, 
                'amount_withheld'  => $withheld,
                'tax_deduction'    => $request->tax_deduction,
                'bank_charges'     => $request->bank_charges ?? 0,
                'payment_date'     => $request->payment_date,
                'payment_received_on' => $request->payment_received_on,
                'payment_method'   => $request->payment_method,
                'reference_number' => $request->reference_number,
                'notes'            => $request->notes,
                'upload_receipt'   => $receiptPath,
                'created_by'       => auth()->id(),
            ]);

            $this->updateInvoiceStatus($invoice);

            DB::commit();
            return back()->with('success', 'Payment added successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'amount_paid' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {

            $payment = Payment::lockForUpdate()->findOrFail($id);
            $invoice = $payment->invoiceOrder;

            $oldGross = $payment->amount_paid + $payment->amount_withheld;

            $totalReceivedExceptThis = $invoice->payments()
                ->where('id', '!=', $payment->id)
                ->selectRaw('SUM(amount_paid + amount_withheld) as total')
                ->value('total') ?? 0;

            $remaining = $invoice->total_amount - $totalReceivedExceptThis;

            $grossAmount = $request->amount_paid;
            $withheld = $request->tax_deduction === 'yes'
                ? ($request->amount_withheld ?? 0)
                : 0;

            $maxAllowed = $remaining + $oldGross;

            if ($grossAmount > $maxAllowed) {
                return back()->withErrors([
                    'amount_paid' => 'Updated amount exceeds remaining invoice amount'
                ]);
            }
            $gross = $grossAmount + $withheld;

            if ($gross > $maxAllowed + 0.01) {
                return back()->withErrors([
                    'amount_paid' => 'Updated amount exceeds remaining invoice amount'
                ]);
            }

            $actualPaid = $grossAmount;
          

            // $actualPaid = $grossAmount - $withheld;
            $receiptPath = $payment->upload_receipt; 
            if ($request->hasFile('upload_receipt')) {

                if ($payment->upload_receipt && Storage::disk('public')->exists($payment->upload_receipt)) {
                    Storage::disk('public')->delete($payment->upload_receipt);
                }

                $receiptPath = $request->file('upload_receipt')
                    ->store('payments', 'public');
            }

            $payment->update([
                'amount_paid'          => $actualPaid,
                'amount_withheld'      => $withheld,
                'tax_deduction'        => $request->tax_deduction,
                'bank_charges'         => $request->bank_charges ?? 0,
                'payment_date'         => $request->payment_date,
                'payment_received_on'  => $request->payment_received_on,
                'payment_method'       => $request->payment_method,
                'reference_number'     => $request->reference_number,
                'notes'                => $request->notes,
                'upload_receipt'       => $receiptPath,
            ]);

            $this->updateInvoiceStatus($invoice);

            DB::commit();
            return back()->with('success', 'Payment updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }



     private function updateInvoiceStatus($invoice)
    {
        $totalReceived = $invoice->payments()
            ->selectRaw('SUM(amount_paid + amount_withheld) as total')
            ->value('total');

        if ($totalReceived >= $invoice->net_amount) {
            $invoice->payment_status = 'Paid';
            $invoice->balance_amount = 0;
        } elseif ($totalReceived > 0) {
            $invoice->payment_status = 'Partial';
            $invoice->balance_amount = $invoice->net_amount - $totalReceived;
        } else {
            $invoice->payment_status = 'Pending';
            $invoice->balance_amount = $invoice->net_amount;
        }

        $invoice->save();
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
