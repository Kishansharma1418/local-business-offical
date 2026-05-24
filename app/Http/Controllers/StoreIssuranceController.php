<?php

namespace App\Http\Controllers;

use App\Models\{StoreIssurance,BomMaster,Warehouse,StoreIssuranceItem,ProductionApproval,StoreIssuranceApprovel,ProductionVoacher,ProductionVoacherItem,ProductionProcessItem,ProductionBatchTeam,ProductionProcess,AssignTeam,RawMaterailBatch,StockLedger,RawMaterial,Uom};
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use PDF;


class StoreIssuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = StoreIssurance::with('bomMaster.finishedGood')
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
                        'PENDING_STORE'          => '<span class="badge bg-warning">Pending Store Approval</span>',
                        'PENDING_HEAD_PRODUCTION'=> '<span class="badge bg-info">Pending Head Production Approval</span>',
                        'PENDING_HEAD_QA'        => '<span class="badge bg-primary">Pending Head QA Approval</span>',
                        'ISSUED'                 => '<span class="badge bg-success">Issued</span>',
                        'REJECTED'               => '<span class="badge bg-danger">Rejected</span>',
                    ];

                    return $statusLabels[$row->status] ?? '<span class="badge bg-secondary">Unknown</span>';
                 
                })

                ->addColumn('action', function ($row) {
                    return view('admin.store-issurance.action', compact('row'))->render();
                })

                ->rawColumns(['status', 'action', 'finished_good'])
                ->make(true);
        }

        return view('admin.store-issurance.index');
    }


    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $id = decrypt($id);
        $batch = StoreIssurance::with('bomMaster', 'bomMaster.finishedGood', 'items.material','approvals.approver')->findOrFail($id);

        $chemistApproval = $batch->approvals->where('approval_level','STORE')->first();
        $productionApproval = $batch->approvals->where('approval_level','HEAD_PRODUCTION')->first();
        $qaApproval = $batch->approvals->where('approval_level','HEAD_QA')->first();
        $uoms = Uom::where('status', '1')->get();
          $headProduction = AssignTeam::join('roles', 'roles.id', '=', 'assign_teams.role_id')
                ->join('users', 'users.id', '=', 'assign_teams.user_id')
                ->where('assign_teams.production_id', $id)
                ->where('assign_teams.stage', 'STORE_ISSURANCE')
                ->select('users.full_name')
                ->first();
        
        return view('admin.store-issurance.show', compact('batch', 'chemistApproval', 'productionApproval', 'qaApproval',"headProduction","uoms"));
    }
    /**
     * Show the form for editing the specified resource.
     */
     public function edit( $id)
    {
        $id = decrypt($id);
        $productionBatch = StoreIssurance::with('bomMaster', 'items.material')->findOrFail($id);
        $bomMasters = BomMaster::with('finishedGood')->get();
        $warehouses = Warehouse::where('is_active', '1')->get();
        $uoms = Uom::where('status', '1')->get();

        return view('admin.store-issurance.edit', compact('productionBatch', 'bomMasters', 'warehouses','uoms'));
    }

    /**
     * Update the specified resource in storage.
     */

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'items'                    => 'required|array|min:1',
    //         // 'items.*.material_id'      => 'required|exists:materials,id',
    //         'items.*.weight_by'        => 'required|string|max:100',
    //         'items.*.warehouse_id'     => 'required|exists:warehouses,id',
    //     ]);

    //     DB::beginTransaction();

    //     try {

    //         $issurance = StoreIssurance::findOrFail($id);

    //         foreach ($request->items as $row) {

    //             $item = StoreIssuranceItem::where('store_issurance_id', $issurance->id)
    //                 ->where('material_id', $row['material_id'])
    //                 ->firstOrFail();

    //             $item->warehouse_id = $row['warehouse_id'];
    //             $item->weight_by    = $row['weight_by'];
    //             $item->status       = 'Pending_store';
    //             $item->updated_by   = auth()->id();
    //             $item->save();
    //         }

    //          StoreIssuranceApprovel::create([
    //             'store_issurance_id' => $issurance->id,
    //             'approver_id'        => auth()->id(),
    //             'approval_level'     => 'STORE',
    //             'decision'           => 'APPROVED',
    //             'remarks'            => 'Auto approved on weight update',
    //             'approval_date'      => now(),
    //         ]);

    //         $issurance->update([
    //             'status'     => 'PENDING_HEAD_PRODUCTION',
    //             'updated_by' => auth()->id(),
    //         ]);

    //         DB::commit();

    //         return redirect()
    //             ->route('store-issurance.index')
    //             ->with('success', 'Store Issurance updated successfully');

    //     } catch (\Exception $e) {

    //         DB::rollBack();
    //         return back()->withInput()->with('error', $e->getMessage());
    //     }
    // }



    public function update(Request $request, $id)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.weight_by' => 'required|string|max:100',
            'items.*.warehouse_id' => 'required|exists:warehouses,id',
            'items.*.batch_data' => 'required'
        ]);

        DB::beginTransaction();

        try {
           
            $issurance = StoreIssurance::lockForUpdate()->findOrFail($id);

            if ($issurance->is_stock_deducted) {
                throw new \Exception("Stock already deducted for this voucher.");
            }

            foreach ($request->items as $row) {

                $item = StoreIssuranceItem::where('store_issurance_id', $id)
                    ->where('material_id', $row['material_id'])
                    ->firstOrFail();

                $item->weight_by = $row['weight_by'];
                $item->warehouse_id = $row['warehouse_id'];
                $item->status = 'APPROVED';
                $item->save();

                $batchData = json_decode($row['batch_data'], true);

                if (!$batchData || !is_array($batchData)) {
                    throw new \Exception("Batch selection missing.");
                }

                foreach ($batchData as $batchRow) {
                    $batch = RawMaterailBatch::lockForUpdate()
                        ->findOrFail($batchRow['batch_id']);

                    if ($batch->quantity < $batchRow['qty']) {
                        throw new \Exception("Insufficient stock in batch ".$batch->batch_no);
                    }

                    $batch->quantity -= $batchRow['qty'];
                    $batch->save();

                    $rawMaterial = RawMaterial::lockForUpdate()
                        ->findOrFail($row['material_id']);

                    if ($rawMaterial->stock_all < $batchRow['qty']) {
                        throw new \Exception("Main stock mismatch error.");
                    }

                    $rawMaterial->stock_all -= $batchRow['qty'];
                    $rawMaterial->save();

                    StockLedger::create([
                        'issurance_id'          => $issurance->id,
                        'bom_master_id'         => $issurance->bom_master_id,
                        'uom_id'                => $item->uom,
                        'raw_materail_batch_id' => $batch->id,
                        'raw_materail_id'       => $row['material_id'],
                        'qty'                   => $batchRow['qty'],
                        'type'                  => 'ISSUE',
                        'referance_id'          => $issurance->id,
                        'approved_by'           => auth()->id(),
                    ]);
                }
            }
            StoreIssuranceApprovel::create([
                'store_issurance_id' => $issurance->id,
                'approver_id'        => auth()->id(),
                'approval_level'     => 'STORE',
                'decision'           => 'APPROVED',
                'remarks'            => 'Auto approved on weight update',
                'approval_date'      => now(),
            ]);

            $issurance->update([
                'status' => 'PENDING_HEAD_PRODUCTION',
                'is_stock_deducted' => 1
            ]);

            DB::commit();

            return redirect()
                ->route('store-issurance.index')
                ->with('success', 'Stock deducted successfully');

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Approve or reject a Store Issurance.
     */

    public function approve(Request $request, $id)
    {
        $request->validate([
            'decision' => 'required|in:APPROVED,REJECTED',
            'remarks'  => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {

            $issurance = StoreIssurance::with(['items'])->findOrFail($id);
            $user = auth()->user();

            if (in_array($issurance->status, ['ISSUED', 'REJECTED'])) {
                throw new \Exception('This Store Issurance is already finalized.');
            }

            if ($issurance->status === 'PENDING_HEAD_PRODUCTION') {
                $pending = $issurance->items->where('recevied_checked_by', false)->count();
                if ($pending > 0) {
                    abort(403, 'All items must be verified by Head Production');
                }
            }

            if ($user->hasRole('Store')) {
                $level = 'STORE';
            } elseif ($user->hasRole('HEAD PRODUCTION')) {
                $level = 'HEAD_PRODUCTION';
            } elseif ($user->hasRole('HEAD QA')) {
                $level = 'HEAD_QA';
            } else {
                abort(403);
            }

            StoreIssuranceApprovel::create([
                'store_issurance_id' => $issurance->id,
                'approver_id'        => $user->id,
                'approval_level'     => $level,
                'decision'           => $request->decision,
                'remarks'            => $request->remarks,
                'approval_date'      => now(),
            ]);

            if ($request->decision === 'REJECTED') {

                $issurance->update(['status' => 'REJECTED']);

            } else {

                if ($level === 'STORE') {
                    $issurance->update(['status' => 'PENDING_HEAD_PRODUCTION']);
                }

                elseif ($level === 'HEAD_PRODUCTION') {
                    $issurance->update(['status' => 'PENDING_HEAD_QA']);
                }

                elseif ($level === 'HEAD_QA') {

                    $production = ProductionVoacher::create([
                        'store_issurance_id'      => $issurance->id,
                        'bom_master_id'           => $issurance->bom_master_id,
                        'branch_id'               => $issurance->branch_id,
                        'batch_number'            => $issurance->batch_number,
                        'mfg_date'                => $issurance->mfg_date,
                        'expiry_date'             => $issurance->expiry_date,
                        'quantity'                => $issurance->quantity,
                        'batch_size_qty'          => $issurance->batch_size_qty,
                        'packing_type'            => $issurance->packing_type,
                        'product_type'            => $issurance->product_type,
                        'pack_size'              => $issurance->pack_size,
                        'box_size'               => $issurance->box_size,
                        'no_of_boxes'            => $issurance->no_of_boxes,
                        'rate'                    => $issurance->rate,
                        'material_requisition_no' => $issurance->material_requisition_no,
                        'line_clearance_given_by' => $issurance->line_clearance_given_by,
                        'raw_material_issued_on'  => $issurance->raw_material_issued_on,
                        'status'                  => 'Pending_production',
                        'created_by'              => $user->id,
                    ]);

                    foreach ($issurance->items as $item) {
                        ProductionVoacherItem::create([
                            'production_voacher_id' => $production->id,
                            'material_id'           => $item->material_id,
                            'warehouse_id'          => $item->warehouse_id,
                            'base_quantity'         => $item->base_quantity,
                            'final_quantity'        => $item->final_quantity,
                            'uom'                   => $item->uom,
                            'overage_percent'       => $item->overage_percent,
                            'specfication'          => $item->specfication,
                            'control_ref_no'        => $item->control_ref_no,
                            'analytical_report_no'  => $item->analytical_report_no,
                            'weight_by'             => $item->weight_by,
                            'recevied_checked_by'   => $item->recevied_checked_by,
                            'status'                => 'Pending_production',
                            'created_by'            => $user->id,
                        ]);
                    }

                    $issurance->update(['status' => 'ISSUED']);
                }
            }

            DB::commit();
            return back()->with('success', 'Store Issurance processed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }


    /**
     * Download Store Issurance PDF.
     */
    public function downloadStoreIssurancePdf($id)
    {
        $batch = StoreIssurance::with(['bomMaster.finishedGood','items.material'])->findOrFail($id);
        $uoms = Uom::where('status', '1')->get();
        $chemistApproval = $batch->approvals()
            ->where('approval_level', 'STORE')
            ->first();

        $productionApproval = $batch->approvals()
            ->where('approval_level', 'HEAD_PRODUCTION')
            ->first();

        $qaApproval = $batch->approvals()
            ->where('approval_level', 'HEAD_QA')
            ->first();

        $pdf = Pdf::loadView(
            'admin.store-issurance.pdf',
            compact('batch',
                'chemistApproval',
                'productionApproval',
                'qaApproval',
                'uoms'
            )
        )->setPaper('A4', 'portrait');

        return $pdf->download(
            'Store_Issurance_' . $batch->batch_number . '.pdf'
        );
    }


    /**
     * Verify an item in the Store Issurance.
     */
    public function verifyItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:store_issurance_items,id'
        ]);

        $item = StoreIssuranceItem::findOrFail($request->item_id);

        $item->update([
            'recevied_checked_by' => true,
            'updated_by'  => auth()->id()
        ]);

        return response()->json(['success' => true]);
    }


    public function getBatches(Request $request)
    {
        return RawMaterailBatch::with('uoms')->where('raw_material_id', $request->material_id)
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->get();
    }




}