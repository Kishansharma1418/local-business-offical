<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

use App\Models\{PurchaseOrder, Vendor, Broker, Branch, Currency, PaymentTerms, Uom, RawMaterial, PurchaseOrderDetail, GstRate, PurchaseOrderApprovel, RawMaterailBatch};

class PurchaseTestingController extends Controller
{


    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = PurchaseOrder::with('vendor')
                ->whereNotIn('status', ['draft', 'Approved', 'Sent', 'Accepted', 'partialreceived', 'rejected'])
                ->orderBy('id', 'DESC');

            return DataTables::of($query)

                ->addIndexColumn()

                ->filterColumn('user', function ($query, $keyword) {
                    $query->whereHas('vendor', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                            ->orWhere('code', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhere('phone', 'like', "%{$keyword}%");
                    });
                })

               ->editColumn('created_at', fn($row) => formatDate($row->created_at))

                ->addColumn('user', function ($row) {

                    $user  = '<div class="d-flex align-items-center" style="gap:15px;">';

                    $user .= '<div class="flex-grow-1">';
                    $user .= '<p class="mb-1"><strong>Name:</strong> ' . ($row->vendor->name ?? '-') . '</p>';
                    $user .= '<p class="mb-1"><strong>Vendor Code:</strong> ' . ($row->vendor->code ?? '-') . '</p>';
                    $user .= '<p class="mb-1"><strong>Email:</strong> ' . ($row->vendor->email ?? '-') . '</p>';
                    $user .= '<p class="mb-0"><strong>Phone:</strong> ' . ($row->vendor->phone ?? '-') . '</p>';
                    $user .= '</div>';

                    $user .= '</div>';

                    return $user;
                })

                ->editColumn('status', function ($row) {

                    if ($row->status == 'quarantine') {
                        return '<span class="badge bg-dark">QA Approval</span>';
                    }

                    elseif ($row->status == 'in_qa') {
                        return '<span class="badge bg-info">In QA</span>';
                    }
                    elseif ($row->status == 'store_check') {
                        return '<span class="badge bg-success">completed</span>';
                    }

                    else {
                        return '<span class="badge bg-secondary">' . ucfirst($row->status) . '</span>';
                    }
                })

                ->addColumn('action', function ($row) {

                    return '<a href="' . route('purchase-testing.show', $row->id) . '" 
                    title="View">
        <i class="ri-eye-line" style="font-size:18px;"></i>
        </a>';
                })

                ->rawColumns(['user', 'status', 'action'])

                ->make(true);
        }

        return view('admin.purchase-testing.index');
    }

    public function show($id)
    {
        $po = PurchaseOrder::with([
            'vendor',
            'broker',
            'branch',
            'currency',
            'paymentTerm',
            'details.rawMaterial',
            'details.uom'
        ])->findOrFail($id);

        $purchaseorderapprovals = PurchaseOrderApprovel::where('purchase_order_id', $po->id)
            ->orderBy('created_at', 'ASC')
            ->get();

        $uoms = \App\Models\Uom::all();

        return view('admin.purchase-testing.show', compact(
            'po',
            'purchaseorderapprovals',
            'uoms'
        ));
    }
}
