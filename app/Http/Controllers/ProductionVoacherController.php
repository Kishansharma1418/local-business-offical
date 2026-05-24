<?php

namespace App\Http\Controllers;

use App\Models\{    
    ProductionVoacher,
    ProductionVoacherItem,
    ProductionProcessItem,
    ProductionFlowStart,
    ProductionFlowStartItem,
    AssignTeam,
    Uom
};
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductionVoacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = ProductionVoacher::with('bomMaster.finishedGood')
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
                        'Pending_production' => '<span class="badge bg-warning">Pending Production</span>',
                        'IN_PRODUCTION'      => '<span class="badge bg-primary">In Production</span>',
                        'COMPLETED'          => '<span class="badge bg-success">Completed</span>',
                        'CANCELLED'          => '<span class="badge bg-danger">Cancelled</span>',
                    ];

                    return $statusLabels[$row->status] ?? '<span class="badge bg-secondary">Unknown</span>';
                 
                })

                ->addColumn('action', function ($row) {
                    return view('admin.production-voacher.action', compact('row'))->render();
                })

                ->rawColumns(['status', 'action', 'finished_good'])
                ->make(true);
        }

        return view('admin.production-voacher.index');
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $id = decrypt($id);
        $batch = ProductionVoacher::with('bomMaster', 'bomMaster.finishedGood', 'items.material')->findOrFail($id);
        $uoms = Uom::where('status', '1')->get();
          $headProduction = AssignTeam::join('roles', 'roles.id', '=', 'assign_teams.role_id')
                ->join('users', 'users.id', '=', 'assign_teams.user_id')
                ->where('assign_teams.production_id', $id)
                ->where('assign_teams.stage', 'PRODUCTION_VOACHER')
                ->select('users.full_name')
                ->first();      

      
        return view('admin.production-voacher.show', compact('batch',"headProduction","uoms"));
    }

    /**
     * Verify an item in the Production Voacher.
     */
    public function verifyItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:production_voacher_items,id'
        ]);

        $item = ProductionVoacherItem::findOrFail($request->item_id);

        $item->update([
            'recevied_checked_by' => true,
            'updated_by'  => auth()->id()
        ]);

        return response()->json(['success' => true]);
    }

    public function getVoacherRoles($id)
    {
        $production = ProductionVoacher::with('bomMaster')->findOrFail($id);

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

    /**
     * Approve or Reject the Production Voacher.
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'decision' => 'required|in:APPROVED,REJECTED',
            'notes'    => 'nullable|string|max:500',
        ]);

        $batch = ProductionVoacher::with('items')->findOrFail($id);

        // $pending = $batch->items->where('recevied_checked_by', false)->count();
        // if ($pending > 0) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'All items must be verified before approval'
        //     ], 403);
        // }

        if ($request->decision === 'APPROVED') {
            $batch->status = 'COMPLETED';

            $productionFlow = ProductionFlowStart::create([
                    //  'production_process_id'=> $batch->id,
                'production_voucher_id'=> $batch->id,
                    // 'assign_team_id'=> $batch->id,
                'bom_master_id'=> $batch->bom_master_id,
                'branch_id'=> $batch->branch_id,
                'batch_number'=> $batch->batch_number,
                'quantity'=> $batch->quantity,
                'batch_size_qty'=> $batch->batch_size_qty,
                'packing_type'=> $batch->packing_type,
                'product_type'=> $batch->product_type,
                'pack_size'=> $batch->pack_size,
                'box_size'=> $batch->box_size,  
                'no_of_boxes'=> $batch->no_of_boxes, 
                'status'=> 'Pending',
                'created_by' => auth()->id(),


            ]);

             foreach ($batch->items as $item) {
                        ProductionFlowStartItem::create([
                            'production_flow_start_id' => $productionFlow->id,
                            'material_id' => $item->material_id,
                            'warehouse_id' => $item->warehouse_id,
                            'base_quantity' => $item->base_quantity,
                            'final_quantity' => $item->final_quantity,
                            'uom' => $item->uom,
                            'overage_percent' => $item->overage_percent,
                            'weight_by' => $item->weight_by,
                            'status' => 'Pending',
                            'created_by' => auth()->id(),
                        ]);
                    }


        } else {
            $batch->status = 'CANCELLED';
        }

        $batch->verified_by_production = auth()->id();
        $batch->verified_notes_production = $request->notes;
        $batch->save();

         return redirect()->back()->with('success', 'Production Voacher ' . ($request->decision === 'APPROVED' ? 'approved' : 'rejected') . ' successfully.');
       
    }

    /**
     * Download the Production Voacher as a PDF.
     */
    public function downloadProductionPdf($id)
    {
        $batch = ProductionVoacher::with('bomMaster', 'bomMaster.finishedGood', 'items.material')->findOrFail($id);
        $uoms = Uom::where('status', '1')->get();
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('admin.production-voacher.pdf', compact('batch','uoms'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('production_voacher_' . $batch->batch_number . '.pdf');
    }

}