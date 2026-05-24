<?php

namespace App\Http\Controllers;

use App\Models\LastMonthAdjustment;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Employee;


class LastMonthAdjustmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $adjustments = LastMonthAdjustment::with('employee')
                ->when($request->employee_id, function ($q) use ($request) {
                    $q->where('employee_id', $request->employee_id);
                })
                ->when($request->month, function ($q) use ($request) {

                    $q->where('adjustment_month', 'like', $request->month . '%');
                })
                ->latest();


            return DataTables::of($adjustments)

                ->addIndexColumn()
                ->filterColumn('employee', function ($query, $keyword) {
                    $query->whereHas('employee', function ($q) use ($keyword) {
                        $q->where('full_name', 'like', "%{$keyword}%")
                            ->orWhere('role', 'like', "%{$keyword}%")
                            ->orWhere('code', 'like', "%{$keyword}%")
                            ->orWhereHas('branches', function ($branchQuery) use ($keyword) {
                                $branchQuery->where('branch_name', 'like', "%{$keyword}%");
                            })
                            ->orWhere('mobile_no', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('employee', function ($row) {

                    $user  = '<div class="d-flex align-items-center " style="gap:15px;">';

                    $user .= '   <div class="flex-grow-1">';
                    $user .= '       <h6 class="mb-1" style="font-weight:600;color:#333;">' . ($row->employee->full_name ?? 'N/A') . '</h6>';
                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Type:</strong> ' . ($row->employee->role ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Employee Code:</strong> ' . ($row->employee->code ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Branch:</strong> ' . ($row->employee->branches->branch_name ?? '-') . '</p>';
                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Phone:</strong> ' . ($row->employee->mobile_no ?? '-') . '</p>';

                    $user .= '   </div>';
                    $user .= '</div>';

                    return $user;
                })

                ->addColumn('status', function ($row) {
                    $badge = $row->status == 'Credit' ? 'success' : 'danger';
                    return "<span class='badge bg-$badge'>{$row->status}</span>";
                })
                ->editColumn('created_at', fn($row) => formatDate($row->created_at))

                ->addColumn('action', function ($row) {
                    return view('admin.lastmonthadjustment.action', compact('row'))->render();
                })

                ->rawColumns(['status', 'action', 'employee'])
                ->make(true);
        }
        $employee = Employee::whereIn('id', LastMonthAdjustment::pluck('employee_id'))->get();
        return view('admin.lastmonthadjustment.index', compact('employee'));
    }


    public function create()
    {
        $employees = Employee::select('id', 'full_name')->orderBy('id', 'DESC')->get();
        return view('admin.lastmonthadjustment.create', compact('employees'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'employee_id' => 'required|integer',
            'adjustment_month' => 'required|string',
            'current_month' => 'required|string',
            'adjustment_amount' => 'required|numeric',
            'description' => 'nullable|string',
            'status' => 'required|in:Debit,Credit',
        ]);


        LastMonthAdjustment::create([
            'employee_id' => $request->employee_id,
            'adjustment_date' => now(),
            'adjustment_month' => $request->adjustment_month,
            'current_month' => $request->current_month,
            'adjustment_amount' => $request->adjustment_amount,
            'description' => $request->description,
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('last-adjustments.index')
            ->with('success', 'Record created successfully.');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $adjustment = LastMonthAdjustment::findOrFail($id);
        $employees = Employee::orderBy('id', 'DESC')->get();
        return view('admin.lastmonthadjustment.edit', compact('adjustment', 'employees'));
    }

    public function update(Request $request, $id)
    {

        $adjustment = LastMonthAdjustment::findOrFail($id);

        $request->validate([
            'employee_id' => 'required|integer',
            'adjustment_month' => 'required|string',
            'current_month' => 'required|string',
            'adjustment_amount' => 'required|numeric',
            'description' => 'nullable|string',
            'status' => 'required|in:Debit,Credit',
        ]);

        $adjustment->update([
            'employee_id' => $request->employee_id,
            'adjustment_month' => $request->adjustment_month,
            'current_month' => $request->current_month,
            'adjustment_amount' => $request->adjustment_amount,
            'description' => $request->description,
            'status' => $request->status,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('last-adjustments.index')
            ->with('success', 'Record updated successfully.');
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        LastMonthAdjustment::findOrFail($id)->delete();

        return redirect()->route('last-adjustments.index')
            ->with('success', 'Record deleted successfully.');
    }
}
