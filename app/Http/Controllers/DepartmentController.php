<?php

namespace App\Http\Controllers;

use App\Models\{Department, Branch};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $departments = Department::query()->orderBy('id', 'DESC');

            if ($request->department_name) {
                $departments->where('department_name', 'LIKE', '%' . $request->department_name . '%');
            }
            return Datatables::of($departments)
                ->addColumn('action', function ($row) {
                    return view('admin.departments.action', compact('row'))->render();
                })
                ->addColumn('branch', function ($row) {
                    return $row->branch?->branch_name;
                })
                ->addColumn('employee_count', function ($row) {
                    return $row->employees()->count();
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 'Active'
                        ? '<span class="badge bg-success px-2 py-1">Active</span>'
                        : '<span class="badge bg-danger px-2 py-1">Inactive</span>';
                })
                ->rawColumns(['action', 'status', 'branch', 'employee_count'])
                ->make(true);
        }
        $departments = Department::select('department_name')->groupBy('department_name')->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        $branches = Branch::select('id', 'branch_name')->where('status', 'Active')->get();
        // $department = Department::select('id','department_name')->where('status','Active')->get();
        return view('admin.departments.create', compact('branches'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|unique:departments,code|min:3',
                'department_name' => 'required|string|max:100',
            ]);

            $department = Department::create([
                'code' => $request->code,
                'department_name' => $request->department_name,
                'parent_department_id' => $request->parent_department_id,
                'branch_id' => $request->branch_id,
                'department_head_id' => $request->department_head_id,
                'email' => $request->email,
                'phone' => $request->phone,
                'description' => $request->description,
                'status' => $request->status,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('departments.index')->with('success', 'Department created successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $id = decrypt($id);
            $department = Department::findOrFail($id);

            return view('admin.departments.edit', compact('department'));
        } catch (Exception $e) {
            return redirect()->route('departments.index')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $department = Department::findOrFail($id);
            $request->validate([
                'code' => 'required|min:3|unique:departments,code,' . $department->id,
                'department_name' => 'required|string|max:100',
            ]);

            $department->update([
                'code' => $request->code,
                'department_name' => $request->department_name,
                'parent_department_id' => $request->parent_department_id,
                'branch_id' => $request->branch_id,
                'department_head_id' => $request->department_head_id,
                'email' => $request->email,
                'phone' => $request->phone,
                'description' => $request->description,
                'status' => $request->status,
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $id = decrypt($id);
        $department = Department::with(['parent', 'branch', 'head', 'createdBy', 'updatedBy'])->findOrFail($id);
        return view('admin.departments.show', compact('department'));
    }

    public function destroy($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->delete();

            return response()->json(['success' => true, 'message' => 'Department deleted successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
