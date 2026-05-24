<?php

namespace App\Http\Controllers;

use App\Models\RawMaterailBatch;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class RawMaterailBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // ✅ Pehle raw_material_id decrypt karo (dono blocks ke liye)
        $rawMaterialId = null;
        if ($request->has('raw_material_id')) {
            $rawMaterialId = decrypt($request->raw_material_id);
        }

        if ($request->ajax()) {

            $batches = RawMaterailBatch::with('rawMaterial')
                ->orderBy('id', 'DESC');

            // ✅ Ajax mein bhi filter
            if ($rawMaterialId) {
                $batches->where('raw_material_id', $rawMaterialId);
            }

            return DataTables::of($batches)
                ->addColumn('raw_material_name', function ($row) {
                    return $row->rawMaterial?->name ?? '';
                })
                ->addColumn('quantity', function ($row) {
                    return $row->quantity . ' ' . $row->uoms?->name ?? '';
                })
                ->addColumn('action', function ($row) {
                    return "<a href='" . route('stock-ledger.index', ['raw_materail_batch_id' => $row->id]) . "' class='' title='View Stock Ledger'><i class='material-symbols-outlined fs-16 fw-normal text-body'>inventory_2</i></a>";
                })
                ->rawColumns(['action', 'raw_material_name'])
                ->make(true);
        }

        // ✅ Non-ajax mein bhi filter apply karo
        $query = RawMaterailBatch::with(['rawMaterial', 'uoms'])
            ->select(
                'id',
                'raw_material_id',
                'batch_no', 
                'quantity',
                'uom_id',
                'created_at',
                'expiry_date',
                'analytic_report_no',
                'grn_no',
                'referance_no',
                'purchase_order_id'
            )
            ->orderBy('created_at', 'ASC');

        if ($rawMaterialId) {
            $query->where('raw_material_id', $rawMaterialId);
        }

        $batches = $query->get()
            ->map(function ($batch) {
                $outward = \App\Models\StockLedger::where('raw_materail_batch_id', $batch->id)
                    ->sum('qty');

                $batch->inward = $batch->quantity;
                $batch->outward = $outward;
                $batch->balance = $batch->quantity - $outward;
                $batch->uom_name = $batch->uoms?->name ?? '';

                return $batch;
            })
            ->groupBy(function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('M Y');
            });

        // Months calculation - filtered data ke basis par
        $firstDateQuery = RawMaterailBatch::query();
        if ($rawMaterialId) {
            $firstDateQuery->where('raw_material_id', $rawMaterialId);
        }
        $firstDate = $firstDateQuery->min('created_at');

        if ($firstDate) {
            $first = \Carbon\Carbon::parse($firstDate);
            $startYear = $first->month >= 4 ? $first->year : $first->year - 1;
        } else {
            $startYear = now()->year;
        }

        $start = \Carbon\Carbon::create($startYear, 4, 1);
        $current = now();
        $endYear = $current->month >= 4 ? $current->year : $current->year - 1;
        $end = \Carbon\Carbon::create($endYear + 1, 3, 1);

        $months = [];
        while ($start <= $end) {
            $months[] = $start->format('M Y');
            $start->addMonth();
        }

        return view('admin.RawmaterialBatch.index', compact('batches', 'months'));
    }
    public function stockLedgerIndex(Request $request)
    {
        if ($request->ajax()) {

            $stockLedger = StockLedger::with([
                'rawMaterial',
                'uom',
                'referance',
                'approvedBy'
            ])
                ->orderBy('id', 'DESC');

            if ($request->filled('raw_materail_batch_id')) {
                $stockLedger->where('raw_materail_batch_id', $request->raw_materail_batch_id);
            }

            return DataTables::of($stockLedger)
                ->addColumn('raw_material', function ($row) {
                    return $row->rawMaterial->name ?? '';
                })
                ->addColumn('uom', function ($row) {
                    return $row->qty . ' ' . ($row->uom->name ?? '');
                })
                ->addColumn('approved_by', function ($row) {
                    return $row->approvedBy->full_name ?? '';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M, Y') : '-';
                })
                ->addColumn('referance', function ($row) {
                    return $row->referance->name ?? '';
                })
                ->rawColumns(['raw_material', 'uom', 'referance', 'approved_by'])
                ->make(true);
        }

        return view('admin.RawmaterialBatch.stock-ledger.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RawMaterailBatch $rawMaterailBatch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RawMaterailBatch $rawMaterailBatch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RawMaterailBatch $rawMaterailBatch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RawMaterailBatch $rawMaterailBatch)
    {
        //
    }
}
