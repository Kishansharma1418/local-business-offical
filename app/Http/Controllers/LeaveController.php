<?php

namespace App\Http\Controllers;

use App\Models\{Leave,Employee};
use App\Models\EmployeeAssets;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
          $leaves = Leave::with('employee')->orderBy('id', 'DESC');

            if (!auth()->user()->hasRole('admin')) {
                $leaves->where('employee_id', auth()->user()->reference_id);
            }
            
            return DataTables::of($leaves)
            ->filterColumn('full_name', function ($query, $keyword) {
        $query->whereHas('employee', function ($q) use ($keyword) {
            $q->where('full_name', 'like', "%{$keyword}%");
        });
    })
                ->addColumn('action', function ($row) {
                    return view('admin.employee-leave.action', compact('row'))->render();
                })
                   ->addColumn('full_name', function ($row) {
        return $row->employee->full_name ?? '-';
    })
                ->editColumn('status', function ($row) {
                    $statusClass = match ($row->status) {
                        'Verified' => 'bg-success',
                        'Rejected' => 'bg-danger',
                        'Expired' => 'bg-secondary',
                        default => 'bg-warning',
                    };

                    return '<span class="badge ' . $statusClass . '">' . $row->status . '</span>';
                })
                ->editColumn('start_date', fn($row) => formatDate($row->start_date))
                ->editColumn('end_date', fn($row) =>formatDate($row->end_date))
                ->editColumn('total_days', fn($row) => (int) $row->total_days)
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $employee = Employee::select('id','full_name')->orderBy('id', 'DESC')->get();

        return view('admin.employee-leave.index', compact('employee'));
    }

  public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'leave_type'  => 'required|in:half day,full day',
        'date_range'  => 'required|string',
        'description' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    DB::beginTransaction();

    try {

        [$start_date, $end_date] = explode(' - ', $request->date_range);

        $start_date = Carbon::parse(trim($start_date))->format('Y-m-d');
        $end_date   = Carbon::parse(trim($end_date))->format('Y-m-d');

        $employee_id = auth()->user()->reference_id ?? $request->employee_id;

        $leave = new Leave();
        $leave->employee_id = $employee_id;
        $leave->leave_category = $request->leave_category;
        $leave->leave_type = $request->leave_type;
        $leave->start_date = $start_date;
        $leave->end_date = $end_date;
        $leave->description = $request->description;
        $leave->status = 'Pending';
        $leave->total_days = Carbon::parse($start_date)->diffInDays(Carbon::parse($end_date)) + 1;
        $leave->created_by = auth()->id();
        $leave->save();

        /*
        ==================================
        EXPENSE TABLE AUTO ENTRY FOR LEAVE
        ==================================
        */

        $start = Carbon::parse($start_date);
        $end   = Carbon::parse($end_date);

        for ($date = $start; $date->lte($end); $date->addDay()) {

            \App\Models\EmployeeAssets::updateOrCreate(
                [
                    'employee_id' => $employee_id,
                    'start_date'  => $date->format('Y-m-d')
                ],
                [
                    'end_date'     => $date->format('Y-m-d'),
                    'type'         => 'leave',
                    'amount'       => 0,
                    'total_amount' => 0,
                    'status'       => 'Submited',
                    'created_by'   => auth()->id()
                ]
            );

        }

        DB::commit();

        return response()->json([
            'status'  => true,
            'message' => 'Leave created successfully!'
        ]);

    } catch (Exception $e) {

        DB::rollBack();

        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}
    public function edit($id)
    {
        $leave = Leave::findOrFail($id);
        $employee = Employee::select('id','full_name')->orderBy('id', 'DESC')->get();
        return view('admin.employee-leave.edit', compact('leave', 'employee'));
    }

  public function update(Request $request, $id)
{
    // 🔹 Validation rules
    $rules = [
     
        'leave_type'     => 'required|in:half day,full day',
        'date_range'     => 'required|string',
        'description'    => 'nullable|string',
    ];

    // 🔹 Admin ke liye employee required
    if (auth()->user()->hasRole('admin')) {
        $rules['employee_id'] = 'required|exists:employees,id';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    DB::beginTransaction();

    try {
        // 🔹 Date range split & convert
        [$start_date, $end_date] = explode(' - ', $request->date_range);

        $start_date = Carbon::createFromFormat('d/m/Y', trim($start_date))
            ->format('Y-m-d');

        $end_date = Carbon::createFromFormat('d/m/Y', trim($end_date))
            ->format('Y-m-d');

        // 🔹 Find leave
        $leave = Leave::findOrFail($id);

        // 🔹 Employee assignment (FIXED)
        if (auth()->user()->hasRole('admin')) {
            $leave->employee_id = $request->employee_id;   // ✅ dropdown value
        } else {
            $leave->employee_id = auth()->user()->reference_id; // ✅ self
        }

        // 🔹 Update fields
        $leave->leave_category = $request->leave_category;
        $leave->leave_type     = $request->leave_type;
        $leave->start_date     = $start_date;
        $leave->end_date       = $end_date;
        $leave->description    = $request->description;
        $leave->updated_by     = auth()->id();

        // 🔹 Total days calculation
        $leave->total_days = Carbon::parse($start_date)
            ->diffInDays(Carbon::parse($end_date)) + 1;

        $leave->save();

        DB::commit();

        return response()->json([
            'status'  => true,
            'message' => 'Leave updated successfully!'
        ]);

    } catch (Exception $e) {
        DB::rollBack();

        return response()->json([
            'status'  => false,
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}



    public function show($id)
    {
        $id = decrypt($id);
        $leave = Leave::with(['creator', 'updater'])->findOrFail($id);
        return view('admin.employee-leave.show', compact('leave'));
    }

    public function updateStatus(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->status = $request->status;
        $leave->reason = $request->reason;
        $leave->updated_by = auth()->id();
        $leave->save();

        return redirect()->route('leaves.show', encrypt($id))
            ->with('success', 'Leave status updated successfully!');
    }

    public function destroy(Leave $leave)
    {
        try {
            $leave->delete();
            return response()->json([
                'success' => true,
                'message' => 'Leave deleted successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approveAll()
    {
        
        try {
            Leave::where('status', 'Pending')->update(['status' => 'Verified']);

            return response()->json([
                'success' => true,
                'message' => 'All pending leaves approved successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }


}