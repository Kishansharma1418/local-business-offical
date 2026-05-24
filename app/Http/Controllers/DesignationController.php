<?php

namespace App\Http\Controllers;

use App\Models\{Designation, Department};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class DesignationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $designation = Designation::query()->orderBy('id', 'DESC');
            if ($request->name) {
                $designation->where('name', 'LIKE', '%' . $request->name . '%');
            }
            return DataTables::of($designation)
                ->addColumn('action', function ($row) {
                    $type = 'action';
                    return view('admin.designation.action', compact('row', 'type'))->render();
                })
                ->addColumn('department', function ($row) {
                    return $row->departments?->department_name;
                })
                ->addColumn('employee_count', function ($row) {
                    return $row->employees()->count();
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $departments = Department::where('status', 'Active')->get();
        $designation = Designation::select('name')->groupBy('name')->get();
        return view('admin.designation.index', compact('departments', 'designation'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $designation = new Designation;
            $designation->name = $request->name;
            $designation->code = $request->code;
            $designation->department_id = $request->department_id;
            $designation->description = $request->description;
            $designation->status = (string) $request->status;
            $designation->created_by = auth()->user()->id;
            $designation->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Designation created successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $designation = Designation::findOrFail($id);
        $departments = Department::where('status', 'Active')->get();
        return view('admin.designation.edit', compact('designation', 'departments'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $designation = Designation::findOrFail($id);
            $designation->name = $request->name;
            $designation->code = $request->code;
            $designation->description = $request->description;
            $designation->department_id = $request->department_id;

            $designation->updated_by = auth()->user()->id;
            $designation->status = $request->status;
            $designation->save();

            DB::commit();
            $response['status'] = true;
            $response['message'] = 'Designation Updated successfully';
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(Designation $designation)
    {
        try {
            $designation->delete();
            return response()->json(['success' => true, 'message' => 'Designation deleted successfully!']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
}
