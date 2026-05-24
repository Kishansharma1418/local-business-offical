<?php

namespace App\Http\Controllers;

use App\Models\{EmployeAttandance, Employee};
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeAttandanceImport;
use App\Exports\EmployeeAttendanceSampleExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use DB;

class EmployeAttandanceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $month = $request->month; 

            $query = EmployeAttandance::select(
                    'employee_id',
                    DB::raw('MAX(name) as name'),
                    DB::raw('COUNT(CASE WHEN status = "Present" THEN 1 END) as present_count'),
                    DB::raw('COUNT(CASE WHEN status = "Leave" THEN 1 END) as absent_count'),
                    DB::raw('COUNT(CASE WHEN status = "Weekly Off" THEN 1 END) as weekly_off_count'),
                    DB::raw('COUNT(CASE WHEN status = "Half Day" THEN 1 END) as half_day_count'),
                    DB::raw('COUNT(CASE WHEN status = "Holiday" THEN 1 END) as holiday_count'),
                    DB::raw('MONTH(date) as month')
                )->where('employee_id',auth()->user()->reference_id);

            if ($month) {
                $query->whereMonth('date', $month);
            }

            $query->groupBy('employee_id', 'month')
                ->orderBy('employee_id', 'DESC');
            if ($request->employee_id) {
                $query->where('employee_id', $request->employee_id);
            }
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('admin.employee-attandance.action', compact('row'))->render();
                })
                ->addColumn('name', fn($row) => $row->name ?? '-')
                ->addColumn('present', fn($row) => $row->present_count)
                ->addColumn('leave', fn($row) => $row->absent_count)
                ->addColumn('weekly_off', fn($row) => $row->weekly_off_count)
                ->addColumn('half_day', fn($row) => $row->half_day_count)
                ->addColumn('holiday', fn($row) => $row->holiday_count)
                ->addColumn('month', fn($row) => date('F-Y', mktime(0, 0, 0, $row->month, 1)))
                ->rawColumns(['action'])
                ->make(true);
        }
        $employee = Employee::select('id','full_name')->where('id',auth()->user()->reference_id)->orderBy('id', 'DESC')->get();
       
        return view('admin.employee-attandance.index',compact('employee'));
    }


    public function edit($employee_attandance, Request $request)
    {
        $employeeId = decrypt($employee_attandance);
        $month = $request->month ?? now()->month;

        $employee = DB::table('employe_attandances')
            ->select('employee_id', DB::raw('MAX(name) as name'))
            ->where('employee_id', $employeeId)
            ->groupBy('employee_id')
            ->first();

        $summary = DB::table('employe_attandances')
            ->select(
                DB::raw('COUNT(CASE WHEN status = "Present" THEN 1 END) as present'),
                DB::raw('COUNT(CASE WHEN status = "Leave" THEN 1 END) as `leave`'),
                DB::raw('COUNT(CASE WHEN status = "Weekly Off" THEN 1 END) as weekly_off'),
                DB::raw('COUNT(CASE WHEN status = "Half Day" THEN 1 END) as half_day'),
                DB::raw('COUNT(CASE WHEN status = "Holiday" THEN 1 END) as holiday'),
                DB::raw('COUNT(*) as total')
            )
            ->where('employee_id', $employeeId)
            ->whereMonth('date', $month)
            ->first();

        $records = EmployeAttandance::where('employee_id', $employeeId)
            ->whereMonth('date', $month)
            ->orderBy('date', 'ASC')
            ->get();

        $employees = Employee::all();

        return view('admin.employee-attandance.edit', compact('employee', 'summary', 'records', 'employees', 'month'));
    }

    public function update(Request $request, $id)
    {
        $employeeId = decrypt($id);

        if ($request->has('attendance')) {
            foreach ($request->attendance as $recordId => $data) {
                EmployeAttandance::where('id', $recordId)->update([
                    'status' => $data['status'] ?? null,
                    'check_in' => $data['check_in'] ?? null,
                    'check_out' => $data['check_out'] ?? null,
                ]);
            }
        }

        return redirect()->route('employee-attandance.index')
            ->with('success', 'Attendance updated successfully!');
    }

    public function show($employee_attandance, Request $request)
    {
        $employeeId = decrypt($employee_attandance);
        $month = $request->month ?? now()->month;

       $employee = DB::table('employe_attandances')
            ->select('employee_id', DB::raw('MAX(name) as name'))
            ->where('employee_id', $employeeId)
            ->groupBy('employee_id')
            ->first();

        $summary = DB::table('employe_attandances')
            ->select(
                DB::raw('COUNT(CASE WHEN status = "Present" THEN 1 END) as present'),
                DB::raw('COUNT(CASE WHEN status = "Leave" THEN 1 END) as `leave`'),
                DB::raw('COUNT(CASE WHEN status = "Weekly Off" THEN 1 END) as weekly_off'),
                DB::raw('COUNT(CASE WHEN status = "Half Day" THEN 1 END) as half_day'),
                DB::raw('COUNT(CASE WHEN status = "Holiday" THEN 1 END) as holiday'),
                DB::raw('COUNT(*) as total')
            )
            ->where('employee_id', $employeeId)
            ->whereMonth('date', $month)
            ->first();

        $records = DB::table('employe_attandances')
            ->where('employee_id', $employeeId)
            ->whereMonth('date', $month)
            ->orderBy('date', 'ASC')
            ->get();

        return view('admin.employee-attandance.show', compact('employee', 'summary', 'records', 'month'));
    }

    public function import(Request $request)
    {
        
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new EmployeAttandanceImport, $request->file('file'));
            return back()->with('success', 'Attendance imported successfully!');
        } catch (\Exception $e) {
            \Log::error('Import failed: ' . $e->getMessage());
            return back()->with('error', 'Import failed. Please check log file.');
        }
    }

    public function exportSample(Request $request)
    {
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        return Excel::download(
            new EmployeeAttendanceSampleExport($month, $year),
            "attendance_sample_{$month}_{$year}.xlsx"
        );
    }
}
