<?php

namespace App\Http\Controllers;

use App\Models\{BomMaster, FinishedGood, Uom, RawMaterial, GstRate, BomItem, Warehouse, BomType, ProductionProcess, ProductionProcessItem, ProductType, PackgingType, Branch};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use DataTables;
use Exception;

class BomMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = BomMaster::query()->with('finishedGood');

            if ($request->filled('finished_good_id')) {
                $query->where('finished_good_id', $request->finished_good_id);
            }

            $bomMasters = $query->orderBY('id', 'DESC');

            return DataTables::of($bomMasters)
                ->addColumn('bom_number', function ($bomMaster) {
                    return $bomMaster->bom_number;
                })
                ->addColumn('finished_good', function ($bomMaster) {
                    return $bomMaster->finishedGood ? $bomMaster->finishedGood->name : '';
                })
                ->addColumn('bom_date', fn($bomMaster) => formatDate($bomMaster->bom_date))

                ->addColumn('batch_size', function ($bomMaster) {
                    return $bomMaster->batch_size;
                })
                ->addColumn('status', function ($bomMaster) {
                    return '<span class="badge bg-' . ($bomMaster->status == '1' ? 'success' : 'warning') . '">' . ($bomMaster->status == '1' ? 'Approved' : 'Pending') . '</span>';
                })
                ->addColumn('action', function ($bomMaster) {
                    return view('admin.bom-master.action', ['row' => $bomMaster])->render();
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $finished_goods = FinishedGood::select('id', 'name')->get();
        return view('admin.bom-master.index', compact('finished_goods'));
    }

    /**
     * Generate BOM Number
     */
    public function generateBOMNumber()
    {
        $lastOrder = BomMaster::orderBy('id', 'DESC')->first();

        if (!$lastOrder) {
            return 'BM-101';
        }

        $lastNumber = intval(str_replace('BM-', '', $lastOrder->bom_number));

        $nextNumber = $lastNumber + 1;

        return 'BM-' . $nextNumber;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = FinishedGood::where('status', '1')->get();
        $uoms = Uom::where('status', '1')->get();
        $rawMaterials = RawMaterial::where('status', '1')->get();
        $warehouses = Warehouse::where('is_active', '1')->get();
        $productTypes = ProductType::where('status', '1')->get();
        $packConfig = PackgingType::where('status', '1')->get();
        $branches = Branch::where('status', 'Active')->where('branch_type', 'Head Office')->get();

        return view('admin.bom-master.create', compact('products', 'uoms', 'rawMaterials', 'warehouses', 'productTypes', 'packConfig', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $lastBom = BomMaster::where('finished_good_id', $request->finished_good_id)
                ->orderBy('id', 'desc')
                ->first();

            if ($lastBom) {
                $newVersion = str_pad(((int)$lastBom->bom_version + 1), 2, '0', STR_PAD_LEFT);
            } else {
                $newVersion = '01';
            }
            // $exists = BomMaster::where('finished_good_id', $request->finished_good_id)
            //     ->where('product_type', $request->product_type)
            //     ->where('bom_version', $request->bom_version)
            //     ->exists();

            // dd($exists);
            // if ($exists) {
            //     DB::rollBack();

            //     return redirect()
            //         ->back()
            //         ->with('error', 'Same BOM Version already exists for this product.')
            //         ->withInput();
            // }

            $batchSize = (float) $request->batch_size;
            $packSize  = (float) $request->pack_size;
            $boxSize   = (float) $request->box_size;

            $productTypeData = ProductType::where('id', $request->product_type)->first();

            $productTypeName = strtolower($productTypeData->name ?? '');

            if (str_contains($productTypeName, 'syrup') || str_contains($productTypeName, 'vial')) {

                $packSizeInLiter = $packSize / 1000;
                $totalLiterPerBox = $packSizeInLiter * $boxSize;

                $noOfBoxes = ceil($batchSize / $totalLiterPerBox);
            } else {

                $noOfBoxes = ceil($batchSize / ($packSize * $boxSize));
            }
            $request->merge(['no_of_boxes' => $noOfBoxes]);

            $bomMaster = new BomMaster();
            $bomMaster->bom_number = $this->generateBOMNumber();
            $bomMaster->bom_version = $newVersion;
            $bomMaster->batch_size = $request->batch_size;
            $bomMaster->packing_type = $request->packing_type;
            $bomMaster->pack_size = $request->pack_size;
            $bomMaster->box_size = $request->box_size;
            $bomMaster->no_of_boxes = $noOfBoxes;
            $bomMaster->batch_uom = $request->batch_uom;
            $bomMaster->bom_date = $request->bom_date;
            $bomMaster->finished_good_id = $request->finished_good_id;
            $bomMaster->product_type = $request->product_type;
            $bomMaster->pack_config_id = $request->pack_config_id;
            $bomMaster->quantity = $request->quantity;
            $bomMaster->remarks = $request->remarks;
            $bomMaster->branch_id = $request->branch_id;
            $bomMaster->status = '0';
            $bomMaster->created_by = auth()->user()->id;
            $bomMaster->save();

            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    if (empty($item['material_id']) || empty($item['quantity'])) {
                        continue;
                    }

                    $perUnitQty = $item['quantity'] / $bomMaster->batch_size;

                    BomItem::create([
                        'bom_master_id' => $bomMaster->id,
                        'material_id'   => $item['material_id'],
                        // 'item_type'     => $item['item_type'],
                        'uom'           => $item['uom'],
                        'quantity'      => $item['quantity'],
                        'overage'       => $item['overage'] ?? 0,
                        'warehouse_id'  => $item['warehouse_id'] ?? null,
                        'status'        => '1',
                        'per_unit_qty' => $perUnitQty,
                        'created_by' => auth()->user()->id,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('production-process.create', encrypt($bomMaster->id))
                ->with('success', 'BOM Master & BOM Items created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Error while saving BOM: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);

        $bomMaster = BomMaster::with('items.material')->findOrFail($id);
        $uoms = Uom::where('status', '1')->get();
        $branches = Branch::where('status', 'Active')->get();
        // dd($bomMaster->items);

        return view('admin.bom-master.show', compact('bomMaster', 'uoms', 'branches'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);

        $bomMaster = BomMaster::with('items.material')->findOrFail($id);
        // dd($bomMaster->items);

        $products = FinishedGood::where('status', '1')->get();
        $rawMaterials = RawMaterial::where('status', '1')->get();
        $warehouses = Warehouse::where('is_active', '1')->get();
        $productTypes = ProductType::where('status', '1')->get();
        $packConfig = PackgingType::where('status', '1')->get();
        $uoms = Uom::where('status', '1')->get();
        $branches = Branch::where('status', 'Active')->where('branch_type', 'Head Office')->get();
        return view('admin.bom-master.edit', compact('bomMaster', 'products', 'rawMaterials', 'warehouses', 'productTypes', 'packConfig', 'uoms', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $lastBom = BomMaster::where('finished_good_id', $request->finished_good_id)
                ->where('id', '!=', $id) // current ko exclude karo
                ->orderBy('id', 'desc')
                ->first();

            if ($lastBom) {
                $newVersion = str_pad(((int)$lastBom->bom_version + 1), 2, '0', STR_PAD_LEFT);
            } else {
                $newVersion = '01';
            }
            $bomMaster = BomMaster::findOrFail($id);


            $batchSize = (float) $request->batch_size;
            $packSize  = (float) $request->pack_size;
            $boxSize   = (float) $request->box_size;

            $productTypeData = ProductType::where('id', $request->product_type)->first();

            $productTypeName = strtolower($productTypeData->name ?? '');

            if (str_contains($productTypeName, 'syrup') || str_contains($productTypeName, 'vial')) {

                $packSizeInLiter = $packSize / 1000;
                $totalLiterPerBox = $packSizeInLiter * $boxSize;

                $noOfBoxes = ceil($batchSize / $totalLiterPerBox);
            } else {

                $noOfBoxes = ceil($batchSize / ($packSize * $boxSize));
            }

            // $batchSize = (int) $request->batch_size;
            // $packSize  = (int) $request->pack_size;
            // $boxSize   = (int) $request->box_size;

            // if ($packSize <= 0 || $boxSize <= 0) {
            //     return back()->withErrors(['Invalid Pack Size or Box Size']);
            // }

            // $noOfBoxes = (int) ceil($batchSize / ($packSize * $boxSize));

            $request->merge(['no_of_boxes' => $noOfBoxes]);

            $bomMaster->update([
                // 'bom_number'       => $this->generateBOMNumber(),
                'bom_version' => $newVersion,
                'batch_size' => $request->batch_size,
                'batch_uom' => $request->batch_uom,
                'bom_date' => $request->bom_date,
                'finished_good_id' => $request->finished_good_id,
                'quantity'         => $request->quantity,
                'remarks'          => $request->remarks,
                'packing_type'     => $request->packing_type,
                'pack_size'        => $request->pack_size,
                'box_size'         => $request->box_size,
                'no_of_boxes'      => $noOfBoxes,
                'product_type'     => $request->product_type,
                'pack_config_id' => $request->pack_config_id,
                'branch_id'  => $request->branch_id,
                'updated_by'       => auth()->id(),
            ]);

            BomItem::where('bom_master_id', $bomMaster->id)->delete();

            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    if (empty($item['material_id']) || empty($item['quantity'])) {
                        continue;
                    }

                    $perUnitQty = $item['quantity'] / $bomMaster->batch_size;

                    BomItem::create([
                        'bom_master_id' => $bomMaster->id,
                        'material_id' => $item['material_id'],
                        // 'item_type'     => $item['item_type'],
                        'uom' => $item['uom'],
                        'quantity' => $item['quantity'],
                        'per_unit_qty' => $perUnitQty,
                        'status' => '1',
                        'overage' => $item['overage'] ?? 0,
                        'warehouse_id' => $item['warehouse_id'] ?? null,
                        'updated_by' => auth()->user()->id,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('bom-master.index')
                ->with('success', 'BOM updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BomMaster $bomMaster)
    {
        //
    }

    public function createFormProductionProcess($id)
    {
        $id = decrypt($id);
        $bomMaster = BomMaster::with('items.material')
            ->findOrFail($id);

        $bomTypes = BomType::where('status', 1)
            ->orderBy('order_no')
            ->get();

        $roles = Role::whereNotIn('name', ['Admin'])->get();

        $processes = ProductionProcess::with('items')
            ->where('bom_master_id', $id)
            ->orderBy('sequence')
            ->get();

        return view('admin.bom-master.production-process.create', compact('bomMaster', 'bomTypes', 'processes', 'roles'));
    }

    public function storeProductionProcess(Request $request)
    {
        $request->validate([
            'bom_master_id' => 'required|integer',
            'steps' => 'required|array|min:1',
            'steps.*.bom_type_id' => 'required|integer',
            'steps.*.bom_item_id' => 'nullable|array',
            'steps.*.roles' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            $oldProcesses = ProductionProcess::where('bom_master_id', $request->bom_master_id)->get();
            foreach ($oldProcesses as $old) {
                $old->items()->delete();
                $old->delete();
            }

            foreach ($request->steps as $index => $step) {
                $process = ProductionProcess::create([
                    'bom_master_id' => $request->bom_master_id,
                    'bom_type_id' => $step['bom_type_id'],
                    'sequence' => $index + 1,
                    'status' => 'ACTIVE',
                    'created_by' => auth()->id()
                ]);

                $bomItems = $step['bom_item_id'] ?? [];

                if (!empty($bomItems)) {
                    foreach ($bomItems as $bomItemId) {
                        foreach ($step['roles'] as $roleId) {
                            ProductionProcessItem::create([
                                'production_process_id' => $process->id,
                                'bom_item_id' => $bomItemId,
                                'roles' => $roleId,
                                'created_by' => auth()->id()
                            ]);
                        }
                    }
                } else {
                    foreach ($step['roles'] as $roleId) {
                        ProductionProcessItem::create([
                            'production_process_id' => $process->id,
                            'bom_item_id' => null,
                            'roles' => $roleId,
                            'created_by' => auth()->id()
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('bom-master.index')->with('success', 'Production Process Saved Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function changeStatus(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $po = BomMaster::findOrFail($id);
            $po->status = $request->status;
            $po->updated_by = auth()->id();
            $po->save();

            DB::commit();

            return redirect()
                ->route('bom-master.index')
                ->with('success', 'BOM status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
