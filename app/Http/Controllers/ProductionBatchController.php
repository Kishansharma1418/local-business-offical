<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ProductionBatch, BomMaster, FinishedGood, Warehouse, ProductionApproval, StoreIssurance, StoreIssuranceItem, ProductionProcessItem, AssignTeam, ProductionProcess, ProductionBatchTeam, RawMaterailBatch, Uom, PackgingType};
use App\Models\ProductionBatchItem;
use Illuminate\Support\Facades\DB;
use DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class ProductionBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = ProductionBatch::with('bomMaster.finishedGood')
                ->orderBy('id', 'desc');

            return DataTables::of($data)
                ->filterColumn('finished_good', function ($query, $keyword) {
                    $query->whereHas('bomMaster', function ($q) use ($keyword) {

                        $q->where('bom_number', 'like', "%{$keyword}%")
                            ->orWhere('bom_version', 'like', "%{$keyword}%")

                            ->orWhereHas('finishedGood', function ($fg) use ($keyword) {
                                $fg->where('name', 'like', "%{$keyword}%");
                            });
                    });
                })
                ->addIndexColumn()

                ->editColumn('mfg_date', function ($row) {
                    return date('d-m-Y', strtotime($row->mfg_date));
                })

                ->editColumn('expiry_date', function ($row) {
                    return date('d-m-Y', strtotime($row->expiry_date));
                })

                ->addColumn('finished_good', function ($row) {
                    return $row->bomMaster->bom_number
                        . ' - ' . $row->bomMaster->bom_version
                        . ' - ' . ($row->bomMaster->finishedGood->name ?? '');
                })

                ->addColumn('status', function ($row) {
                    $statusLabels = [
                        'PENDING_CHEMIST'    => '<span class="badge bg-warning">Pending Chemist</span>',
                        // 'PENDING_STORE'      => '<span class="badge bg-secondary">Pending Store</span>',
                        'PENDING_HEAD_PRODUCTION' => '<span class="badge bg-info">Pending Head Production</span>',
                        'PENDING_QA'         => '<span class="badge bg-primary">Pending QA</span>',
                        'APPROVED'           => '<span class="badge bg-success">Approved</span>',
                        'REJECTED'           => '<span class="badge bg-danger">Rejected</span>',
                        'DRAFT'           => '<span class="badge bg-secondary">DRAFT</span>',
                    ];

                    return $statusLabels[$row->status]
                        ?? '<span class="badge bg-dark">NO FOUND</span>';
                })

                ->addColumn('action', function ($row) {
                    return view('admin.production-batch.action', compact('row'))->render();
                })

                ->rawColumns(['status', 'action', 'finished_good'])
                ->make(true);
        }




        return view('admin.production-batch.index');
    }


    /**
     * Show the form for creating a new resource.J
     */
    public function create()
    {
        $bomMasters = BomMaster::where('status', '1')->get();
        $finishedGoods = FinishedGood::where('status', '1')->get();
        $warehouses = Warehouse::where('is_active', '1')->get();
        $packConfig = PackgingType::where('status', '1')->get();

        // dd($packConfig);

        return view('admin.production-batch.create', compact('bomMasters', 'finishedGoods', 'warehouses', 'packConfig'));
    }


    /**
     * Get BOM Items
     */
    public function getBomItems($bomId)
    {
        $bom = BomMaster::with('items.material')->findOrFail($bomId);

        return response()->json([
            'batch_size' => $bom->batch_size,
            'product_type' => $bom->productType?->name ?? null,
            'pack_size' => $bom->pack_size,
            'box_size' => $bom->box_size,
            'packing_type' => $bom->packing_type,
            'no_of_boxes' => $bom->no_of_boxes,
            'items' => $bom->items->map(function ($item) {
                return [
                    'material_id' => $item->material_id,
                    'material_name' => $item->material?->name,
                    'quantity' => $item->quantity,
                    'uom' => $item->uoms?->name ?? $item->uom,
                    'overage_percent' => $item->overage ?? 0,
                ];
            })
        ]);
    }

    public function getProcessRoles($id)
    {
        $production = ProductionBatch::with('bomMaster')->findOrFail($id);

        $roles = ProductionProcessItem::whereHas('process', function ($q) use ($production) {
            $q->where('bom_master_id', $production->bom_master_id);
        })

            ->with('role:id,name')
            ->get()
            ->pluck('role')
            ->unique('id')
            ->values();

        return response()->json($roles);
    }

    public function getRoleUsers($roleId)
    {
        $role = Role::findOrFail($roleId);

        $users = $role->users()->select('id', 'full_name')->where('id', '!=', 1)->get();

        return response()->json($users);
    }


    public function generateProductionBatchCode()
    {
        $lastOrder = ProductionBatch::orderBy('id', 'DESC')->first();

        if (!$lastOrder) {
            return 'PB-501';
        }

        $lastNumber = intval(str_replace('PB-', '', $lastOrder->batch_number));

        $nextNumber = $lastNumber + 1;

        return 'PB-' . $nextNumber;
    }

    public function generateMaterialRequisitionCode()
    {
        $lastOrder = ProductionBatch::orderBy('id', 'DESC')->first();

        if (!$lastOrder) {
            return 'MR-501';
        }

        $lastNumber = intval(str_replace('MR-', '', $lastOrder->material_requisition_no));

        $nextNumber = $lastNumber + 1;

        return 'MR-' . $nextNumber;
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'bom_master_id' => 'required|exists:bom_masters,id',
            'quantity'      => 'required|numeric|min:1',
            'items'         => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {

            $bom = BomMaster::with('items')->findOrFail($request->bom_master_id);

            $hasStockIssue = false;

            foreach ($request->items as $row) {

                $bomItem = $bom->items
                    ->where('material_id', $row['material_id'])
                    ->first();

                if (!$bomItem) {
                    throw new \Exception('Invalid BOM Item detected');
                }

                $baseQty = $bomItem->quantity / $bom->batch_size * $request->batch_size_qty;
                $overagePercent = $bomItem->overage ?? 0;
                $finalQty = $baseQty + ($baseQty * $overagePercent / 100);

                $available = RawMaterailBatch::where('raw_material_id', $row['material_id'])
                    ->where('quantity', '>', 0)
                    ->whereDate('expiry_date', '>=', Carbon::today())
                    ->sum('quantity');

                if ($available < $finalQty) {
                    $hasStockIssue = true;
                }
            }

            $status = $hasStockIssue ? 'DRAFT' : 'PENDING_CHEMIST';

            $batch = ProductionBatch::create([
                'bom_master_id' => $bom->id,
                'branch_id' => $bom->branch_id,
                'pack_config_id' => $request->pack_config_id,
                'batch_number'  => $request->batch_number ?? $this->generateProductionBatchCode(),
                'line_clearance_given_by' => $request->line_clearance_given_by,
                'raw_material_issued_on' => $request->raw_material_issued_on ?? null,
                'material_requisition_no' => $this->generateMaterialRequisitionCode(),
                'mfg_date'      => $request->mfg_date,
                'expiry_date'   => $request->expiry_date,
                'quantity'      => $request->quantity,
                'packing_type'  => $bom->packing_type,
                'pack_size'     => $bom->pack_size,
                'box_size'      => $bom->box_size,
                'no_of_boxes'   => $bom->no_of_boxes,
                'batch_size_qty' => $request->batch_size_qty,
                'product_type'  => $bom->product_type,
                'status'        => $status,
                'stage'         => 'REQUISITION',
                'last_stage'    => null,
                'created_by'    => auth()->id(),
            ]);

            foreach ($request->items as $row) {

                $bomItem = $bom->items
                    ->where('material_id', $row['material_id'])
                    ->first();

                $baseQty = $bomItem->quantity / $bom->batch_size * $request->batch_size_qty;
                $overagePercent = $bomItem->overage ?? 0;
                $finalQty = $baseQty + ($baseQty * $overagePercent / 100);

                ProductionBatchItem::create([
                    'production_batch_id' => $batch->id,
                    'material_id'         => $bomItem->material_id,
                    'warehouse_id'        => $row['warehouse_id'],
                    'base_quantity'       => round($baseQty, 4),
                    'overage_percent'     => $overagePercent,
                    'final_quantity'      => round($finalQty, 4),
                    'uom'                 => $bomItem->uom,
                    'status'              => $status,
                    'created_by'          => auth()->id(),
                ]);
            }

            DB::commit();

            if ($status == 'DRAFT') {
                return redirect()
                    ->route('production-batch.index')
                    ->with('error', 'Saved as Draft due to insufficient stock.');
            }

            return redirect()
                ->route('createAssignTeam', $batch->id)
                ->with('success', 'Production Batch created successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function checkStock(Request $request)
    {
        $materialId = $request->material_id;
        $requiredQty = $request->quantity;

        $stocks = RawMaterailBatch::where('raw_material_id', $materialId)
            ->where('quantity', '>', 0)
            ->whereDate('expiry_date', '>=', Carbon::today())
            ->get();

        if ($stocks->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Stock not available or expired'
            ]);
        }

        $available = $stocks->sum('quantity');
        $uom = optional($stocks->first())->uoms->name ?? '';

        if ($available < $requiredQty) {
            return response()->json([
                'status' => false,
                'message' => "Insufficient stock. Available: {$available} {$uom}"
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => "Stock Available: {$available} {$uom}"

        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);
        $batch = ProductionBatch::with('bomMaster', 'bomMaster.finishedGood', 'items.material', 'approvals.approver')->findOrFail($id);

        $chemistApproval = $batch->approvals->where('approval_level', 'CHEMIST')->first();
        $productionApproval = $batch->approvals->where('approval_level', 'HEAD_PRODUCTION')->first();
        $qaApproval = $batch->approvals->where('approval_level', 'HEAD_QA')->first();
        $uoms = Uom::where('status', '1')->get();
        $headProduction = AssignTeam::join('roles', 'roles.id', '=', 'assign_teams.role_id')
            ->join('users', 'users.id', '=', 'assign_teams.user_id')
            ->where('assign_teams.production_id', $id)
            ->where('assign_teams.stage', 'REQUISITION')
            ->select('users.full_name')
            ->first();

        // dd($batch);


        return view('admin.production-batch.show', compact('batch', 'chemistApproval', 'productionApproval', 'qaApproval', 'headProduction', 'uoms'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $productionBatch = ProductionBatch::with('bomMaster', 'items.material')->findOrFail($id);
        $bomMasters = BomMaster::with('finishedGood')->get();
        $warehouses = Warehouse::where('is_active', '1')->get();

        // dd($productionBatch);
        return view('admin.production-batch.edit', compact('productionBatch', 'bomMasters', 'warehouses'));
    }
    /** 
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $request->validate([
            'bom_master_id' => 'required|exists:bom_masters,id',
            'mfg_date'      => 'required|date',
            'expiry_date'   => 'required|date|after:mfg_date',
            'quantity'      => 'required|numeric|min:1',
            'items'         => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {

            $batch = ProductionBatch::findOrFail($id);
            $bom   = BomMaster::with('items')->findOrFail($request->bom_master_id);

            $hasStockIssue = false;

            foreach ($request->items as $row) {

                $bomItem = $bom->items
                    ->where('material_id', $row['material_id'])
                    ->first();

                if (!$bomItem) {
                    throw new \Exception('Invalid BOM Item detected');
                }

                $baseQty = $bomItem->quantity / $bom->batch_size * $request->batch_size_qty;
                $overage = $bomItem->overage ?? 0;
                $finalQty = $baseQty + ($baseQty * $overage / 100);

                $available = RawMaterailBatch::where('raw_material_id', $row['material_id'])
                    ->where('quantity', '>', 0)
                    ->whereDate('expiry_date', '>=', Carbon::today())
                    ->sum('quantity');

                if ($available < $finalQty) {
                    $hasStockIssue = true;
                }
            }

            $status = $hasStockIssue ? 'DRAFT' : 'PENDING_CHEMIST';

            $batch->update([
                'bom_master_id' => $bom->id,
                'branch_id' => $bom->branch_id,
                'mfg_date'      => $request->mfg_date,
                'expiry_date'   => $request->expiry_date,
                'quantity'      => $request->quantity,
                'packing_type'  => $bom->packing_type,
                'pack_size'     => $bom->pack_size,
                'box_size'      => $bom->box_size,
                'no_of_boxes'   => $bom->no_of_boxes,
                'batch_size_qty' => $request->batch_size_qty,
                'product_type'  => $bom->product_type,
                'status'        => $status,
                'stage'         => 'REQUISITION',
            ]);

            ProductionApproval::where('production_id', $batch->id)->delete();
            $batch->items()->delete();

            foreach ($request->items as $row) {

                $bomItem = $bom->items
                    ->where('material_id', $row['material_id'])
                    ->first();

                $baseQty = $bomItem->quantity / $bom->batch_size * $request->batch_size_qty;
                $overage = $bomItem->overage ?? 0;
                $finalQty = $baseQty + ($baseQty * $overage / 100);

                ProductionBatchItem::create([
                    'production_batch_id' => $batch->id,
                    'material_id'         => $bomItem->material_id,
                    'warehouse_id'        => $row['warehouse_id'],
                    'base_quantity'       => round($baseQty, 4),
                    'overage_percent'     => $overage,
                    'final_quantity'      => round($finalQty, 4),
                    'uom'                 => $bomItem->uom,
                    'status'              => $status,
                    'updated_by'          => auth()->id(),
                ]);
            }

            DB::commit();

            if ($status == 'DRAFT') {
                return redirect()
                    ->route('production-batch.index')
                    ->with('error', 'Updated but saved as Draft due to insufficient stock.');
            }

            return redirect()
                ->route('production-batch.index')
                ->with('success', 'Production Batch updated successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Approve or Reject the Production Batch.
     */

    public function approve(Request $request, $id)
    {
        $request->validate([
            'decision' => 'required|in:APPROVED,REJECTED',
            'remarks'  => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {

            $batch = ProductionBatch::findOrFail($id);
            $user  = auth()->user();

            if (in_array($batch->status, ['APPROVED', 'REJECTED'])) {
                throw new \Exception('This batch has already been processed.');
            }

            if ($user->hasRole('Chemist')) {
                $level = 'CHEMIST';
            } elseif ($user->hasRole('HEAD PRODUCTION')) {
                $level = 'HEAD_PRODUCTION';
            } elseif ($user->hasRole('HEAD QA')) {
                $level = 'HEAD_QA';
            } else {
                abort(403);
            }

            ProductionApproval::create([
                'production_id'  => $batch->id,
                'approver_id'    => $user->id,
                'approval_level' => $level,
                'decision'       => $request->decision,
                'approval_date'  => now(),
                'remarks'        => $request->remarks,
            ]);

            if ($request->decision === 'REJECTED') {

                $batch->update(['status' => 'REJECTED']);
            } else {

                if ($level === 'CHEMIST') {
                    $batch->update(['status' => 'PENDING_HEAD_PRODUCTION']);
                } elseif ($level === 'HEAD_PRODUCTION') {
                    $batch->update(['status' => 'PENDING_QA']);
                }
                // elseif ($level === 'HEAD_QA') {
                //     $batch->update(['status' => 'APPROVED']);
                // }

                elseif ($level === 'HEAD_QA') {

                    $batch->update(['status' => 'APPROVED']);

                    $issurance = StoreIssurance::create([
                        'requisition_production_batch_id' => $batch->id,
                        'bom_master_id' => $batch->bom_master_id,
                        'branch_id' => $batch->branch_id,
                        'batch_number' => $batch->batch_number,
                        'mfg_date' => $batch->mfg_date,
                        'expiry_date' => $batch->expiry_date,
                        'quantity' => $batch->quantity,
                        'status' => 'PENDING_STORE',
                        'batch_size_qty' => $batch->batch_size_qty,
                        'packing_type' => $batch->packing_type,
                        'product_type' => $batch->product_type,
                        'pack_size' => $batch->pack_size,
                        'box_size' => $batch->box_size,
                        'no_of_boxes' => $batch->no_of_boxes,
                        'material_requisition_no' => $batch->material_requisition_no,
                        'created_by' => auth()->id(),
                    ]);

                    foreach ($batch->items as $item) {
                        StoreIssuranceItem::create([
                            'store_issurance_id' => $issurance->id,
                            'material_id' => $item->material_id,
                            'warehouse_id' => $item->warehouse_id,
                            'base_quantity' => $item->base_quantity,
                            'final_quantity' => $item->final_quantity,
                            'uom' => $item->uom,
                            'overage_percent' => $item->overage_percent,
                            'status' => 'PENDING_STORE',
                            'created_by' => auth()->id(),
                        ]);
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Batch status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Download the Production Batch as a PDF.
     */

    public function downloadPdf($id)
    {
        $batch = ProductionBatch::with([
            'bomMaster.finishedGood',
            'items.material'
        ])->findOrFail($id);

        $chemistApproval = $batch->approvals()
            ->where('approval_level', 'CHEMIST')
            ->first();

        $productionApproval = $batch->approvals()
            ->where('approval_level', 'HEAD_PRODUCTION')
            ->first();

        $qaApproval = $batch->approvals()
            ->where('approval_level', 'HEAD_QA')
            ->first();

        $uoms = Uom::where('status', '1')->get();
        $pdf = Pdf::loadView(
            'admin.production-batch.pdf',
            compact('batch', 'chemistApproval', 'productionApproval', 'qaApproval', 'uoms')
        )->setPaper('A4', 'portrait');

        return $pdf->download(
            'Production_Batch_' . $batch->batch_number . '.pdf'
        );
    }


    private function getLatestTeam($batchId, $bomMasterId, $module)
    {
        $priority = [];

        if ($module == 'Voucher') {
            $priority = ['Voucher', 'Store', 'Requisition'];
        }

        if ($module == 'Store') {
            $priority = ['Store', 'Requisition'];
        }

        if ($module == 'Requisition') {
            $priority = ['Requisition'];
        }

        foreach ($priority as $type) {

            $team = ProductionBatchTeam::where('production_batch_id', $batchId)
                ->where('bom_master_id', $bomMasterId)
                ->where('module_type', $type)
                ->get()
                ->groupBy(['bom_type_id', 'role_id']);

            if ($team->isNotEmpty()) {
                return $team;
            }
        }

        return collect();
    }


    public function createAssignTeam($id)
    {
        $module = request()->module ?? 'Requisition';

        $batch = ProductionBatch::findOrFail($id);

        $processes = ProductionProcess::where('bom_master_id', $batch->bom_master_id)
            ->with([
                'bomType:id,name',
                'items.role' => function ($q) {
                    $q->with(['users' => function ($u) {
                        $u->where('full_name', '!=', 'Admin');
                    }]);
                }
            ])
            ->get();

        $assignedTeams = $this->getLatestTeam(
            $batch->id,
            $batch->bom_master_id,
            $module
        );

        return view('admin.production-batch.assign-team', compact(
            'batch',
            'processes',
            'assignedTeams',
            'module'
        ));
    }


    public function assignTeam(Request $request)
    {
        $request->validate([
            'batch_id' => 'required',
            'bom_master_id' => 'required',
            'module_type' => 'required',
            'users' => 'required|array'
        ]);

        ProductionBatchTeam::where('production_batch_id', $request->batch_id)
            ->where('module_type', $request->module_type)
            ->delete();

        foreach ($request->users as $bomTypeId => $roles) {

            foreach ($roles as $roleId => $users) {
                // if (empty($users)) {
                //             return back()->with('error', 'All roles must have at least one team member selected.');
                //         }
                foreach ($users as $userId) {

                    ProductionBatchTeam::create([
                        'production_batch_id' => $request->batch_id,
                        'bom_master_id' => $request->bom_master_id,
                        'bom_type_id' => $bomTypeId,
                        'role_id' => $roleId,
                        'user_id' => $userId,
                        'module_type' => $request->module_type,
                        'module_id' => $request->batch_id,
                        'created_by' => auth()->id(),
                    ]);
                }
            }
        }

        return back()->with('success', 'Team Assigned Successfully');
    }
}
