<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeOvertime;
use App\Models\Employee;
use DataTables;


class EmployeeOvertimeController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = EmployeeOvertime::with('employee');

            return DataTables::of($query)

                ->addIndexColumn()

                ->addColumn('employee', function ($row) {
                    return $row->employee->full_name ?? 'N/A';
                })
                ->addColumn('date', fn($row) => formatDate($row->date))

                ->addColumn('created_at', fn($row) => formatDate($row->created_at))

                ->addColumn('action', function ($row) {
                    $type = "action";
                    return view('admin.overtime.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action', 'created_at'])

                ->make(true);
        }

        return view('admin.overtime.index');
    }



    public function create()
    {

        $employees = Employee::get();

        return view('admin.overtime.create', compact('employees'));
    }



    public function store(Request $request)
    {

        $request->validate([

            'employee_id' => 'required',
            'date' => 'required'

        ]);

        EmployeeOvertime::create([

            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'hours' => $request->hours,
            'rate_per_hour' => $request->rate_per_hour,
            'total_amount' => $request->total_amount,
            'remark' => $request->remark,
            'created_by' => auth()->id()

        ]);

        return redirect()->route('overtime.index')
            ->with('success', 'Overtime Added Successfully');
    }



    public function edit($id)
    {
        $id = decrypt($id);
        $overtime = EmployeeOvertime::findOrFail($id);

        $employees = Employee::get();

        return view('admin.overtime.edit', compact('overtime', 'employees'));
    }



    public function update(Request $request, $id)
    {

        $overtime = EmployeeOvertime::findOrFail($id);

        $overtime->update([

            'employee_id' => $request->employee_id,
            'date' => $request->date,
            'hours' => $request->hours,
            'rate_per_hour' => $request->rate_per_hour,
            'total_amount' => $request->total_amount,
            'remark' => $request->remark,
            'updated_by' => auth()->id()

        ]);

        return redirect()->route('overtime.index')
            ->with('success', 'Overtime Updated Successfully');
    }
    public function show($id)
    {
        $id = decrypt($id);
        $overtime = EmployeeOvertime::with('employee')->findOrFail($id);

        return view('admin.overtime.show', compact('overtime'));
    }
}
