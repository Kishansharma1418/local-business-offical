<?php

namespace App\Http\Controllers;

use App\Models\{FinishedGood, Uom, Category, ProductDetail, BatchManagement, Warehouse, Branch, GstRate, FinishedGoodStockLedger};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FinishedGoodExport;
use App\Imports\FinishedGoodImport;

class FinishedGoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $finishgood = FinishedGood::with('category', 'subCategory', 'branches');

            if ($request->name) {
                $finishgood->where('name', 'LIKE', '%' . $request->name . '%');
            }
            if ($request->category_id) {
                $finishgood->where('category_id', $request->category_id);
            }
            if ($request->sub_category_id) {
                $finishgood->where('sub_category_id', $request->sub_category_id);
            }
            return Datatables::of($finishgood)
                ->filterColumn('product_detail', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        // 🔹 Product table columns
                        $q
                            ->where('name', 'LIKE', "%{$keyword}%")
                            ->orWhere('code', 'LIKE', "%{$keyword}%")
                            ->orWhere('hsn_code', 'LIKE', "%{$keyword}%")
                            // 🔹 Category
                            ->orWhereHas('category', function ($c) use ($keyword) {
                                $c->where('category_name', 'LIKE', "%{$keyword}%");
                            })
                            // 🔹 Sub Category
                            ->orWhereHas('subCategory', function ($sc) use ($keyword) {
                                $sc->where('category_name', 'LIKE', "%{$keyword}%");
                            })
                            // 🔹 Status (1 / 0 OR text)
                            ->orWhere(function ($s) use ($keyword) {
                                if (strtolower($keyword) === 'active') {
                                    $s->where('status', 1);
                                } elseif (strtolower($keyword) === 'inactive') {
                                    $s->where('status', 0);
                                }
                            })
                            // 🔹 Created Date (formatted search)
                            ->orWhereRaw(
                                "DATE_FORMAT(created_at, '%d %b, %Y') LIKE ?",
                                ["%{$keyword}%"]
                            );
                    });
                })
                ->addColumn('action', function ($row) {
                    return view('admin.finished-goods.action', compact('row'))->render();
                })
                ->addColumn('category', function ($row) {
                    return $row->category?->category_name;
                })
                ->addColumn('subcategory', function ($row) {
                    return $row->subCategory?->category_name;
                })
                ->addColumn('total_batch_quantity', function ($row) {
                    return number_format($row->batchManagements()->sum('available_quantity'), 0);
                })
                ->addColumn('stock_status_order', function ($row) {
                    $total = $row->batchManagements()->sum('available_quantity');
                    $minLevel = $row->record_level ?? 0;

                    if ($total == 0) {
                        return 3; // Out of Stock
                    } elseif ($total < $minLevel) {
                        return 2; // Low Quantity
                    } else {
                        return 1; // Sufficient Stock — sabse upar
                    }
                })
                ->addColumn('product_detail', function ($row) {
                    $user = '<div class="d-flex align-items-center " style="gap:15px;">';
                    $user .= '   <div class="flex-grow-1">';
                    $user .= '       <p class="mb-0" style="font-size:14px;"><strong>' . ($row->name ?? '-') . '</strong></p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Product Code:</strong> ' . ($row->code ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>HSN Code:</strong> ' . ($row->hsn_code ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Branch:</strong> ' . ($row->branches?->branch_name ?? '-') . '</p>';

                    $user .= '   </div>';
                    $user .= '</div>';

                    return $user;
                })
                ->addColumn('batch_count', function ($row) {
                    return $row->batchManagements()->count();
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })

                ->addColumn('product_detail', function ($row) {
                    $batches = $row->batchManagements()
                        ->select('id', 'batch_number', 'manufacturing_date', 'expiry_date', 'mrp', 'available_quantity')
                        ->orderBy('manufacturing_date', 'DESC')
                        ->get();

                    $batchData = [];
                    foreach ($batches as $batch) {
                        $inward  = \App\Models\FinishedGoodStockLedger::where('product_id', $row->id)->where('batch_id', $batch->id)->sum('inward_qty');
                        $outward = \App\Models\FinishedGoodStockLedger::where('product_id', $row->id)->where('batch_id', $batch->id)->sum('outward_qty');
                        $batchData[] = [
                            'batch'   => $batch->batch_number,
                            'mfg'     => formatDate($batch->manufacturing_date),
                            'exp'     => formatDate($batch->expiry_date),
                            'mrp'     => $batch->mrp ?? '-',
                            'inward'  => $inward,
                            'outward' => $outward,
                            'balance' => $inward - $outward,
                             'total_cost' => ($inward - $outward) * ($batch->mrp ?? 0), 
                        ];
                    }

                    $jsonData = htmlspecialchars(json_encode($batchData), ENT_QUOTES, 'UTF-8');

                    $user  = '<div class="d-flex align-items-center" style="gap:15px;">';
                    $user .= '  <div class="flex-grow-1" data-batches="' . $jsonData . '">';
                    $user .= '      <p class="mb-0" style="font-size:14px;"><strong>' . ($row->name ?? '-') . '</strong></p>';
                    $user .= '      <p class="mb-1" style="font-size:13px;color:#666;"><strong>Product Code:</strong> ' . ($row->code ?? '-') . '</p>';
                    $user .= '      <p class="mb-1" style="font-size:13px;color:#666;"><strong>HSN Code:</strong> ' . ($row->hsn_code ?? '-') . '</p>';
                    $user .= '      <p class="mb-1" style="font-size:13px;color:#666;"><strong>Branch:</strong> ' . ($row->branches?->branch_name ?? '-') . '</p>';
                    $user .= '  </div>';
                    $user .= '</div>';

                    return $user;
                })
                ->editColumn('created_at', fn($row) => formatDate($row->created_at))

                ->rawColumns(['action', 'status', 'category', 'subcategory', 'created_at', 'product_detail', 'total_batch_quantity', 'stock_status_order', 'batch_count'])
                ->make(true);
        }
        $departments = FinishedGood::select('name')->groupBy('name')->get();
        $categoriesCat = Category::with('subcategories')->whereNull('parent_category_id')->where('status', '1')->get();

        return view('admin.finished-goods.index', compact('departments', 'categoriesCat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $uoms = Uom::where('status', '1')->get();
        $categories = Category::with('subcategories')->whereNull('parent_category_id')->where('status', '1')->get();
        $branches = Branch::where('branch_type', 'Head Office')->get();
        $gstrates = GstRate::all();
        return view('admin.finished-goods.create', compact('uoms', 'categories', 'branches', 'gstrates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:finished_goods,code',
            'name' => 'required|string|max:255',
            'hsn_code' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
            'uom_id' => 'required|exists:uoms,id',
            'description' => 'nullable|string',
            'record_level' => 'nullable|numeric',
            'lead_time_days' => 'nullable|numeric',
            'total_qty' => 'nullable|numeric',
            'status' => 'required|in:0,1',
        ]);

        DB::beginTransaction();

        try {
            $product = FinishedGood::create([
                'code' => $request->code,
                'name' => $request->name,
                'hsn_code' => $request->hsn_code,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'uom_id' => $request->uom_id,
                'description' => $request->description,
                'record_level' => $request->record_level,
                'total_qty' => $request->total_qty,
                'lead_time_days' => $request->lead_time_days,
                'status' => $request->status,
                'unit_cost' => $request->unit_cost,
                'base_price' => $request->base_price,
                'gst_percent' => $request->gst_percent,
                'mrp' => $request->mrp,
                'branch_id' => $request->branch_id,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()
                ->route('finished-good.index')
                ->with('success', 'Product added successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show($id)
    // {
    //     $id = decrypt($id);

    //     $finishedGood = FinishedGood::with([
    //         'category',
    //         'subCategory',
    //         'productDetail',
    //         'uoms',
    //         'branches',
            
    //     ])->findOrFail($id);

    //     return view('admin.finished-goods.show', compact('finishedGood'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);

        $product = FinishedGood::findOrFail($id);
        $branches = Branch::where('branch_type', 'Head Office')->get();
        $categories = Category::with('subcategories')
            ->whereNull('parent_category_id')
            ->where('status', '1')
            ->get();

        $uoms = Uom::where('status', '1')->get();
        $gstrates = GstRate::all();

        return view(
            'admin.finished-goods.edit',
            compact('product', 'categories', 'uoms', 'branches', 'gstrates')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $product = FinishedGood::findOrFail($id);
        $request->validate([
            // 'code' => 'required|string|max:255',
            'code' => 'required|min:3|unique:finished_goods,code,' . $product->id,
            'name' => 'required|string|max:255',
            'hsn_code' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:categories,id',
            'uom_id' => 'required|exists:uoms,id',
            'record_level' => 'nullable|numeric',
            'lead_time_days' => 'nullable|numeric',
            'status' => 'required|in:0,1',
        ]);

        DB::beginTransaction();

        try {
            $product->update([
                'code' => $request->code,
                'name' => $request->name,
                'hsn_code' => $request->hsn_code,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'uom_id' => $request->uom_id,
                'description' => $request->description,
                'record_level' => $request->record_level,
                'total_qty' => $request->total_qty,
                'lead_time_days' => $request->lead_time_days,
                'status' => $request->status,
                'unit_cost' => $request->unit_cost,
                'base_price' => $request->base_price,
                'gst_percent' => $request->gst_percent,
                'mrp' => $request->mrp,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('finished-good.index')->with('success', 'Product updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $finishedGood = FinishedGood::findOrFail($id);
            $finishedGood->delete();

            return response()->json(['success' => true, 'message' => 'Product deleted successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getSubcategories($categoryId)
    {
        $subcategories = Category::where('parent_category_id', $categoryId)
            ->where('status', '1')
            ->get(['id', 'category_name']);

        return response()->json($subcategories);
    }

    public function showFinishedGoodForm($product_id)
    {
        $product_id = decrypt($product_id);

        $finishGoods = FinishedGood::find($product_id);
        $warehouses = Warehouse::select('id', 'warehouse_name')->get();
        $productDetails = ProductDetail::where('finished_goods_id', $product_id)->first();

        return view('admin.finished-goods.product-details.create', compact('product_id', 'finishGoods', 'productDetails', 'warehouses'));
    }

    public function storeProductDetails(Request $request)
    {
        $request->validate([
            'finished_goods_id' => 'required|exists:finished_goods,id',
            'composition' => 'nullable|string|max:255',
            'strength_specification' => 'nullable|string|max:255',
            'packing_type' => 'nullable|string|max:255',
            'pack_size' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'country_origin' => 'nullable|string|max:255',
            'storage_condation' => 'nullable|string|max:255',
            'shelf_life_months' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {
            ProductDetail::updateOrCreate(
                ['finished_goods_id' => $request->finished_goods_id],
                $request->only([
                    'composition',
                    'strength_specification',
                    'packing_type',
                    'pack_size',
                    'brand',
                    'country_origin',
                    'storage_condation',
                    'shelf_life_months'
                ])
            );

            DB::commit();

            return redirect()->route('finished-good.index')->with('success', 'Product details saved successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])->withInput();
        }
    }

    public function showBatchForm($product_id)
    {
        $product_id = decrypt($product_id);

        // Product find karo
        $finishGoods = FinishedGood::findOrFail($product_id);
        $warehouses = Warehouse::select('id', 'warehouse_name')->get();

        $batchmanagement = BatchManagement::where('product_id', $product_id)->first();

        return view('admin.finished-goods.batch-detail.create', compact('finishGoods', 'batchmanagement', 'warehouses'));
    }

    public function storeBatchDetails(Request $request)
    {
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

        DB::beginTransaction();

        try {
            BatchManagement::updateOrCreate(
                ['product_id' => $request->product_id],
                $request->only([
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
                ])
            );

            DB::commit();

            return redirect()
                ->route('finished-good.index')
                ->with('success', 'Batch details saved successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])->withInput();
        }
    }

    public function checkUnique($model, $column, $value, $id = null)
    {
        $modelClass = 'App\\Models\\' . Str::studly($model);

        if (!class_exists($modelClass)) {
            return response()->json(['error' => 'Invalid model'], 400);
        }

        $query = $modelClass::where($column, $value);

        if ($id) {
            $query->where('id', '!=', decrypt($id));
        }

        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }


    public function show($id)
    {
        $productId = decrypt($id);

        $product = FinishedGood::findOrFail($productId);

        $batches = BatchManagement::with('product')
            ->where('product_id', $productId)
            ->select(
                'id',
                'product_id',
                'batch_number',
                'available_quantity',
                'manufacturing_date',
                'expiry_date',
                'created_at',
                'mrp'
            )
            ->orderBy('manufacturing_date', 'DESC')
            ->get()
            ->map(function ($batch) {

                // Us batch ka total inward (purchase/opening)
                $batch->total_inward = FinishedGoodStockLedger::where('product_id', $batch->product_id)
                    ->where('batch_id', $batch->id)
                    ->sum('inward_qty');

                // Us batch ka total outward (sales)
                $batch->total_outward = FinishedGoodStockLedger::where('product_id', $batch->product_id)
                    ->where('batch_id', $batch->id)
                    ->sum('outward_qty');

                // Balance
                $batch->ledger_balance = $batch->total_inward - $batch->total_outward;

                return $batch;
            })
            ->groupBy(function ($row) {
                return \Carbon\Carbon::parse($row->manufacturing_date)->format('M Y');
            });

        $firstDate = BatchManagement::where('product_id', $productId)->min('manufacturing_date');

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
        $months = array_reverse($months);

        return view('admin.finished-goods.view-batch', compact('product', 'batches', 'months'));
    }

    public function downloadSample()
    {
        return Excel::download(new FinishedGoodExport, 'finished_goods_sample.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new FinishedGoodImport, $request->file('file'));

            return back()->with('success', 'Finished Goods Imported Successfully ✅');
        } catch (\Exception $e) {
            return back()->with('error', 'Import Failed ❌ ' . $e->getMessage());
        }
    }

    public function stockLedger(Request $request)
    {
        if ($request->ajax()) {
            $query = FinishedGoodStockLedger::with('product', 'batch')
                ->orderBy('date', 'DESC')
                ->orderBy('id', 'DESC');

            // Product filter
            if ($request->product_id) {
                try {
                    $productId = decrypt($request->product_id);
                } catch (\Exception $e) {
                    $productId = $request->product_id;
                }
                $query->where('product_id', $productId);
            }

            // Batch filter
            if ($request->batch_id) {
                try {
                    $batchId = decrypt($request->batch_id);
                } catch (\Exception $e) {
                    $batchId = $request->batch_id;
                }
                $query->where('batch_id', $batchId);
            }

            // Transaction type filter
            if ($request->transaction_type) {
                $query->where('transaction_type', $request->transaction_type);
            }

            // Date range filter
            if ($request->from_date && $request->to_date) {
                $query->whereBetween('date', [
                    $request->from_date,
                    $request->to_date,
                ]);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('product_name', fn($r) => $r->product?->name ?? '-')
                ->addColumn('product_code', fn($r) => $r->product?->code ?? '-')
                ->addColumn('batch_number', fn($r) => $r->batch?->batch_number ?? '-')
                ->editColumn('inward_qty', function ($r) {

                    if ($r->outward_qty > 0) {
                        $balanceBefore = FinishedGoodStockLedger::where('product_id', $r->product_id)
                            ->where('batch_id', $r->batch_id)
                            ->where('id', '<', $r->id)
                            ->selectRaw('SUM(inward_qty) - SUM(outward_qty) as balance')
                            ->value('balance') ?? 0;

                        return $balanceBefore;
                    }

                    return $r->inward_qty;
                })

                ->addColumn('balance_qty', function ($r) {

                    $balance = FinishedGoodStockLedger::where('product_id', $r->product_id)
                        ->where('batch_id', $r->batch_id)
                        ->where('id', '<=', $r->id)
                        ->selectRaw('SUM(inward_qty) - SUM(outward_qty) as balance')
                        ->value('balance') ?? 0;

                    return $balance;
                })

                ->editColumn('transaction_type', function ($r) {
                    return match ($r->transaction_type) {
                        'sale_out'    => '<span class="badge bg-danger">Sale Out</span>',
                        'purchase_in' => '<span class="badge bg-success">Purchase In</span>',
                        'return_in'   => '<span class="badge bg-info">Return In</span>',
                        'adjustment'  => '<span class="badge bg-warning">Adjustment</span>',
                        'opening'     => '<span class="badge bg-secondary">Opening</span>',
                        default       => '<span class="badge bg-secondary">' . $r->transaction_type . '</span>',
                    };
                })
                ->editColumn('date', fn($r) => formatDate($r->date))
                ->rawColumns(['transaction_type'])
                ->make(true);
        }

        // URL se pre-select karo (view-batch page se aata hai)
        $selectedProductId = null;
        $selectedBatchId   = null;

        if (request('product_id')) {
            try {
                $selectedProductId = decrypt(request('product_id'));
            } catch (\Exception $e) {
            }
        }
        if (request('batch_id')) {
            try {
                $selectedBatchId = decrypt(request('batch_id'));
            } catch (\Exception $e) {
            }
        }

        $products = FinishedGood::where('status', '1')->get();
        $batches  = BatchManagement::select('id', 'batch_number')->get();

        return view('admin.finished-goods.stock-ledger', compact(
            'products',
            'batches',
            'selectedProductId',
            'selectedBatchId'
        ));
    }
}
