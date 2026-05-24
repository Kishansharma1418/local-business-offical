<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::orderBy('name', 'ASC')->get();

        if ($request->ajax()) {
            $branches = Branch::query()->orderBy('id', 'DESC');

            if ($request->branch_name) {
                $branches->where('branch_name', 'LIKE', '%' . $request->branch_name . '%');
            }

            return datatables()
                ->of($branches)
                ->addColumn('action', function ($row) {
                    return view('admin.branches.action', compact('row'))->render();
                })
                ->editColumn('user', function ($row) {
                    return $row->users?->full_name;
                })
                ->addColumn('employee_count', function ($row) {
                    return $row->employees()->count();
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 'Active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->rawColumns(['action', 'status', 'user', 'employee_count'])
                ->make(true);
        }
        $branch = Branch::select('branch_name')->groupBy('branch_name')->get();
        return view('admin.branches.index', compact('countries', 'branch'));
    }

    public function create()
    {
        $countries = Country::orderBy('name', 'ASC')->get();
        return view('admin.branches.create', compact('countries'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:branches,code',
                'branch_name' => 'required|string|max:200',
                'branch_type' => 'required|in:Head Office,Regional Office,Warehouse,Factory,Export Division',
                'address_line1' => 'required|string|max:200',
                'address_line2' => 'nullable|string|max:200',
                'city_id' => 'required|integer|exists:cities,id',
                'state_id' => 'required|integer|exists:states,id',
                'country_id' => 'required|integer|exists:countries,id',
                'pin_code' => 'required|regex:/^[0-9]{4,10}$/',
                'phone' => 'nullable|string|max:20',
                'mobile' => 'required|regex:/^[0-9+\-\s]{7,20}$/|max:12',
                'email' => 'nullable|email|max:100',
                'gst_number' => 'nullable|string|regex:/^[0-9A-Z]{15}$/',
                'pan_number' => 'nullable|string|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                'policy_no' => 'nullable|string|max:50',
                'dl_no' => 'nullable|string|max:50',
                'cbn_no' => 'nullable|string|max:50',
                // 'manager_employee_id' => 'nullable|integer|exists:employees,id',
                // 'parent_branch_id' => 'nullable|integer|exists:branches,branchID',
                // 'currency_id' => 'nullable|integer|exists:currencies,id',
                'status' => 'required|in:Active,Inactive',
                'notes' => 'nullable|string|max:500',
            ]);
            $validated['created_by'] = auth()->user()->id;
            $branch = Branch::create($validated);

            return redirect()->route('branches.index')->with('success', 'Branch created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $id = decrypt($id);
            $branch = Branch::findOrFail($id);
            $countries = Country::orderBy('name', 'ASC')->get();
            $states = State::where('country_id', $branch->country_id)->orderBy('name')->get();
            $cities = City::where('state_id', $branch->state_id)->orderBy('name')->get();

            return view('admin.branches.edit', compact('branch', 'countries', 'states', 'cities'));
        } catch (Exception $e) {
            return redirect()->route('branches.index')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $branch = Branch::findOrFail($id);

            $validated = $request->validate([
                'code' => 'required|string|max:20|unique:branches,code,' . $branch->id,
                'branch_name' => 'required|string|max:200',
                'branch_type' => 'required|in:Head Office,Regional Office,Warehouse,Factory,Export Division',
                'address_line1' => 'required|string|max:200',
                'address_line2' => 'nullable|string|max:200',
                'city_id' => 'required|integer|exists:cities,id',
                'state_id' => 'required|integer|exists:states,id',
                'country_id' => 'required|integer|exists:countries,id',
                'pin_code' => 'nullable|regex:/^[0-9]{4,10}$/',
                'phone' => 'nullable|string|max:20',
                'mobile' => 'required|regex:/^[0-9+\-\s]{7,20}$/',
                'email' => 'nullable|email|max:100',
                'gst_number' => 'nullable|string|regex:/^[0-9A-Z]{15}$/',
                'pan_number' => 'nullable|string|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                'policy_no' => 'nullable|string|max:50',
                'dl_no' => 'nullable|string|max:50',
                'cbn_no' => 'nullable|string|max:50',
                'manager_employee_id' => 'nullable|integer|exists:employees,id',
                'parent_branch_id' => 'nullable|integer|exists:branches,branchID',
                'currency_id' => 'nullable|integer|exists:currencies,id',
                'status' => 'required|in:Active,Inactive',
                'notes' => 'nullable|string|max:500',
            ]);

            $validated['updated_by'] = auth()->id();
            $branch->update($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Branch updated successfully',
                    'branch' => $branch
                ]);
            }

            return redirect()->route('branches.index')->with('success', 'Branch updated successfully');
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $id = decrypt($id);
        $branch = Branch::with(['country', 'state', 'city', 'createdBy', 'updatedBy'])->findOrFail($id);
        return view('admin.branches.show', compact('branch'));
    }

    public function destroy($id)
    {
        try {
            $branch = Branch::findOrFail($id);
            $branch->delete();

            return response()->json(['success' => true, 'message' => 'Branch deleted successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
