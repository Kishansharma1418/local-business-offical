<?php

namespace App\Http\Controllers;

use App\Models\{EmployeHoliday,Branch,EmployeHolidayBranch};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Yajra\DataTables\DataTables;


class EmployeHolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = EmployeHoliday::query()->orderBy('id','DESC');
            if ($request->branch_id) {
                $query->whereHas('branches', function ($q) use ($request) {
                    $q->where('branch_id', $request->branch_id);
                });
            }
            if ($request->year) {
                $query->whereYear('start_date', $request->year);
            }

            return Datatables::of($query)
                ->addIndexColumn()
               
               ->addColumn('created_at', function($row) {
                    return formatDate($row->created_at);
                })

                 ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                    })
                     ->addColumn('start_date', function ($row) {
                     return $row->start_date
                        ? \Carbon\Carbon::parse($row->start_date)->format('d M, Y')
                        : '-';
                })

                ->addColumn('start_date', function ($row) {
                    return formatDate($row->start_date);
                })

                ->addColumn('branch', function ($row) {
                    return $row->branches
                        ->pluck('branch.branch_name')  
                        ->filter()                     
                        ->join(', ');                
                })
                
                ->addColumn('action', function ($row) {
                    $type = "action";
                    return view('admin.employee-holiday.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action','created_at','status','branch'])
                ->make(true);
        }
        $branches = Branch::whereIn('id', EmployeHolidayBranch::pluck('branch_id'))->get();
        return view('admin.employee-holiday.index',compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::select('id','branch_name')->where('status','Active')->get();

        return view('admin.employee-holiday.create',compact('branches'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
   

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                // 'emoloye_name'=>auth()->id(),
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                
                'status' => 'required|in:0,1',
                'branch_id' => 'required|array|min:1',
                'branch_id.*' => 'exists:branches,id',
                'start_date' => 'required|date|after_or_equal:today',

            ]);
            $start_date = $request->start_date;
            $end_date = null;

            $holiday = EmployeHoliday::create([
                'title' => $validated['title'],
                'start_date' => $start_date,
                'end_date' => $end_date,
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['branch_id'] as $branchId) {
                EmployeHolidayBranch::create([
                    'employe_holiday_id' => $holiday->id,
                    'branch_id' => $branchId,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('employee-holiday.index')
                ->with('success', 'Employee holiday added successfully!');

        } catch (\Throwable $th) {
            DB::rollBack();
            return back()
               ->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])
                ->withInput();
             
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(EmployeHoliday $employeHoliday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $holiday = EmployeHoliday::with('branches')->findOrFail($id);
        $branches = Branch::all();

        $selectedBranches = $holiday->branches->pluck('branch_id')->toArray();

        return view('admin.employee-holiday.edit', compact('holiday', 'branches', 'selectedBranches'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $id = decrypt($id);
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:0,1',
                
                'branch_id' => 'required|array',
                'branch_id.*' => 'exists:branches,id',
                'start_date' => 'required|date|after_or_equal:today',
            ]);
            $start_date = $request->start_date;
            $end_date = null;


            $holiday = EmployeHoliday::findOrFail($id);
            $holiday->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
                'start_date' => $start_date,
                'end_date' => $end_date,
                'updated_by' => auth()->id(),
            ]);

            EmployeHolidayBranch::where('employe_holiday_id', $holiday->id)->delete();

            foreach ($validated['branch_id'] as $branchId) {
                EmployeHolidayBranch::create([
                    'employe_holiday_id' => $holiday->id,
                    'branch_id' => $branchId,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('employee-holiday.index')
                ->with('success', 'Employee holiday updated successfully!');

        }catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Employee Holiday Store Error: '.$th->getMessage());

            return back()
              ->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])
                ->withInput();
               
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $id = decrypt($id);

            EmployeHolidayBranch::where('employe_holiday_id', $id)->delete();
            EmployeHoliday::findOrFail($id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Holiday deleted successfully!'
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }

}