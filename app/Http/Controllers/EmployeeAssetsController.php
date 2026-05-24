<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\{State};
use App\Models\City;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\EmployeeAssets;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeExpensesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeAssetsController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $assets = EmployeeAssets::select(
                'employee_id',
                DB::raw('SUM(total_amount) as total_amount_sum'),
                DB::raw('GROUP_CONCAT(DISTINCT status) as all_statuses')
            )
                ->with('employee')
                ->groupBy('employee_id');

            if ($request->filled('employee_id')) {
                $assets->where('employee_id', $request->employee_id);
            }

            if ($request->filled('month')) {
                try {
                    $month = Carbon::parse($request->month)->format('m');
                    $year = Carbon::parse($request->month)->format('Y');

                    $assets->whereYear('start_date', $year)
                        ->whereMonth('start_date', $month);
                } catch (\Exception $e) {
                }
            }

            return DataTables::of($assets)
                ->filterColumn('employee', function ($query, $keyword) {
                    $query->whereHas('employee', function ($q) use ($keyword) {
                        $q->where('full_name', 'like', "%{$keyword}%")
                            ->orWhere('role', 'like', "%{$keyword}%")
                            ->orWhere('code', 'like', "%{$keyword}%")
                            ->orWhereHas('branches', function ($branchQuery) use ($keyword) {
                                $branchQuery->where('branch_name', 'like', "%{$keyword}%");
                            })
                            ->orWhere('mobile_no', 'like', "%{$keyword}%");
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

                ->addColumn('total_amount', fn($row) => number_format($row->total_amount_sum, 2))

                ->addColumn('status', function ($row) {
                    $statuses = explode(',', $row->all_statuses);

                    $html = '';
                    foreach ($statuses as $status) {
                        $statusClass = match ($status) {
                            'Verified' => 'bg-success',
                            'Rejected' => 'bg-danger',
                            default => 'bg-warning',
                        };

                        $html .= '<span class="badge ' . $statusClass . ' me-1 mb-1">' . $status . '</span>';
                    }

                    return $html;
                })



                ->addColumn('action', function ($row) {
                    return view('admin.employee-assets.action', compact('row'))->render();
                })

                ->rawColumns(['status', 'action', 'employee'])
                ->make(true);
        }

        $employee = Employee::whereIn('id', EmployeeAssets::pluck('employee_id'))->get();
        return view('admin.employee-assets.index', compact('employee'));
    }


    public function create()
    {
        $employee = Employee::select('id', 'full_name')->get();

        return view('admin.employee-assets.create', compact('employee'));
    }


    public function store(Request $request)
    {
        try {
            $savedIds = [];

            foreach ($request->expenses as $index => $exp) {

                if (!$exp['date']) continue;

                if (!empty($exp['id'])) {
                    $asset = EmployeeAssets::find($exp['id']);
                    if (!$asset) continue;
                } else {
                    $asset = new EmployeeAssets();
                    $asset->employee_id = auth()->user()->reference_id;
                    $asset->start_date = $exp['date'];
                    $asset->created_by = auth()->user()->reference_id;
                }

                $asset->end_date = $exp['date'];
                $asset->type = $exp['type'];
                $asset->working_type = $exp['working_type'] ?? null;
                $asset->travel_from = $exp['travel_from'] ?? null;
                $asset->travel_to = $exp['travel_to'] ?? null;
                $asset->amount = $exp['amount'] ?? 0;
                $asset->hq_allow = $exp['hq_allow'] ?? 0;
                $asset->ex_stn_allow = $exp['ex_stn_allow'] ?? 0;
                $asset->out_stn_allow = $exp['out_stn_allow'] ?? 0;
                $asset->bus_ticket_amount = $exp['bus_ticket'] ?? 0;
                $asset->total_amount = $exp['total'] ?? 0;
                $asset->distance = $exp['distance'] ?? null;

                if (!$asset->status) {
                    $asset->status = 'Submited';
                }

                if ($request->hasFile("expenses.$index.file")) {

                    $file = $request->file("expenses.$index.file");

                    $filename = time() . "_" . $file->getClientOriginalName();
                    $path = $file->storeAs('employee_expenses', $filename, 'public');

                    $asset->image = $path;
                }

                $asset->save();
                if ($asset->type == 'leave') {

                    Leave::updateOrCreate(
                        [
                            'employee_id' => $asset->employee_id,
                            'start_date'  => $asset->start_date
                        ],
                        [
                            'end_date'     => $asset->end_date,
                            'leave_type'   => 'full day',
                            'status'       => 'Pending',
                            'description'  => 'Leave added from expense',
                            'total_days'   => 1,
                            'created_by'   => auth()->id()
                        ]
                    );
                }
                $savedIds[] = $asset->id;
            }

            return response()->json([
                'success' => true,
                'message' => 'Expenses saved successfully!',
                'saved_ids' => $savedIds,
                'image' => $asset->image
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }



    public function fetchExpenses(Request $request)
    {
        $month = Carbon::parse($request->month);

        $data = EmployeeAssets::where('employee_id', $request->employee_id)
            ->whereYear('start_date', $month->year)
            ->whereMonth('start_date', $month->month)
            ->status('status', 'Submited')
            ->orWhere('status', 'Verified')
            ->orWhere('status', 'Pending')
            ->orWhere('status', 'Rejected')

            ->get();

        return response()->json($data);
    }

    public function fetchMonth(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'month' => 'required'
        ]);

        $month = $request->month;

        $data = EmployeeAssets::where('employee_id', $request->employee_id)
            ->where('start_date', 'like', "$month-%")
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }


    public function edit($id)
    {
        try {
            $id = decrypt($id);
            $employee = Employee::select('id', 'full_name')->get();
            $employeeAsset = EmployeeAssets::findOrFail($id);
            $countries = Country::orderBy('name', 'ASC')->get();
            $states = State::where('country_id', $employeeAsset->country_id)->orderBy('name')->get();
            $cities = City::where('state_id', $employeeAsset->state_id)->orderBy('name')->get();
            return view('admin.employee-assets.edit', compact('employeeAsset', 'countries', 'states', 'cities', 'employee'));
        } catch (Exception $e) {
            return redirect()->route('employee-expense.index')->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $employeeAsset = EmployeeAssets::findOrFail($id);

            $validated = $request->validate([
                'type' => 'nullable|string',
                'hq_allow' => 'nullable|string',
                'ex_stn_allow' => 'nullable|string',
                'out_stn_allow' => 'nullable|string',
                'bus_ticket_amount' => 'nullable|string',
                'amount' => 'nullable|string',
                'total_amount' => 'nullable|string',


            ]);

            $employeeAsset->update([
                'city_id' => $request->city_id,
                'employee_id' => $request->employee_id,
                'state_id' => $request->state_id,
                'country_id' => $request->country_id,
                'type' => $request->type,
                'hq_allow' => $request->hq_allow,
                'ex_stn_allow' => $request->ex_stn_allow,
                'out_stn_allow' => $request->out_stn_allow,

                'bus_ticket_amount' => $request->bus_ticket_amount,
                'amount' => $request->amount,
                'total_amount' => $request->total_amount,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'updated_by' => auth()->id(),
            ]);

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Asset updated successfully']);
            }

            return redirect()->route('employee-expense.index')->with('success', 'Employee asset updated successfully.');
        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $employee_id = decrypt($id);

        $employee = Employee::findOrFail($employee_id);

        // Current month
        $currentMonth = now()->format('Y-m');
        // Show only previous month
        $month = request('month') ?? now()->subMonth()->format('Y-m');
        if ($month >= $currentMonth) {
            $month = now()->subMonth()->format('Y-m');
        }

        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);

        $expenses = EmployeeAssets::where('employee_id', $employee_id)
            ->whereYear('start_date', $year)
            ->whereMonth('start_date', $monthNum)
            ->orderBy('start_date')
            ->get();

        return view('admin.employee-assets.show', compact('employee', 'expenses', 'month'));
    }


    public function destroy($id)
    {
        try {
            $employeeAsset = EmployeeAssets::findOrFail($id);
            $employeeAsset->delete();

            return response()->json(['success' => true, 'message' => 'Expense deleted successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }



    public function approveAll(Request $request)
    {
        try {
            if (!$request->month) {
                return response()->json(['message' => 'Please select a month!'], 400);
            }

            if (!$request->employee_id) {
                return response()->json(['message' => 'Please select an employee!'], 400);
            }

            $month = date('m', strtotime($request->month));
            $year  = date('Y', strtotime($request->month));

            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $totalExpenses = EmployeeAssets::where('employee_id', $request->employee_id)
                ->whereMonth('start_date', $month)
                ->whereYear('start_date', $year)
                ->count();

            if ($totalExpenses != $daysInMonth) {
                return response()->json([
                    'message' => "Cannot Approve! Required $daysInMonth expenses for full month, but found $totalExpenses."
                ], 400);
            }

            $notSubmitted = EmployeeAssets::where('employee_id', $request->employee_id)
                ->whereMonth('start_date', $month)
                ->whereYear('start_date', $year)
                ->whereNotIn('status', ['Submited', 'Verified', 'Pending', 'Rejected'])
                ->count();

            if ($notSubmitted > 0) {
                return response()->json([
                    'message' => 'Cannot Approve! Some expenses are still not submitted.'
                ], 400);
            }

            $updated = EmployeeAssets::where('employee_id', $request->employee_id)
                ->whereMonth('start_date', $month)
                ->whereYear('start_date', $year)
                ->update([
                    'status' => 'Verified',
                    'updated_by' => auth()->id()
                ]);

            return response()->json([
                'message' => "$updated expenses approved successfully!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    public function exportExcel(Request $request)
    {
        $employee_id = $request->employee_id;
        $month = $request->month;

        return Excel::download(new EmployeeExpensesExport($employee_id, $month), 'employee_expenses.xlsx');
    }

    public function exportPDF(Request $request)
    {
        $employee_id = $request->employee_id;
        $month = $request->month;

        $data = EmployeeAssets::with('employee')
            ->when($employee_id, function ($q) use ($employee_id) {
                $q->where('employee_id', $employee_id);
            })
            ->when($month, function ($q) use ($month) {
                $q->whereYear('start_date', date('Y', strtotime($month)))
                    ->whereMonth('start_date', date('m', strtotime($month)));
            })
            ->orderBy('start_date', 'ASC')
            ->get();

        $employee = $employee_id ? Employee::find($employee_id) : null;

        $pdf = Pdf::loadView('admin.employee-assets.pdf', compact('data', 'month', 'employee'));
        return $pdf->download('employee_expenses.pdf');
    }




    public function updateStatus(Request $request, $employee_id)
    {
        $request->validate([
            'status' => 'required|string',
            'reason' => 'nullable|string',
            'month'  => 'required|string'
        ]);

        $month = $request->month;

        $count = EmployeeAssets::where('employee_id', $employee_id)
            ->whereMonth('start_date', Carbon::parse($month)->month)
            ->whereYear('start_date', Carbon::parse($month)->year)
            ->count();

        if ($count == 0) {
            return response()->json([
                'error'   => true,
                'message' => 'No expenses found for this employee for the selected month!'
            ], 404);
        }

        EmployeeAssets::where('employee_id', $employee_id)
            ->whereMonth('start_date', Carbon::parse($month)->month)
            ->whereYear('start_date', Carbon::parse($month)->year)
            ->update([
                'status'      => $request->status,
                'reason'      => $request->reason,
                'updated_by'  => auth()->id(),
                'updated_at'  => now()
            ]);

        return response()->json([
            'error'   => false,
            'message' => 'All expenses updated successfully!'
        ]);
    }


    public function updateSingleStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        $expense = EmployeeAssets::find($id);

        if (!$expense) {
            return response()->json([
                'error' => true,
                'message' => 'Expense record not found!',
            ], 404);
        }

        $expense->status = $request->status;
        $expense->reason = $request->reason;
        $expense->updated_by = auth()->id();
        $expense->updated_at = now();
        $expense->save();

        return response()->json([
            'error' => false,
            'message' => 'Expense status updated successfully!',
        ]);
    }
}
