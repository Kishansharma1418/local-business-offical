<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{RawMaterial, Uom, RawCategory, RawMaterailBatch, Branch};
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;


class RawMaterialController extends Controller
{
    /* =========================
        INDEX
    ==========================*/
    public function index(Request $request)
    {
        if ($request->ajax()) {


            $rawMaterials = RawMaterial::with(['category', 'subCategory', 'uom'])
                ->orderBy('id', 'DESC');

            if ($request->name) {
                $rawMaterials->where('name', 'LIKE', '%' . $request->name . '%');
            }

            if ($request->raw_category_id) {
                $rawMaterials->where('raw_category_id', $request->raw_category_id);
            }

            if ($request->sub_rawcategory_id) {
                $rawMaterials->where('sub_rawcategory_id', $request->sub_rawcategory_id);
            }

            return DataTables::of($rawMaterials)
                ->filterColumn('category', function ($query, $keyword) {

                    $query->where(function ($q) use ($keyword) {

                        // 🔹 Category
                        $q->whereHas('category', function ($cat) use ($keyword) {
                            $cat->where('name', 'LIKE', "%{$keyword}%");
                        })

                            // 🔹 Sub Category
                            ->orWhereHas('subCategory', function ($sub) use ($keyword) {
                                $sub->where('name', 'LIKE', "%{$keyword}%");
                            });
                    });
                })

                ->addColumn('created_at', fn($row) => formatDate($row->created_at))
                ->addColumn('category', fn($row) => $row->category?->name ?? '-')
                ->addColumn('subcategory', fn($row) => $row->subCategory?->name ?? '-')
                ->editColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    return view('admin.rawmaterial.action', compact('row'))->render();
                })
                ->rawColumns(['status', 'action', 'created_at'])
                ->make(true);
        }

        $categories = RawCategory::whereNull('parent_category_id')
            ->where('status', 1)
            ->get();

        return view('admin.rawmaterial.index', compact('categories'));
    }

    /* =========================
        CREATE
    ==========================*/
    public function create()
    {
        $uom = Uom::where('status', '1')->get();

        $categories = RawCategory::with('subcategories')
            ->whereNull('parent_category_id')
            ->where('status', 1)
            ->get();
        $branches = Branch::where('status', 'Active')
           ->where('branch_type', 'Head Office')->get(); 
        return view('admin.rawmaterial.create', compact('uom', 'categories','branches'));
    }

    /* =========================
        STORE
    ==========================*/
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:rawmaterial,code',
            'name' => 'required|string|max:255',
            'hsn_code' => 'required|string|max:255',
            'raw_category_id' => 'required|exists:rawcategory,id',
            'sub_rawcategory_id' => 'nullable|exists:rawcategory,id',
            'lead_time_days' => 'nullable|numeric',
            'status' => 'required|in:1,0',
        ]);

        DB::beginTransaction();

        try {
            RawMaterial::create([
                'code' => $request->code,
                'name' => $request->name,
                'branch_id' => $request->branch_id,
                'hsn_code' => $request->hsn_code,
                'raw_category_id' => $request->raw_category_id,
                'sub_rawcategory_id' => $request->sub_rawcategory_id,
                'uom_id' => $request->uom_id,
                'description' => $request->description,
                'specification' => $request->specification,
                'lead_time_days' => $request->lead_time_days,
                'status' => $request->status,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()
                ->route('rawmaterial.index')
                ->with('success', 'Raw Material added successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }

    /* =========================
        EDIT
    ==========================*/
    public function edit($id)
    {
        $id = decrypt($id);

        $rawMaterial = RawMaterial::findOrFail($id);

        $categories = RawCategory::whereNull('parent_category_id')
            ->where('status', 1)
            ->get();

        $subCategories = RawCategory::where('parent_category_id', $rawMaterial->raw_category_id)
            ->where('status', 1)
            ->get();

        $uoms = Uom::where('status', '1')->get();
  $branches = Branch::where('status', 'Active')
           ->where('branch_type', 'Head Office')->get(); 
        return view('admin.rawmaterial.edit', compact(
            'rawMaterial',
            'categories',
            'subCategories',
            'uoms',
            'branches'
        ));
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $rawMaterial = RawMaterial::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:rawmaterial,code,' . $rawMaterial->id,
            'name' => 'required',
            'hsn_code' => 'required',
            'raw_category_id' => 'required',
            'status' => 'required',
        ]);

        $rawMaterial->update([
            'code' => $request->code,
            'name' => $request->name,
            'hsn_code' => $request->hsn_code,
            'raw_category_id' => $request->raw_category_id,
            'sub_rawcategory_id' => $request->sub_rawcategory_id,
            'uom_id' => $request->uom_id,
            'description' => $request->description,
            'specification' => $request->specification,
            'lead_time_days' => $request->lead_time_days,
            'status' => $request->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()
            ->route('rawmaterial.index')
            ->with('success', 'Raw Material updated successfully');
    }

    /* =========================
        DELETE
    ==========================*/
    public function destroy($id)
    {
        RawMaterial::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Raw Material deleted successfully'
        ]);
    }

    //     public function show($id)
    // {
    //     $id = decrypt($id);

    //     $rawMaterial = RawMaterial::with([
    //         'category',
    //         'subCategory',
    //         'uom'
    //     ])->findOrFail($id);

    //     return view('admin.rawmaterial.show', compact('rawMaterial'));
    // }
    public function getSubcategories($categoryId)
    {
        $subcategories = RawCategory::where('parent_category_id', $categoryId)
            ->where('status', 1)
            ->get(['id', 'name']);

        return response()->json($subcategories);
    }
    public function show($id)
    {

        $id = decrypt($id);

        $rawMaterial = RawMaterial::findOrFail($id);

        $batches = RawMaterailBatch::with(['rawMaterial', 'uoms'])
            ->where('raw_material_id', $id)
            ->select(
                'id',
                'raw_material_id',
                'batch_no',
                'quantity',
                'created_at'
            )
            ->orderBy('created_at', 'ASC')
            ->get()
            ->map(function ($batch) {

                $outward = \App\Models\StockLedger::where('raw_materail_batch_id', $batch->id)
                    ->sum('qty');

                $batch->inward = $batch->quantity;
                $batch->outward = $outward;
                $batch->balance = $batch->quantity - $outward;

                return $batch;
            })
            ->groupBy(function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('M Y');
            });

        return view(
            'admin.rawmaterial.view-batch',
            compact('rawMaterial', 'batches')
        );
    }
}
