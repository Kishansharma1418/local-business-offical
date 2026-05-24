<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAsset;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeAssetController extends Controller
{
    // 🔹 Employee Wise Asset List
    public function index($id)
    {
        $employeeId = decrypt($id);
        $employee = Employee::findOrFail($employeeId);

        if (request()->ajax()) {

            $query = EmployeeAsset::where('employee_id', $employeeId);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status_badge', function ($row) {
                    if ($row->status === 'under_maintenance') {
                        return '<span class="badge bg-danger">Under Maintenance</span>';
                    }
                    return '<span class="badge bg-success">' . ucfirst($row->status) . '</span>';
                })
                // ->addColumn('action', function ($row) {
                //     return view('admin.employee.employee-assest.action', compact('row'))->render();
                // })
                ->addColumn('start_date', function ($row) {
                    return \Carbon\Carbon::parse($row->start_date)->format('d-m-Y');
                })

                ->addColumn('end_date', function ($row) {
                    return \Carbon\Carbon::parse($row->end_date)->format('d-m-Y');
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('admin.employee.employee-assest.index', compact('employee'));
    }
    // 🔹 Create Page
    public function create($id)
    {
        $employeeId = decrypt($id);
        $employee = Employee::findOrFail($employeeId);

        return view(
            'admin.employee.employee-assest.create',
            compact('employee')
        );
    }

    // 🔹 Store
    public function store(Request $request, $id)
    {
        $employeeId = decrypt($id);

        $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        EmployeeAsset::create([
            'employee_id' => $employeeId,
            'name' => $request->name,
            'code' => $request->code,
            'asset_type' => $request->asset_type,
            'serial_number' => $request->serial_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'imei_number' => $request->imei_number,
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('employee.assets.index', encrypt($employeeId))
            ->with('success', 'Asset Created Successfully');
    }
    public function show($id)
    {
        $asset = EmployeeAsset::findOrFail($id);

        return view('admin.employee.employee_asset.show', compact('asset'));
    }

    public function assetManagement(Request $request)
    {
        if ($request->ajax()) {

            $query = EmployeeAsset::with('employee')->get();

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('employee', function ($row) {
                    return $row->employee->full_name ?? 'N/A';
                })

                ->addColumn('status', function ($row) {

                    if ($row->status === 'under_maintenance') {
                        return '<span class="badge bg-danger">Under Maintenance</span>';
                    }

                    return '<span class="badge bg-success">' . ucfirst($row->status) . '</span>';
                })

                ->rawColumns(['status'])
                ->make(true);
        }

        return view('admin.asset-management.index');
    }
}
