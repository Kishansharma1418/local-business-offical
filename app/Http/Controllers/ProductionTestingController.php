<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    ProductionFlowStart,
    ProductionProcess,
    QualtyCheck
};
use Yajra\DataTables\Facades\DataTables;

class ProductionTestingController extends Controller
{

   public function index(Request $request)
{
    if ($request->ajax()) {

        $data = ProductionFlowStart::with('bomMaster.finishedGood')
            ->whereIn('status', ['IN_PROGRESS', 'completed'])
            ->orderBy('id', 'desc')
            ->get()
            ->filter(function ($row) {

                // ✅ QC approved = hamesha dikhao (completed wala)
                $qcApproved = QualtyCheck::where('production_flow_start_id', $row->id)
                    ->where('status', 'approved')
                    ->exists();

                if ($qcApproved) {
                    return true;
                }

                // ✅ QC nahi hua — sirf tab dikhao jab current step Quality Check ho
                $step = ProductionProcess::with('bomType')
                    ->where('bom_master_id', $row->bom_master_id)
                    ->skip($row->current_step - 1)
                    ->first();

                return $step && $step->bomType && $step->bomType->name == 'Quality Check';
            });


        return DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('finished_good', function ($row) {
                return $row->bomMaster->bom_number
                    . ' - ' . $row->bomMaster->bom_version
                    . ' - ' . ($row->bomMaster->finishedGood->name ?? '');
            })

            ->addColumn('batch_number', function ($row) {
                return $row->batch_number;
            })

            ->addColumn('mfg_date', function ($row) {
                return $row->mfg_date
                    ? \Carbon\Carbon::parse($row->mfg_date)->format('d-m-Y')
                    : '-';
            })

            // ✅ QC approved = Completed, warna In Progress
            ->addColumn('status', function ($row) {

                $qcApproved = QualtyCheck::where('production_flow_start_id', $row->id)
                    ->where('status', 'approved')
                    ->exists();

                if ($qcApproved) {
                    return '<span class="badge bg-success">Completed</span>';
                }

                return '<span class="badge bg-warning text-dark">In Progress</span>';
            })

            ->addColumn('current_step', function ($row) {
                return '<span class="badge bg-warning text-dark">QC Testing</span>';
            })

            ->addColumn('action', function ($row) {
                return '<a href="' . route('production-testing.show', $row->id) . '" title="View">
                    <i class="ri-eye-line" style="font-size:18px;"></i>
                </a>';
            })

            ->rawColumns(['current_step', 'action', 'status'])
            ->make(true);
    }

    return view('admin.production-testing.index');
}
    public function show($id)
    {

        $production = ProductionFlowStart::with([
            'bomMaster',
            'bomMaster.finishedGood'
        ])->findOrFail($id);

        $qcReports = QualtyCheck::where('production_flow_start_id', $id)
            ->orderBy('id', 'desc')
            ->get();
        $processes = ProductionProcess::with('bomType')
            ->where('bom_master_id', $production->bom_master_id)
            ->orderBy('id', 'asc')
            ->get();
        return view(
            'admin.production-testing.show',
            compact('production', 'qcReports', 'processes')
        );
    }


    public function store(Request $request)
    {

        $request->validate([
            'qc_report' => 'required|mimes:pdf|max:2048',
            'production_id' => 'required',
            'step' => 'required'
        ]);

        $filePath = $request->file('qc_report')
            ->store('qc_reports', 'public');

        QualtyCheck::create([

            'production_flow_start_id' => $request->production_id,
            'step_number' => $request->step,
            'report_path' => $filePath,
            'checked_by' => auth()->id(),

        ]);

        return back()->with('success', 'QC Report Uploaded');
    }


    public function update(Request $request, $id)
    {

        $request->validate([

            'qc_id' => 'required',
            'status' => 'required|in:approved,rejected',
            'remarks' => 'required'

        ]);

        $qc = QualtyCheck::findOrFail($request->qc_id);

        $qc->update([

            'status' => $request->status,
            'remarks' => $request->remarks

        ]);

        $production = ProductionFlowStart::findOrFail($id);

        if ($request->status == 'approved') {

            $production->increment('current_step');
        }

        return redirect()
            ->route('production-testing.show', $production->id)
            ->with('success', 'QC Status Updated');
    }
}
