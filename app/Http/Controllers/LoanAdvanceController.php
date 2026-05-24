<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\{LoanAdvance, LastMonthAdjustment};
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\DB;

class LoanAdvanceController extends Controller
{
    /**
     * Display a listing of the resource (DataTables)
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $loans = LoanAdvance::with('employee')->orderBy('id', 'DESC');

            if (!auth()->user()->hasRole('admin')) {
                $loans->where('employee_id', auth()->user()->reference_id);
            }

            if ($request->employee_id) {
                $loans->where('employee_id', $request->employee_id);
            }
            return DataTables::of($loans)
                ->filterColumn('employee', function ($query, $keyword) {
                    $query->whereHas('employee', function ($q) use ($keyword) {
                        $q->where('full_name', 'like', "%{$keyword}%");
                        $q->orWhere('code', 'like', "%{$keyword}%");
                        $q->orWhere('mobile_no', 'like', "%{$keyword}%");
                        $q->orWhere('role', 'like', "%{$keyword}%");
                        $q->orWhereHas('branches', function ($qb) use ($keyword) {
                            $qb->where('branch_name', 'like', "%{$keyword}%");
                        });
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
                ->addColumn('start_month', function ($row) {
                    return formatDate($row->start_month);
                })

                ->addColumn('action', function ($row) {
                    return view('admin.loanadvance.action', compact('row'))->render();
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'Active') {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->rawColumns(['action', 'employee', 'status'])
                ->make(true);
        }
        $employee = Employee::whereIn('id', LoanAdvance::pluck('employee_id'))->get();

        return view('admin.loanadvance.index', compact('employee'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $employees = Employee::select('id', 'full_name')->orderBy('id', 'DESC')->get();
        return view('admin.loanadvance.create', compact('employees'));
    }

    /**
     * Store a newly created resource
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'employee_id' => 'required|string',
                'loan_amount' => 'required|numeric',
                'month' => 'required|string',
                'start_month' => 'required|date',
                'deduction_amount' => 'required|numeric',
                'status' => 'required|in:Active,Inactive',

            ]);
            $startMonthDate = $request->start_month . '-01';
            LoanAdvance::create([
                'employee_id' => $request->employee_id,
                'loan_amount' => $request->loan_amount,
                'month' => $request->month,
                'start_month' => $startMonthDate,
                'deduction_amount' => $request->deduction_amount,
                'status' => $request->status,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('loan-advances.index')->with('success', 'Loan  created successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit($id)
    {
        try {

            $id = decrypt($id);
            $loan = LoanAdvance::findOrFail($id);
            $employees = Employee::orderBy('id', 'DESC')->get();
            return view('admin.loanadvance.edit', compact('loan', 'employees'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $loan = LoanAdvance::findOrFail($id);

            $request->validate([
                'employee_id' => 'required|string',
                'loan_amount' => 'required|numeric',
                'month' => 'required|string',
                'start_month' => 'required|date',
                'deduction_amount' => 'required|numeric',
                'status' => 'required|in:Active,Inactive',
            ]);
            $startMonthDate = $request->start_month . '-01';
            $loan->update([
                'employee_id' => $request->employee_id,
                'loan_amount' => $request->loan_amount,
                'month' => $request->month,
                'start_month' => $startMonthDate,
                'deduction_amount' => $request->deduction_amount,
                'status' => $request->status,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('loan-advances.index')->with('success', 'Loan  updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified resource
     */
    public function destroy($id)
    {
        try {
            $loan = LoanAdvance::findOrFail($id);
            $loan->delete();
            return response()->json(['success' => true, 'message' => 'Loan  deleted successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the details of a single loan advance
     */

    public function show($id)
    {
        $id = decrypt($id);
        $loan = LoanAdvance::with(['creator', 'updater', 'employee'])->findOrFail($id);
        return view('admin.loanadvance.show', compact('loan'));
    }
    public function updateStatus(Request $request, $id)
    {
        $loan = LoanAdvance::findOrFail($id);
        $loan->status = $request->status;
        $loan->updated_by = auth()->id();
        $loan->save();
        return redirect()->route('loan-advances.show', encrypt($id))
            ->with('success', 'Loan status updated successfully!');
    }

    //  public function showLoanMonthAdjustmentlist(Request $request,$loan_id)
    // {

    //     if ($request->ajax()) {

    //         $query = LastMonthAdjustment::where('loan_id',$loan_id)->orderBy('id','DESC');

    //         return Datatables::of($query)
    //             ->addIndexColumn()

    //              ->editColumn('created_at', function ($row) {
    //                 return $row->created_at ? $row->created_at->format('d M, Y') : '-';
    //             })

    //              ->addColumn('loan', function($row) {

    //                 return $row->loanAdvance ? $row->loanAdvance->loan_amount : 'N/A';
    //             })

    //             ->addcolumn('status', function($row) {
    //                 $statusBadge = $row->status == 'Credit' ? 'success' : 'danger';
    //                 return '<span class="badge bg-' . $statusBadge . '">' . $row->status . '</span>';
    //             })

    //             ->addColumn('action', function ($row) {
    //                 $type = "action";
    //                 return view('admin.loanadvance.last-month-adjustments.action', compact('row', 'type'))->render();
    //             })
    //             ->rawColumns(['action','loan','status'])
    //             ->make(true);
    //     }

    //     return view('admin.loanadvance.last-month-adjustments.index',compact('loan_id'));
    // }

    // public function showLoanMonthAdjustmentCreate($loan_id)
    // {
    //     $loan_id = decrypt($loan_id);

    //     return view('admin.loanadvance.last-month-adjustments.create', compact('loan_id'));
    // }


    // public function showLoanMonthAdjustmentStore(Request $request)
    // {
    //     $loan_id = decrypt($request->loan_id);

    //     $request->validate([
    //         'adjustment_month' => 'required|string',
    //         'current_month' => 'required|string',
    //         'adjustment_amount' => 'required|numeric',
    //         'description' => 'nullable|string',
    //         'status' => 'required|in:Debit,Credit',
    //     ]);

    //     $loan = LoanAdvance::find($loan_id);
    //     if (!$loan) {
    //         return redirect()->back()->with('error', 'Loan not found.');
    //     }

    //   $data = [
    //         'loan_id'    => $loan_id,
    //         'employee_id'=> $loan->employee_id,
    //         'adjustment_date' => now(), 
    //         'adjustment_month' => $request->adjustment_month,
    //         'current_month' => $request->current_month,
    //         'adjustment_amount' => $request->adjustment_amount,
    //         'description' => $request->description,
    //         'status' => $request->status,
    //         'created_by' => auth()->id(),
    //     ];

    //     LastMonthAdjustment::create($data);

    //     return redirect()
    //         ->route('loan.showLoanMonthAdjustmentlist', $loan_id)
    //         ->with('success', 'Last Month Adjustment added successfully.');
    // }

    //  public function showLoanMonthAdjustmentEditForm($loan_id)
    // {
    //     $loan_id = decrypt($loan_id);
    //     $lastmonth = LastMonthAdjustment::findOrFail($loan_id);

    //     return view('admin.loanadvance.last-month-adjustments.edit', compact('loan_id','lastmonth'));
    // }

    // public function showLoanMonthAdjustmentUpdate(Request $request, $id)
    // {
    //     $id = decrypt($id);

    //     $lastmonth = LastMonthAdjustment::findOrFail($id);

    //     $request->validate([
    //         'adjustment_month'   => 'required|string',
    //         'current_month'      => 'required|string',
    //         'adjustment_amount'  => 'required|numeric',
    //         'description'        => 'nullable|string',
    //         'status'             => 'required|in:Debit,Credit',
    //     ]);

    //     $lastmonth->update([
    //         'adjustment_month'   => $request->adjustment_month,
    //         'current_month'      => $request->current_month,
    //         'adjustment_amount'  => $request->adjustment_amount,
    //         'description'        => $request->description,
    //         'status'             => $request->status,
    //         'updated_by' => auth()->id(),
    //     ]);

    //     return redirect()
    //         ->route('loan.showLoanMonthAdjustmentlist', $lastmonth->loan_id)
    //         ->with('success', 'Last Month Adjustment updated successfully.');
    // }
}
