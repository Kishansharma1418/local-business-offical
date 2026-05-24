<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeTds;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EmployeeTdsController extends Controller
{

    // INDEX PAGE
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $query = EmployeeTds::with('employee');

            return DataTables::of($query)

                ->addIndexColumn()

                ->addColumn('employee', function ($row) {
                    return $row->employee->full_name ?? 'N/A';
                })

                ->addColumn('financial_year', function ($row) {
                    return $row->financial_year;
                })

                ->addColumn('month', function ($row) {
                    return $row->month;
                })

                ->addColumn('tds_amount', function ($row) {
                    return number_format($row->tds_amount,2);
                })

                 ->addColumn('action', function ($row) {
                    $type = "action";
                    return view('admin.tds.action', compact('row', 'type'))->render();
                })

                ->rawColumns(['action'])

                ->make(true);
        }

        return view('admin.tds.index');
    }


    // CREATE PAGE
    public function create()
    {

        $employees = Employee::select('id','full_name')->get();

        return view('admin.tds.create',compact('employees'));
    }


    // STORE DATA
    public function store(Request $request)
    {

        $request->validate([

            'employee_id'=>'required',
            'financial_year'=>'required',
            'month'=>'required',
            'tds_amount'=>'required'

        ]);

        EmployeeTds::create([

            'employee_id'=>$request->employee_id,

            'financial_year'=>$request->financial_year,

            'month'=>$request->month,

            'gross_salary'=>$request->gross_salary,

            'taxable_salary'=>$request->taxable_salary,

            'tds_percent'=>$request->tds_percent,

            'tds_amount'=>$request->tds_amount,

            'remark'=>$request->remark,

            'created_by'=>auth()->id()

        ]);

        return redirect()->route('tds.index')
        ->with('success','TDS Added Successfully');
    }


    // EDIT PAGE
    public function edit($id)
    {
  $id = decrypt($id);
        $tds = EmployeeTds::findOrFail($id);

        $employees = Employee::select('id','full_name')->get();

        return view('admin.tds.edit',compact('tds','employees'));
    }


    // UPDATE DATA
    public function update(Request $request,$id)
    {

        $tds = EmployeeTds::findOrFail($id);

        $tds->update([

            'employee_id'=>$request->employee_id,

            'financial_year'=>$request->financial_year,

            'month'=>$request->month,

            'gross_salary'=>$request->gross_salary,

            'taxable_salary'=>$request->taxable_salary,

            'tds_percent'=>$request->tds_percent,

            'tds_amount'=>$request->tds_amount,

            'remark'=>$request->remark,

            'updated_by'=>auth()->id()

        ]);

        return redirect()->route('tds.index')
        ->with('success','TDS Updated Successfully');

    }


    // DELETE
    public function destroy($id)
    {

        $tds = EmployeeTds::findOrFail($id);

        $tds->delete();

        return response()->json([
            'status'=>true,
            'message'=>'Deleted Successfully'
        ]);

    }
    function show($id)
    {
        $id = decrypt($id);
        $tds = EmployeeTds::with('employee')->findOrFail($id);

        return view('admin.tds.show',compact('tds'));
    }
}