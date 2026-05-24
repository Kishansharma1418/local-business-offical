<?php

namespace App\Http\Controllers;

use App\Models\{GstRate, Warehouse};
use App\Models\BatchManagement,FinishedGoodStockLedger;
use App\Models\FinishedGood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Carbon\Carbon;

class BatchManagementController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $batch = BatchManagement::with('product')->orderBy('id', 'DESC');
            if ($request->product_id) {
                $batch->where('product_id', $request->product_id);
            }
            if ($request->manufacturing_date) {
                $batch->whereDate('manufacturing_date', $request->manufacturing_date);
            }

            if ($request->expiry_date) {
                $batch->whereDate('expiry_date', $request->expiry_date);
            }

            return DataTables::of($batch)
                ->filterColumn('product', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->whereHas('product', function ($productQuery) use ($keyword) {
                            $productQuery->where('name', 'like', "%{$keyword}%");
                        });
                    });
                })
                ->addColumn('product', function ($row) {
                    return $row->product?->name;
                })
                ->addColumn('action', function ($row) {
                    return view('admin.batch-management.action', compact('row'))->render();
                })
                ->rawColumns(['action', 'product'])
                ->make(true);
        }
        $batches = BatchManagement::with('product')
            ->select(
                'id',
                'product_id',
                'batch_number',
                'available_quantity',
                'manufacturing_date',
                'expiry_date',
                'created_at'
            )
            ->orderBy('manufacturing_date', 'ASC')
            ->get()
            ->groupBy(function ($row) {
                return \Carbon\Carbon::parse($row->manufacturing_date)->format('M Y');
            });
        $firstDate = BatchManagement::min('manufacturing_date');

        if ($firstDate) {

            $first = \Carbon\Carbon::parse($firstDate);

            if ($first->month >= 4) {
                $startYear = $first->year;
            } else {
                $startYear = $first->year - 1;
            }
        } else {

            $startYear = now()->year;
        }

        $start = \Carbon\Carbon::create($startYear, 4, 1);

        $current = now();

        if ($current->month >= 4) {
            $endYear = $current->year;
        } else {
            $endYear = $current->year - 1;
        }

        $end = \Carbon\Carbon::create($endYear + 1, 3, 1);

        $months = [];

        while ($start <= $end) {

            $months[] = $start->format('M Y');

            $start->addMonth();
        }

        $products = FinishedGood::select('id', 'name')->get();
        return view('admin.batch-management.index', compact('products', 'batches', 'months'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $products = FinishedGood::select('id', 'name')->get();
        $warehouses = Warehouse::select('id', 'warehouse_name')->get();
        $gstPercents = GstRate::all();
        return view('admin.batch-management.create', compact('products', 'warehouses', 'gstPercents'));
    }

    /**
     * Store a new batch
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'product_id' => 'required|integer',
                'batch_number' => 'required|string|max:100|unique:batch_management,batch_number',
                'manufacturing_date' => 'required|date',
                'expiry_date' => 'required|date|after:manufacturing_date',
                'warehouse_id' => 'required|integer',
                'available_quantity' => 'required|numeric',
                'unit_cost' => 'required|numeric',
                'base_price' => 'required|numeric',
                'gst_percent' => 'required|numeric',
                'mrp' => 'required|numeric',
            ]);

            $batchmanagement = BatchManagement::create([
                'product_id' => $request->product_id,
                'batch_number' => $request->batch_number,
                'manufacturing_date' => $request->manufacturing_date,
                'expiry_date' => $request->expiry_date,
                'warehouse_id' => $request->warehouse_id,
                'available_quantity' => $request->available_quantity,
                'unit_cost' => $request->unit_cost,
                'base_price' => $request->base_price,
                'gst_percent' => $request->gst_percent,
                'mrp' => $request->mrp,
                'created_by' => auth()->id(),
            ]);

            \App\Models\FinishedGoodStockLedger::addEntry([
                'date'             => now()->toDateString(),
                'product_id'       => $batchmanagement->product_id,
                'batch_id'         => $batchmanagement->id,
                'transaction_type' => 'Adjustment',
                'inward_qty'       => $request->available_quantity,
                'outward_qty'      => 0,
                'reference_id'     =>  $batchmanagement->id,
            ]);

            DB::commit();

            return redirect()->route('finished-goods.index')->with('success', 'Batch created successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        try {
            // Decrypt the encrypted ID from the URL
            $id = decrypt($id);

            // Find the batch by ID or fail if not found
            $batch = BatchManagement::findOrFail($id);

            // Load dropdown data for related models
            $products = FinishedGood::select('id', 'name')->get();
            $warehouses = Warehouse::select('id', 'warehouse_name')->get();
            $gstPercents = GstRate::all();

            // Pass batch + lists to view
            return view('admin.batch-management.edit', compact('batch', 'products', 'warehouses', 'gstPercents'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    /**
     * Update batch
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $batch = BatchManagement::findOrFail($id);

            $request->validate([
                'product_id' => 'required|integer',
                'batch_number' => 'required|string|max:100|unique:batch_management,batch_number,' . $batch->id,
                'manufacturing_date' => 'required|date',
                'expiry_date' => 'required|date|after:manufacturing_date',
                'warehouse_id' => 'required|integer',
                'available_quantity' => 'required|numeric',
                'unit_cost' => 'required|numeric',
                'base_price' => 'required|numeric',
                'gst_percent' => 'required|numeric',
                'mrp' => 'required|numeric',
            ]);

            $oldQty = $batch->available_quantity;
            $newQty = $request->available_quantity;
            $batch->update([
                'product_id' => $request->product_id,
                'batch_number' => $request->batch_number,
                'manufacturing_date' => $request->manufacturing_date,
                'expiry_date' => $request->expiry_date,
                'warehouse_id' => $request->warehouse_id,
                'available_quantity' => $request->available_quantity,
                'unit_cost' => $request->unit_cost,
                'base_price' => $request->base_price,
                'gst_percent' => $request->gst_percent,
                'mrp' => $request->mrp,
                'updated_by' => auth()->id(),
            ]);

            $diff = $newQty - $oldQty;
            if ($diff != 0) {
                \App\Models\FinishedGoodStockLedger::addEntry([ 
                    'date'             => now()->toDateString(),
                    'product_id'       => $batch->product_id,
                    'batch_id'         => $batch->id,
                    'transaction_type' => 'Adjustment',
                    'inward_qty'       => $diff > 0 ? $diff : 0,   // qty badhi
                    'outward_qty'      => $diff < 0 ? abs($diff) : 0, // qty ghati
                    'reference_id'     => $batch->id,
                ]);
            }

            DB::commit();   

            return redirect()->route('finished-goods.index')->with('success', 'Batch updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show single batch details
     */
    public function show($id)
    {
        $id = decrypt($id);

        $batch = BatchManagement::with(['product', 'warehouse', 'createdBy', 'updatedBy'])
            ->findOrFail($id);

        return view('admin.batch-management.show', compact('batch'));
    }

    public function destroy($id)
    {
        try {
            $batch = BatchManagement::findOrFail($id);
            $batch->delete();

            return response()->json(['success' => true, 'message' => 'Batch deleted successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
