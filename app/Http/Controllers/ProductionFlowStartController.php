<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    ProductionVoacher,
    ProductionVoacherItem,
    ProductionProcessItem,
    ProductionFlowStart,
    ProductionFlowStartItem,
    BomType,
    ProductionProcess,
    ProductionBatchTeam,
    QualtyCheck,
    ProductionFlowStep,
    Uom,
    BatchManagement,
    FinishedGood,
    ProductionPackingDetail,
    ProductionPage15Log,
    ProductionPage16Reconciliation,
    ProductionFinishedGoodTransfer,
    CoatedTabletProductionForm,
    CompressionIpqcRecord,
    CapsuleFilling,
    BomMaster,
    SyrupFillingForm,
    CapsuleForm1,
    CapsuleCleningForm2
};
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductionFlowStartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $data = ProductionFlowStart::with('bomMaster.finishedGood')
                ->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()

                ->editColumn('mfg_date', function ($row) {
                    return date('d-m-Y', strtotime($row->mfg_date) ?? '-');
                })

                ->editColumn('expiry_date', function ($row) {
                    return date('d-m-Y', strtotime($row->expiry_date) ?? '-');
                })

                ->addColumn('finished_good', function ($row) {
                    return $row->bomMaster?->bom_number
                        . ' - ' . $row->bomMaster?->bom_version
                        . ' - ' . ($row->bomMaster?->finishedGood?->name ?? '');
                })

                ->addColumn('current_step', function ($row) {

                    if ($row->status == 'completed') {
                        return '<span class="badge bg-success">Production Completed</span>';
                    }

                    if (!$row->current_step || $row->current_step == 0) {
                        return '<span class="badge bg-secondary">Not Started</span>';
                    }

                    $stepNumber = $row->current_step;

                    $step = ProductionProcess::where('bom_master_id', $row->bom_master_id)
                        ->skip($stepNumber - 1)
                        ->first();

                    if ($step && $step->bomType) {
                        return '<span class="badge bg-warning">
                                     Step ' . $stepNumber . ' - ' . $step->bomType->name . '
                                </span>';
                    }

                    return '<span class="badge bg-primary">In Progress</span>';
                })


                ->addColumn('status', function ($row) {
                    $statusLabels = [
                        'Pending' => '<span class="badge bg-warning">Pending</span>',
                        'IN_PROGRESS' => '<span class="badge bg-primary">IN PROGRESS</span>',
                        'completed' => '<span class="badge bg-success">Completed</span>',
                    ];

                    return $statusLabels[$row->status] ?? '<span class="badge bg-secondary">Unknown</span>';
                })

                ->addColumn('action', function ($row) {
                    return view('admin.production-start.action', compact('row'))->render();
                })

                ->rawColumns(['status', 'action', 'finished_good', 'current_step'])
                ->make(true);
        }

        return view('admin.production-start.index');
    }

    public function show($id)
    {
        $productionFlowStart = ProductionFlowStart::with(['bomMaster', 'bomMaster.finishedGood', 'flowItems.material'])->findOrFail($id);

        $uoms = Uom::where('status', '1')->get();
        $processes = ProductionProcess::where('bom_master_id', $productionFlowStart->bom_master_id)
            ->with(['bomType:id,name', 'items.bomItem.material', 'items.role'])
            ->orderBy('sequence')
            ->get();

        $allTeams = ProductionBatchTeam::where('bom_master_id', $productionFlowStart->bom_master_id)
            ->with(['user', 'role'])
            ->get();

        $processTeams = [];

        foreach ($processes as $process) {

            $roleIds = $process->items
                ->pluck('roles')
                ->filter()
                ->unique()
                ->toArray();

            if (!empty($roleIds)) {

                $teams = $allTeams
                    ->whereIn('role_id', $roleIds)
                    ->groupBy('role_id');

                $processTeams[$process->id] = $teams;
            }
        }

        $qcTeam = $allTeams->where('role.name', 'HEAD QA');
        $packingDetail = ProductionPackingDetail::where(
            'production_flow_start_id',
            $productionFlowStart->id
        )->first();

        $page15Logs = ProductionPage15Log::where(
            'production_flow_start_id',
            $productionFlowStart->id
        )->get();

        $page16Reconciliations = ProductionPage16Reconciliation::where(
            'production_flow_start_id',
            $productionFlowStart->id
        )->get();

        $coatingCheck = CoatedTabletProductionForm::where(
            'production_flow_id',
            $productionFlowStart->id
        )->first();

        $capsuleFilling = CapsuleFilling::where(
            'production_flow_start_id',
            $productionFlowStart->id
        )->first();

        $compressionIpqc = CompressionIpqcRecord::where(
            'production_flow_id',
            $productionFlowStart->id
        )->first();


        $capsuleForm1  = CapsuleForm1::where(
            'production_flow_start_id',
            $productionFlowStart->id
        )->first();

        $capsulecleningForm2  = CapsuleCleningForm2::where(
            'production_flow_start_id',
            $productionFlowStart->id
        )->get();
        return view('admin.production-start.show', compact(
            'productionFlowStart',
            'processes',
            'processTeams',
            'qcTeam',
            'packingDetail',
            'uoms',
            'page15Logs',
            'coatingCheck',
            'page16Reconciliations',
            'capsuleFilling',
            'compressionIpqc',
            'capsuleForm1',
            'capsulecleningForm2'

        ));
    }


    public function update(Request $request, $id)
    {
        $production = ProductionFlowStart::findOrFail($id);

        if ($request->action_type === 'START_PRODUCTION') {

            $request->validate([
                'mfg_date' => 'required|date',
                'expiry_date' => 'required|date|after:mfg_date',
            ]);

            $production->mfg_date = $request->mfg_date;
            $production->expiry_date = $request->expiry_date;

            $production->current_step = 1;

            $production->status = 'IN_PROGRESS';

            $production->save();

            return redirect()->back()->with('success', 'Production Started Successfully');
        }
        if ($request->action_type == 'QC_UPLOAD_REPORT') {

            $request->validate([
                'qc_report' => 'required|mimes:pdf|max:2048',
                'step' => 'required'
            ]);

            $filePath = $request->file('qc_report')
                ->store('qc_reports', 'public');

            QualtyCheck::create([
                'production_flow_start_id' => $production->id,
                'bom_master_id' => $production->bom_master_id,
                'step_number' => $request->step,
                'report_path' => $filePath,
                'checked_by' => auth()->id(),
            ]);

            return back()->with('success', 'New QC Uploaded');
        }

        if ($request->action_type == 'QC_UPDATE_STATUS') {

            $request->validate([
                'remarks' => 'required',
                'status' => 'required|in:approved,rejected',
                'qc_id' => 'required',
                'step' => 'required'
            ]);

            $qc = QualtyCheck::where('id', $request->qc_id)
                ->where('production_flow_start_id', $production->id)
                ->where('step_number', $request->step)
                ->whereNull('status')
                ->first();

            if (!$qc) {
                return back()->with('error', 'Invalid QC attempt');
            }

            DB::transaction(function () use ($qc, $request, $production) {

                $qc->update([
                    'remarks' => $request->remarks,
                    'status' => $request->status,
                ]);

                if ($request->status == 'approved' && $production->current_step == $request->step) {

                    $totalSteps = $production->bomMaster->processes->count();

                    if ($production->current_step < $totalSteps) {
                        $production->increment('current_step');
                    } else {
                        $production->update([
                            'status' => 'completed',
                            // 'completed_at' => now(),
                        ]);
                    }
                }
            });

            return back()->with('success', 'QC Status Submitted');
        }
        if ($request->action_type == 'COMPLETE_STEP') {

            if ($production->current_step != $request->step) {
                return back()->with('error', 'Step already completed');
            }

            DB::transaction(function () use ($production) {

                $totalSteps = $production->bomMaster->processes->count();

                ProductionFlowStep::updateOrCreate(
                    [
                        'production_flow_start_id' => $production->id,
                        'step_number' => $production->current_step
                    ],
                    [
                        'bom_master_id' => $production->bom_master_id,
                        'step_status' => 'completed',
                        'started_at' => now(),
                        'completed_at' => now(),
                        'updated_by' => auth()->id(),
                    ]
                );

                if ($production->current_step < $totalSteps) {
                    $production->increment('current_step');
                } else {
                    $production->update([
                        'status' => 'completed',
                        'current_step' => $totalSteps + 1,
                        'completed_at' => now(),
                    ]);
                }
            });

            return redirect()->route(
                'production-start.show',
                [$production->id, 'view_step' => $production->current_step]
            )->with('success', 'Step Completed');
        }

        if ($request->action_type == 'SAVE_COATING_CHECK') {
            $request->validate([
                'thickness' => 'required|array|size:20',
                'thickness.*' => 'required|numeric',
                'weight' => 'required|array|size:20',
                'weight.*' => 'required|numeric',
                'hardness' => 'required|array|size:20',
                'hardness.*' => 'required|numeric',
                'average_thickness' => 'required|numeric',
                'average_weight' => 'required|numeric',
                'average_hardness' => 'required|numeric',
            ]);

            CoatedTabletProductionForm::updateOrCreate(
                [
                    'production_flow_id' => $production->id,
                    'bom_master_id' => $production->bom_master_id,
                ],
                [
                    'production_batch_id' => $production->id,
                    'batch_no' => $production->batch_number,
                    'product_name' => $production->bomMaster?->finishedGood?->name ?? null,
                    'thickness' => json_encode($request->thickness),
                    'weight' => json_encode($request->weight),
                    'hardness' => json_encode($request->hardness),
                    'average_thickness' => $request->average_thickness,
                    'average_weight' => $request->average_weight,
                    'average_hardness' => $request->average_hardness,
                    'tablets_inspected_date' => now(),
                ]
            );

            return back()->with('success', 'Coating Check Saved');
        }

        if ($request->action_type == 'SAVE_COMPRESSION_IPQC') {

            $request->validate([
                'datetime' => 'required|array',
                'datetime.*' => 'nullable|date',

                'weight20' => 'required|array',
                'weight20.*' => 'nullable|numeric',

                'dt' => 'required|array',
                'dt.*' => 'nullable|numeric',

                'hardness' => 'required|array',
                'hardness.*' => 'nullable|numeric',

                'friability' => 'required|array',
                'friability.*' => 'nullable|numeric',

                'thickness' => 'required|array',
                'thickness.*' => 'nullable|numeric',

                'remarks' => 'nullable|array'
            ]);

            CompressionIpqcRecord::updateOrCreate(
                [
                    'production_flow_id' => $production->id,
                    'bom_master_id' => $production->bom_master_id
                ],
                [
                    'product_name' => $production->bomMaster?->finishedGood?->name,
                    'batch_no' => $production->batch_number,

                    'datetime' => json_encode($request->datetime),
                    'weight20' => json_encode($request->weight20),
                    'dt' => json_encode($request->dt),
                    'hardness' => json_encode($request->hardness),
                    'friability' => json_encode($request->friability),
                    'thickness' => json_encode($request->thickness),
                    'remarks' => json_encode($request->remarks),

                    'inspected_by' => $request->inspected_by,
                    'total_weight_uncoated' => $request->total_weight_uncoated,
                    'total_weight_rejected' => $request->total_weight_rejected,

                    'created_by' => auth()->id()
                ]
            );

            return back()->with('success', 'Compression IPQC Saved Successfully');
        }
        if ($request->action_type == 'SAVE_CAPSULE_FILLING') {

            $request->validate([
                'datetime' => 'required|array|size:10',
                'datetime.*' => 'nullable|date',

                'weight' => 'required|array|size:10',
                'weight.*' => 'nullable|numeric',

                'leakage' => 'required|array|size:10',
                'leakage.*' => 'nullable|string',

                'cracks' => 'required|array|size:10',
                'cracks.*' => 'nullable|string',

                'defects' => 'required|array|size:10',
                'defects.*' => 'nullable|string',

                'dt' => 'required|array|size:10',
                'dt.*' => 'nullable|numeric',

                'sign' => 'required|array|size:10',
                'sign.*' => 'nullable|string',

                'total_filled_weight' => 'nullable|numeric',
                'total_rejected_weight' => 'nullable|numeric',
            ]);

            $records = [];

            for ($i = 0; $i < 10; $i++) {
                $records[] = [
                    'row_no' => $i + 1,
                    'datetime' => $request->datetime[$i] ?? null,
                    'weight' => $request->weight[$i] ?? null,
                    'leakage' => $request->leakage[$i] ?? null,
                    'cracks' => $request->cracks[$i] ?? null,
                    'defects' => $request->defects[$i] ?? null,
                    'dt' => $request->dt[$i] ?? null,
                    'sign' => $request->sign[$i] ?? null,
                ];
            }

            CapsuleFilling::updateOrCreate(
                [
                    'production_flow_start_id' => $production->id,
                    'bom_master_id' => $production->bom_master_id
                ],
                [
                    'product_id' => $production->product_id ?? null,
                    'product_name' => $production->bomMaster?->finishedGood?->name ?? null,
                    'batch_no' => $production->batch_number,

                    'capsule_records' => $records,

                    'filled_capsules_inspected_by' => $request->inspected_by,
                    'inspection_date' => now(),

                    'total_weight_filled_capsules' => $request->total_filled_weight,
                    'total_weight_rejected_capsules' => $request->total_rejected_weight,

                    'production_chemist_signature' => auth()->user()->full_name,
                    'production_chemist_date' => now(),

                    'qa_chemist_signature' => auth()->user()->full_name,
                    'qa_chemist_date' => now(),
                ]
            );

            return back()->with('success', 'Capsule Filling Saved Successfully');
        }

        if ($request->action_type == 'SAVE_SYRUP_FILLING') {
            $hasData =
                !empty(array_filter($request->datetime ?? [])) ||
                !empty(array_filter($request->filled_volume[1] ?? [])) ||
                !empty(array_filter($request->filled_volume[2] ?? [])) ||
                !empty(array_filter($request->ropp_cap ?? [])) ||
                !empty($request->total_filled_qty);

            if (!$hasData) {
                return back()->withErrors(['error' => 'Please filled all fields']);
            }

            $data = [
                'production_flow_start_id' => $production->id,
                'bom_master_id' => $production->bom_master_id,
                'product_name' => $production->bomMaster->finishedGood->name ?? '',
                'batch_number' => $production->batch_number,
                'datetime' => $request->datetime,
                'filled_volume' => $request->filled_volume,
                'ropp_cap' => $request->ropp_cap,
                'checked_by' => $request->checked_by,
                'verified_by' => $request->verified_by,
                'total_filled_qty' => $request->total_filled_qty,
                'prev_product' => $request->prev_product,
                'temprature' => $request->temprature,
                'colour_appearance' => $request->colour_appearance,
                'ph' => $request->ph,
                'prev_batch' => $request->prev_batch,
                'line_clearance_by' => $request->line_clearance_by,
                'inspection_start' => $request->inspection_start,
                'inspection_done_by' => $request->inspection_done_by,
                'inspection_completed' => $request->inspection_completed,
                'inspection_verified' => $request->inspection_verified,
            ];

            SyrupFillingForm::updateOrCreate(
                ['production_flow_start_id' => $production->id],
                $data
            );

            return redirect()->back()->with('success', 'Syrup Filling IPQC Saved ✅');
        }

        if ($request->action_type == 'SAVE_PACKING_DETAILS') {
            $request->validate([
                'previous_product'   => 'required|string|max:255',
                'previous_batch_no'  => 'required|string|max:255',
                'packing_date'       => 'required|date',
                'machine_id'         => 'required|string|max:255',
                'machine_operator'   => 'required|string|max:255',
                'bfr_temperature'    => 'required|string|max:100',
                'sfr_temperature'    => 'required|string|max:100',
                'duration'           => 'nullable|string|max:255',

                'carton_batch_no'    => 'nullable|string|max:255',
                'carton_mfd'         => 'nullable|date',
                'carton_exp'         => 'nullable|date',
                'carton_mrp'         => 'nullable|numeric',
                'carton_printed_date' => 'nullable|date',

                'foil_batch_no'      => 'nullable|string|max:255',
                'foil_mfd'           => 'nullable|date',
                'foil_exp'           => 'nullable|date',
                'foil_mrp'           => 'nullable|numeric',
                'foil_printed_date'  => 'nullable|date',

                'step'               => 'required'
            ]);

            DB::transaction(function () use ($request, $production) {

                ProductionPackingDetail::updateOrCreate(
                    [
                        'production_flow_start_id' => $production->id,
                        'bom_master_id'            => $production->bom_master_id,
                    ],
                    [
                        'product_name'        => $production->bomMaster?->finishedGood?->name ?? null,
                        'batch_no'            => $production->batch_number,

                        // Line Clearance
                        'previous_product'    => $request->previous_product,
                        'previous_batch_no'   => $request->previous_batch_no,
                        'line_clearance_date' => now(),

                        // Machine Details
                        'packing_date'        => $request->packing_date,
                        'machine_id'          => $request->machine_id,
                        'machine_operator'    => $request->machine_operator,
                        'bfr_temperature'     => $request->bfr_temperature,
                        'sfr_temperature'     => $request->sfr_temperature,
                        'duration'            => $request->duration,
                        'verified_by'         => auth()->user()->full_name ?? null,

                        // Carton
                        'carton_batch_no'     => $request->carton_batch_no,
                        'carton_mfd'          => $request->carton_mfd,
                        'carton_exp'          => $request->carton_exp,
                        'carton_mrp'          => $request->carton_mrp,
                        'carton_printed_date' => $request->carton_printed_date,

                        // Foil
                        'foil_batch_no'       => $request->foil_batch_no,
                        'foil_mfd'            => $request->foil_mfd,
                        'foil_exp'            => $request->foil_exp,
                        'foil_mrp'            => $request->foil_mrp,
                        'foil_printed_date'   => $request->foil_printed_date,
                    ]
                );
            });

            return back()->with('success', 'Packing Details Saved Successfully');
        }

        if ($request->action_type == 'SAVE_PAGE15_BULK') {
            foreach ($request->rows as $row) {


                if (empty($row['leak_date']) && empty($row['packing_date'])) {
                    continue;
                }

                ProductionPage15Log::create([
                    'production_flow_start_id' => $production->id,
                    'product_name' => $production->bomMaster->finishedGood->name,
                    'batch_no' => $production->batch_number,

                    'leak_date' => $row['leak_date'],
                    'leak_time' => $row['leak_time'],
                    'leak_done_by' => $row['leak_done_by'],
                    'leak_result' => $row['leak_result'],
                    'leak_verified_by' => auth()->user()->full_name,
                    'leak_remarks' => $row['leak_remarks'],

                    'packing_date' => $row['packing_date'],
                    'previous_product' => $request->previous_product,
                    'previous_product_batch_no' => $request->previous_product_batch_no,
                    'line_clierence_by' => $request->line_clierence_by,
                    'strip_checked_by' => $row['strip_checked_by'],
                    'carton_packing_done_by' => $row['carton_packing_done_by'],
                    'packed_carton_count' => $row['packed_carton_count'] ?? 0,
                    'rejected_carton_count' => $row['rejected_carton_count'] ?? 0,
                    'packing_verified_by' => auth()->user()->full_name,
                ]);
            }

            return back()->with('success', 'Page 15 Data Saved');
        }

        if ($request->action_type == 'SAVE_CAPSULE_FORM1') {

            $request->validate([
                'document_no'              => 'nullable|string|max:255',
                'mfg_license_no'           => 'nullable|string|max:255',
                'generic_name'             => 'nullable|string|max:255',
                'product_name'             => 'nullable|string|max:255',
                'composition'              => 'nullable|string',
                'master_formula_record_no' => 'nullable|string|max:255',
                'batch_no'                 => 'nullable|string|max:255',
                'shelf_life'               => 'nullable|string|max:255',
                'mfg_date'                 => 'nullable|date',
                'exp_date'                 => 'nullable|date|after_or_equal:mfg_date',
                'batch_size'               => 'nullable|string|max:255',
                'unit_packing'             => 'nullable|string|max:255',
                'batch_commenced_on'       => 'nullable|date',
                'batch_completed_on'       => 'nullable|date',
                'issued_by'                => 'nullable|string|max:255',
                'issued_date'              => 'nullable|date',
                'received_by'              => 'nullable|string|max:255',
                'received_date'            => 'nullable|date',
                'prepared_by'              => 'nullable|string|max:255',
                'prepared_date'            => 'nullable|date',
                'reviewed_by'              => 'nullable|string|max:255',
                'reviewed_date'            => 'nullable|date',
                'approved_by'              => 'nullable|string|max:255',
                'approved_date'            => 'nullable|date',
            ]);

            CapsuleForm1::updateOrCreate(
                [
                    'production_flow_start_id' => $production->id,
                    'bom_master_id' => $production->bom_master_id
                ],
                [
                    'document_no'              => $request->document_no,
                    'mfg_license_no'           => $request->mfg_license_no,
                    'generic_name'             => $request->generic_name,
                    'product_name'             => $request->product_name ?? $production->bomMaster?->finishedGood?->name,
                    'composition'              => $request->composition,
                    'master_formula_record_no' => $request->master_formula_record_no,
                    'batch_no'                 => $request->batch_no ?? $production->batch_number,
                    'shelf_life'               => $request->shelf_life,
                    'mfg_date'                 => $request->mfg_date ?? $production->mfg_date,
                    'exp_date'                 => $request->exp_date ?? $production->expiry_date,
                    'batch_size'               => $request->batch_size,
                    'unit_packing'             => $request->unit_packing,
                    'batch_commenced_on'       => $request->batch_commenced_on,
                    'batch_completed_on'       => $request->batch_completed_on,
                    'issued_by'                => $request->issued_by,
                    'issued_date'              => $request->issued_date,
                    'received_by'              => $request->received_by,
                    'received_date'            => $request->received_date,
                    'prepared_by'              => $request->prepared_by,
                    'prepared_date'            => $request->prepared_date,
                    'reviewed_by'              => $request->reviewed_by,
                    'reviewed_date'            => $request->reviewed_date,
                    'approved_by'              => $request->approved_by,
                    'approved_date'            => $request->approved_date,
                ]
            );

            return back()->with('success', 'Capsule Form 1 Saved Successfully');
        }

        if ($request->action_type == 'SAVE_CAPSULE_FORM2') {

            $capsuleForm1 = CapsuleForm1::where('production_flow_start_id', $production->id)->first();

            // Pehle purane records delete karo
            CapsuleCleningForm2::where('production_flow_start_id', $production->id)->delete();

            // Har row ke liye alag DB record
            foreach ($request->rows ?? [] as $row) {

                // Empty rows skip
                if (empty($row['equipment_name']) && empty($row['equipment_id'])) {
                    continue;
                }

                CapsuleCleningForm2::create([
                    'capsule_form1_id'         => $capsuleForm1?->id,
                    'production_flow_start_id' => $production->id,
                    'bom_master_id'            => $production->bom_master_id,
                    'product_name'             => $production->bomMaster?->finishedGood?->name,
                    'equipment_name'           => $row['equipment_name'] ?? null,
                    'equipment_id'             => $row['equipment_id'] ?? null,
                    'previous_product_name'    => $row['previous_product_name'] ?? null,
                    'previous_batch_no'        => $row['previous_batch_no'] ?? null,
                    'cleaned_by'               => $row['cleaned_by'] ?? null,
                    'verified_by'              => $row['verified_by'] ?? null,
                    'line_clierence_given_by'  => $request->line_clierence_given_by,
                    'date'                     => $request->date,
                ]);
            }

            return back()->with('success', 'Equipment Cleaning Form Saved Successfully');
        }

        if ($request->action_type == 'SAVE_PAGE16') {

            foreach ($request->materials as $type => $data) {

                $totalIssued = $data['qty_issued'] + $data['additional_required'];
                $totalPacked = $data['packed_qty'] + $data['sample_qty'] + $data['specimen_qty'];
                $totalConsumed = $totalPacked + $data['rejection_qty'];
                $finalTotal = $totalConsumed + $data['returned_qty'];

                ProductionPage16Reconciliation::updateOrCreate(
                    [
                        'production_flow_start_id' => $production->id,
                        'material_type' => $type
                    ],
                    [
                        'std_qty' => $data['std_qty'],
                        'qty_issued' => $data['qty_issued'],
                        'additional_required' => $data['additional_required'],
                        'total_issued' => $totalIssued,

                        'packed_qty' => $data['packed_qty'],
                        'sample_qty' => $data['sample_qty'],
                        'specimen_qty' => $data['specimen_qty'],
                        'total_packed' => $totalPacked,

                        'rejection_qty' => $data['rejection_qty'],
                        'total_consumed' => $totalConsumed,
                        'returned_qty' => $data['returned_qty'],
                        'final_total' => $finalTotal,
                    ]
                );
            }

            return back()->with('success', 'Page 16 Saved');
        }
    }


    public function stockInFinishedGoods(Request $request, $id)
    {
        $request->validate([
            'finished_goods_qty' => 'required|numeric|min:1',
        ]);

        $production = ProductionFlowStart::with('bomMaster')
            ->findOrFail($id);

        // if ($production->status !== 'completed') {
        //     return back()->with('error', 'Production not completed yet');
        // }

        if ($production->stock_in_done) {
            return back()->with('error', 'Stock already created');
        }

        DB::transaction(function () use ($request, $production) {



            $requisitionFile = null;
            if ($request->requisition_sheet_rm == 'Yes' && $request->hasFile('requisition_sheet_rm_file')) {
                $requisitionFile = $request->file('requisition_sheet_rm_file')
                    ->store('batch_release', 'public');
            }

            $cartonFile = null;
            if ($request->specimen_carton == 'Yes' && $request->hasFile('specimen_carton_file')) {
                $cartonFile = $request->file('specimen_carton_file')
                    ->store('batch_release', 'public');
            }

            $foilFile = null;
            if ($request->specimen_printed_foil == 'Yes' && $request->hasFile('specimen_printed_foil_file')) {
                $foilFile = $request->file('specimen_printed_foil_file')
                    ->store('batch_release', 'public');
            }

            $bulkTestingFile = null;
            if ($request->bulk_testing_report == 'Yes' && $request->hasFile('bulk_testing_report_file')) {
                $bulkTestingFile = $request->file('bulk_testing_report_file')
                    ->store('batch_release', 'public');
            }

            $inProcessFile = null;
            if ($request->in_process_checks == 'Yes' && $request->hasFile('in_process_checks_file')) {
                $inProcessFile = $request->file('in_process_checks_file')
                    ->store('batch_release', 'public');
            }

            $finishedProductFile = null;
            if ($request->finished_product_report == 'Yes' && $request->hasFile('finished_product_report_file')) {
                $finishedProductFile = $request->file('finished_product_report_file')
                    ->store('batch_release', 'public');
            }



            ProductionFinishedGoodTransfer::create([

                'production_flow_start_id' => $production->id,
                'bom_master_id' => $production->bom_master_id,
                'finished_good_id' => $production->bomMaster?->finished_good_id,

                'finished_goods_qty' => $request->finished_goods_qty,
                'batch_yield' => $request->batch_yield,

                'requisition_sheet_rm' => $request->requisition_sheet_rm,
                'requisition_sheet_rm_file' => $requisitionFile,

                'specimen_carton' => $request->specimen_carton,
                'specimen_carton_file' => $cartonFile,

                'specimen_printed_foil' => $request->specimen_printed_foil,
                'specimen_printed_foil_file' => $foilFile,

                'bulk_testing_report' => $request->bulk_testing_report,
                'bulk_testing_report_file' => $bulkTestingFile,

                'in_process_checks' => $request->in_process_checks,
                'in_process_checks_file' => $inProcessFile,

                'finished_product_report' => $request->finished_product_report,
                'finished_product_report_file' => $finishedProductFile,

                'if_any' => $request->if_any,
                'if_any_file' => $request->if_any_file,

                'analytic_report_no' => $request->analytic_report_no,
                'analytic_report_no_file' => $request->analytic_report_no_file,

                'analytic_report_date' => $request->analytic_report_date,

                'verified_head_production_id' => auth()->id(),
                'verified_head_production_at' => now(),

                'verified_head_qc_id' => auth()->id(),
                'verified_head_qc_at' => now(),

                'verified_head_qa_id' => auth()->id(),
                'verified_head_qa_at' => now(),

                'release_qty' => $request->release_qty,
                'batch_released_by_qa' => $request->batch_released_by_qa,

            ]);


            $finishedQty = $request->finished_goods_qty;

            $productId = $production->bomMaster?->finished_good_id;

            BatchManagement::create([
                'product_id' => $productId,
                'warehouse_id' => 1,
                'unit_cost' => 0,
                'base_price' => 0,
                'gst_percent' => 0,
                'mrp' => 0,
                'batch_number' => $production->batch_number,
                'manufacturing_date' => $production->mfg_date,
                'expiry_date' => $production->expiry_date,
                'available_quantity' => $finishedQty,
                'created_by' => auth()->id(),
            ]);

            $newBatch = BatchManagement::where('batch_number', $production->batch_number)
                ->where('product_id', $productId)
                ->latest()
                ->first();

            \App\Models\FinishedGoodStockLedger::addEntry([
                'date'             => now()->toDateString(),
                'product_id'       => $productId,
                'batch_id'         => $newBatch->id,
                'transaction_type' => 'Production',
                'inward_qty'       => $finishedQty,
                'outward_qty'      => 0,
                'reference_id'     => $production->id,
            ]);

            $finishedGood = FinishedGood::lockForUpdate()->find($productId);

            if (!$finishedGood) {
                throw new \Exception('Finished Good Not Found');
            }

            $finishedGood->total_qty += $finishedQty;
            $finishedGood->save();

            $production->update([
                'stock_in_done' => 1
            ]);
        });

        return back()->with('success', 'Finished Goods Stock Created Successfully');
    }
    public function downloadPackingPdf($id)
    {
        $productionFlowStart = ProductionFlowStart::with('bomMaster.finishedGood')
            ->findOrFail($id);

        $packingDetail = ProductionPackingDetail::where(
            'production_flow_start_id',
            $productionFlowStart->id
        )->first();

        $pdf = Pdf::loadView(
            'admin.production-start.packing_pdf',
            compact('productionFlowStart', 'packingDetail')
        );

        return $pdf->download('packing-details.pdf');
    }
    public function page15Pdf($id)
    {
        $productionFlowStart = ProductionFlowStart::with('page15Logs')->findOrFail($id);
        $page15Logs = $productionFlowStart->page15Logs; // make sure relationship exists

        $pdf = Pdf::loadView('admin.production-start.page15-pdf', compact('productionFlowStart', 'page15Logs'));
        return $pdf->download('Page15_' . $productionFlowStart->batch_number . '.pdf');
    }

    public function page16Pdf($id)
    {
        $productionFlowStart = ProductionFlowStart::with('page16Reconciliations')->findOrFail($id);
        $page16Reconciliations = $productionFlowStart->page16Reconciliations;

        $pdf = Pdf::loadView('admin.production-start.page16-pdf', compact('productionFlowStart', 'page16Reconciliations'));
        return $pdf->download('Page16_' . $productionFlowStart->batch_number . '.pdf');
    }

    public function downloadCompressionPdf($id)
    {
        $production = ProductionFlowStart::with('bomMaster.finishedGood')->findOrFail($id);

        $record = CompressionIpqcRecord::where('production_flow_id', $production->id)->first();

        if (!$record) {
            return back()->with('error', 'No Compression IPQC Data Found');
        }

        // ✅ JSON decode once
        $datetime = json_decode($record->datetime, true) ?? [];
        $weight20 = json_decode($record->weight20, true) ?? [];
        $dt = json_decode($record->dt, true) ?? [];
        $hardness = json_decode($record->hardness, true) ?? [];
        $friability = json_decode($record->friability, true) ?? [];
        $thickness = json_decode($record->thickness, true) ?? [];
        $remarks = json_decode($record->remarks, true) ?? [];

        // ✅ Proper records array banaya
        $records = [];

        for ($i = 0; $i < 10; $i++) {
            $records[] = [
                'datetime' => $datetime[$i] ?? '',
                'weight20' => $weight20[$i] ?? '',
                'dt' => $dt[$i] ?? '',
                'hardness' => $hardness[$i] ?? '',
                'friability' => $friability[$i] ?? '',
                'thickness' => $thickness[$i] ?? '',
                'remarks' => $remarks[$i] ?? '',
                'sign' => $record->inspected_by,
            ];
        }

        // ✅ Final data object
        $data = (object)[
            'product_name' => $record->product_name,
            'batch_number' => $record->batch_no,
            'records' => $records,
            'inspected_by' => $record->inspected_by,
            'total_weight_uncoated' => $record->total_weight_uncoated,
            'total_weight_rejected' => $record->total_weight_rejected,
        ];

        // ✅ Load PDF view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'admin.production-start.compression_ipqc_pdf',
            compact('data')
        );

        return $pdf->download('compression_ipqc_' . $record->batch_no . '.pdf');
    }


    public function downloadCoatingPdf($id)
    {
        $production = ProductionFlowStart::with('bomMaster.finishedGood')->findOrFail($id);

        $record = CoatedTabletProductionForm::where('production_flow_id', $production->id)->first();

        if (!$record) {
            return back()->with('error', 'No Coating Data Found');
        }

        // ✅ JSON decode safely
        $thickness = json_decode($record->thickness, true) ?? [];
        $weight = json_decode($record->weight, true) ?? [];
        $hardness = json_decode($record->hardness, true) ?? [];

        // ✅ Final data object (Blade ke hisab se)
        $data = (object)[
            'product_name' => $record->product_name,
            'batch_number' => $record->batch_no,

            'thickness' => $thickness,
            'weight' => $weight,
            'hardness' => $hardness,

            'average_thickness' => $record->average_thickness,
            'average_weight' => $record->average_weight,
            'average_hardness' => $record->average_hardness,

            'inspection_date' => $record->tablets_inspected_date
                ? date('d-m-Y', strtotime($record->tablets_inspected_date))
                : '',

            'total_weight_coated' => $record->total_weight_coated,
            'total_weight_rejected' => $record->total_weight_rejected,

            'production_sign' => auth()->user()->full_name . ' / ' . date('d-m-Y'),
            'qa_sign' => auth()->user()->full_name . ' / ' . date('d-m-Y'),
        ];

        // ✅ Load PDF view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'admin.production-start.coating_check_pdf',
            compact('data')
        );

        return $pdf->download('coating_ipqc_' . $record->batch_no . '.pdf');
    }

    public function downloadCapsulePdf($id)
    {
        $production = ProductionFlowStart::with('bomMaster.finishedGood')
            ->findOrFail($id);

        $record = CapsuleFilling::where('production_flow_start_id', $production->id)->first();

        if (!$record) {
            return back()->with('error', 'No Capsule Data Found');
        }

        // ✅ JSON decode safely
        $records = $record->capsule_records ?? [];
        // ✅ Final data object (Blade ke hisab se)
        $data = (object)[
            'product_name' => $production->bomMaster->finishedGood->name ?? '',
            'batch_number' => $production->batch_number ?? '',

            'records' => $records,

            'inspected_by' => $record->inspected_by,

            'total_filled_weight' => $record->total_weight_filled_capsules,
            'total_rejected_weight' => $record->total_weight_rejected_capsules,

            'production_sign' => auth()->user()->full_name . ' / ' . date('d-m-Y'),
            'qa_sign' => auth()->user()->full_name . ' / ' . date('d-m-Y'),
        ];

        // ✅ Load PDF view
        $pdf = Pdf::loadView(
            'admin.production-start.capsule_filling_pdf', // 👈 blade path
            compact('data')
        );

        return $pdf->download('capsule_ipqc_' . $production->batch_number . '.pdf');
    }


    public function downloadSyrupPdf($id)
    {
        $production = ProductionFlowStart::with('bomMaster.finishedGood')->findOrFail($id);

        $record = SyrupFillingForm::where('production_flow_start_id', $id)->first();

        if (!$record) {
            return back()->with('error', 'No Syrup Data Found');
        }

        // ✅ SAFE JSON (array ho ya string dono handle karega)
        $datetime = is_array($record->datetime) ? $record->datetime : json_decode($record->datetime, true);
        $filled_volume = is_array($record->filled_volume) ? $record->filled_volume : json_decode($record->filled_volume, true);
        $ropp_cap = is_array($record->ropp_cap) ? $record->ropp_cap : json_decode($record->ropp_cap, true);
        $checked_by = is_array($record->checked_by) ? $record->checked_by : json_decode($record->checked_by, true);
        $verified_by = is_array($record->verified_by) ? $record->verified_by : json_decode($record->verified_by, true);

        $data = (object)[
            'product_name' => $production->bomMaster->finishedGood->name ?? '',
            'batch_number' => $production->batch_number,

            'datetime' => $datetime ?? [],
            'filled_volume' => $filled_volume ?? [],
            'ropp_cap' => $ropp_cap ?? [],
            'checked_by' => $checked_by ?? [],
            'verified_by' => $verified_by ?? [],

            'total_filled_qty' => $record->total_filled_qty,

            'prev_product' => $record->prev_product,
            'prev_batch' => $record->prev_batch,
            'line_clearance_by' => $record->line_clearance_by,

            'inspection_start' => $record->inspection_start,
            'inspection_done_by' => $record->inspection_done_by,
            'inspection_completed' => $record->inspection_completed,
            'inspection_verified' => $record->inspection_verified,
        ];

        $pdf = Pdf::loadView('admin.production-start.syrup_pdf', compact('data'));

        return $pdf->download('syrup_ipqc_' . $production->batch_number . '.pdf');
    }
}
