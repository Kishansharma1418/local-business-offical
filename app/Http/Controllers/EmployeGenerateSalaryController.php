<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{EmployeGenerateSalary,EmployeeAssets,LoanAdvance,LastMonthAdjustment};
use App\Models\Employee;
use App\Models\EmployeeSalaryRevision;
use App\Models\EmployeeSalaryComponent;
use DataTables;
use DB;
use App\Exports\SalaryGenerateExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;


class EmployeGenerateSalaryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $month = $request->month ?? now()->month;
            $year = $request->year ?? now()->year;

            // $query = EmployeGenerateSalary::with('employee')
            //     ->where('month', $month)
            //     ->where('year', $year)
            //     ->orderBy('employee_id', 'ASC');
                $query = EmployeGenerateSalary::with('employee')
                    ->where('month', $month)
                    ->where('year', $year);

                // if (!auth()->user()->hasRole('admin')) {
                //     $query->where('employee_id', auth()->user()->reference_id);
                // }

                $query->orderBy('employee_id', 'ASC')->get();


            if ($request->employee_id) {
                        $query->where('employee_id', $request->employee_id);
            }
            return DataTables::of($query)
                 ->filterColumn('user', function ($query, $keyword) {
                    $query->whereHas('employee', function ($q) use ($keyword) {
                        $q->where('full_name', 'like', "%{$keyword}%")
                          ->orWhere('role', 'like', "%{$keyword}%")
                          ->orWhere('code', 'like', "%{$keyword}%")
                          ->orWhere('mobile_no', 'like', "%{$keyword}%");
                            $q->orWhereHas('branches', function ($qb) use ($keyword) {
                $qb->where('branch_name', 'like', "%{$keyword}%");});
                    });
      
                    })

                ->addIndexColumn()
                // ->addColumn('name', function ($row) {
                //     return $row->employee->full_name ?? '-';
                // })
                 ->addColumn('user', function($row) {

                    $user  = '<div class="d-flex align-items-center " style="gap:15px;">';
                 
                    $user .= '   <div class="flex-grow-1">';
                    $user .= '       <h6 class="mb-1" style="font-weight:600;color:#333;">'.($row->employee->full_name ?? 'N/A').'</h6>';
                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Type:</strong> '.($row->employee->role ?? '-').'</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Employee Code:</strong> '.($row->employee->code ?? '-').'</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Branch:</strong> '.($row->employee->branches->branch_name ?? '-').'</p>';
                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Phone:</strong> '.($row->employee->mobile_no ?? '-').'</p>';
                    
                    $user .= '   </div>';
                    $user .= '</div>';

                    return $user;
                })
                ->addColumn('present', fn($row) => $row->present_days)
                ->addColumn('leave', fn($row) => $row->absent_days)
                ->addColumn('weekly_off', fn($row) => $row->weekly_off)
                ->addColumn('half_day', fn($row) => $row->half_day)
                ->addColumn('holiday', fn($row) => $row->holiday)
                ->addColumn('action', function ($row) {
                    $type = "action";
                    return view('admin.employee-generate-salary.action', compact('row', 'type'))->render();
                })
                 ->addColumn('net_salary', function ($row) {
                return '₹' . number_format($row->net_salary, 2);
            })
                ->rawColumns(['action','user'])
                ->make(true);
        }
        $employee = Employee::whereIn('id', LoanAdvance::pluck('employee_id'))->where('id',auth()->user()->reference_id)->get();
        return view('admin.employee-generate-salary.index',compact('employee'));
    }

   public function store(Request $request)
{
    $month = $request->month;
    $year  = now()->year;

    $employees = Employee::where('status', '1')->get();
    $workingDays = 31;

    foreach ($employees as $emp) {

        $revision = EmployeeSalaryRevision::where('employee_id', $emp->id)
                    ->where('status', '1')
                    ->first();

        if (!$revision) continue;

        $newSalary     = $revision->new_salary_total; 
        $perDaySalary  = $newSalary / $workingDays;

        $lastmonthAdjustment = LastMonthAdjustment::where('employee_id', $emp->id)
            ->get()
            ->sum(function ($adj) {
                return $adj->status == 'Credit'
                    ? $adj->adjustment_amount
                    : -$adj->adjustment_amount;
            });

        // ================= SALES =================
        if ($emp->role == 'sales') {

            $assets = EmployeeAssets::where('employee_id', $emp->id)
                        ->where('status', 'Verified')
                        ->whereMonth('start_date', $month)
                        ->get();

            $presentDays = 0;
            $leaveDays = 0;
            $halfDays = 0;
            $holidayDays = 0;
            $weeklyOffDays = 0;

            $expenseTotal = 0;
            $unpaidDays = 0;

            $presentExpenseTypes = [
                'traveling','hotel','telephone','postage','printing',
                'advertisement','bankcharges','donation','mislinious',
                'exgratia_perquisites'
            ];

            foreach ($assets as $a) {

                if (in_array($a->type, $presentExpenseTypes)) {
                    $presentDays++;
                    $expenseTotal += $a->total_amount ?? 0;
                    continue;
                }

                switch ($a->type) {
                    case 'leave':
                        $leaveDays++;
                        $unpaidDays += 1;
                        break;

                    case 'halfday':
                        $halfDays++;
                        $unpaidDays += 0.5;
                        $expenseTotal += $a->total_amount ?? 0;
                        break;

                    case 'holiday':
                        $holidayDays++;
                        $expenseTotal += $a->total_amount ?? 0;
                        break;

                    case 'weekly_off':
                        $weeklyOffDays++;
                        $expenseTotal += $a->total_amount ?? 0;
                        break;

                    default:
                        $expenseTotal += $a->total_amount ?? 0;
                        break;
                }
            }

            $leaveAmount = $unpaidDays * $perDaySalary;
            $remaining = $newSalary - $leaveAmount;

            // BONUS
            $bonus = ($newSalary < 10000) ? round($remaining * 0.30, 2) : 0;

            $basic = $remaining * 0.50;
            $hra   = $remaining * 0.30;
            $conv  = $remaining * 0.20;

            // PF & ESI
            $pf = $esi = $pfCompany = $esiCompany = 0;
            $pfBase = $basic + $conv;

            if ($newSalary <= 21000) {
                $pf = round($pfBase * 0.13, 2);
                $esi = round($pfBase * 0.0325, 2);

                $pfCompany = round($pfBase * 0.12, 2);
                $esiCompany = round($pfBase * 0.0075, 2);
            }

            $loanDeduction = LoanAdvance::where('employee_id', $emp->id)
                ->where('status', 'Active')
                ->sum('deduction_amount');

            // TOTALS
            $totalDeduction = $pf + $esi + $loanDeduction + $leaveAmount;
            $totalEarning = $remaining + $bonus + $expenseTotal + $lastmonthAdjustment;
            $companyContribution = $pfCompany + $esiCompany;

            $netSalary = $totalEarning - ($pf + $esi + $loanDeduction);

            EmployeGenerateSalary::updateOrCreate(
                ['employee_id' => $emp->id, 'month' => $month, 'year' => $year],
                [
                    'basic_salary' => round($basic, 2),
                    'gross_salary' => $newSalary,
                    'hra_amount' => round($hra, 2),
                    'conveyance_amount' => round($conv, 2),

                    'pf_amount' => $pf,
                    'esi_amount' => $esi,

                    'pf_company_contribution' => $pfCompany,
                    'esi_company_contribution' => $esiCompany,
                    'total_company_contribution' => $companyContribution,

                    'leave_amount' => $leaveAmount,
                    'loan_amount_deduction' => $loanDeduction,
                    'expense_total' => $expenseTotal,

                    'last_month_adjustment' => $lastmonthAdjustment,
                    'bounnce_employee' => $bonus,

                    'total_deduction' => round($totalDeduction, 2),
                    'total_earning' => round($totalEarning, 2),

                    'present_days' => $presentDays,
                    'absent_days'  => $leaveDays,
                    'half_day'     => $halfDays,
                    'holiday'      => $holidayDays,
                    'weekly_off'   => $weeklyOffDays,

                    'net_salary' => round($netSalary, 2),
                    'created_by' => auth()->id(),
                ]
            );

            continue;
        }

        // ================= NON-SALES =================

        $expenseTotal = 0;

        $loanDeduction = LoanAdvance::where('employee_id', $emp->id)
            ->whereDate('start_month', '<=', now())
            ->where('status', 'Active')
            ->sum('deduction_amount');

        $attendance = DB::table('employe_attandances')
            ->selectRaw('
                COUNT(CASE WHEN status="Present" THEN 1 END) as present,
                COUNT(CASE WHEN status="Leave" THEN 1 END) as `leave`,
                COUNT(CASE WHEN status="Half Day" THEN 1 END) as half_day
            ')
            ->where('employee_id', $emp->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->first();

        $fullLeave = $attendance->leave;
        $halfLeave = $attendance->half_day * 0.5;
        $unpaidDays = $fullLeave + $halfLeave;

        $leaveAmount = $unpaidDays * $perDaySalary;
        $remaining = $newSalary - $leaveAmount;

        // BONUS
        $bonus = ($newSalary <= 10000) ? round($remaining * 0.30, 2) : 0;

        $basic  = $remaining * 0.50;
        $hra    = $remaining * 0.30;
        $conv   = $remaining * 0.20;

        // PF & ESI
        $pf = $esi = $pfCompany = $esiCompany = 0;
        $pfBase = $basic + $conv;

        if ($newSalary <= 21000) {
            $pf = round($pfBase * 0.13, 2);
            $esi = round($pfBase * 0.0325, 2);

            $pfCompany = round($pfBase * 0.12, 2);
            $esiCompany = round($pfBase * 0.0075, 2);
        }

        // TOTALS
        $totalDeduction = $pf + $esi + $loanDeduction + $leaveAmount;
        $totalEarning = $remaining + $bonus + $lastmonthAdjustment;
        $companyContribution = $pfCompany + $esiCompany;

        $netSalary = $totalEarning - ($pf + $esi + $loanDeduction);

        EmployeGenerateSalary::updateOrCreate(
            ['employee_id'=>$emp->id,'month'=>$month,'year'=>$year],
            [
                'basic_salary' => round($basic,2),
                'gross_salary' => $newSalary,
                'hra_amount'   => round($hra,2),
                'conveyance_amount' => round($conv,2),

                'pf_amount' => $pf,
                'esi_amount' => $esi,

                'pf_company_contribution' => $pfCompany,
                'esi_company_contribution' => $esiCompany,
                'total_company_contribution' => $companyContribution,

                'leave_amount' => $leaveAmount,
                'loan_amount_deduction' => $loanDeduction,

                'last_month_adjustment' => $lastmonthAdjustment,
                'bounnce_employee' => $bonus,

                'total_deduction' => round($totalDeduction, 2),
                'total_earning' => round($totalEarning, 2),

                'present_days' => $attendance->present,
                'absent_days'  => $fullLeave,
                'half_day'     => $attendance->half_day,

                'net_salary'   => round($netSalary,2),
                'created_by' => auth()->id(),
            ]
        );
    }

    return response()->json(['message' => 'Salary generated successfully!']);
}
    // public function store(Request $request)
    // {
    //     $month = $request->month;
    //     $year  = now()->year;

    //     $employees = Employee::where('status', '1')->get();
    //     $workingDays = 31;

    //     foreach ($employees as $emp) {

    //         $revision = EmployeeSalaryRevision::where('employee_id', $emp->id)
    //                     ->where('status', '1')
    //                     ->first();

    //         if (!$revision) continue;

    //         $newSalary     = $revision->new_salary_total; 
    //         $perDaySalary  = $newSalary / $workingDays;

    //         $lastmonthAdjustment = LastMonthAdjustment::where('employee_id', $emp->id)
    //             ->get()
    //             ->sum(function ($adj) {
    //                 return $adj->status == 'Credit'
    //                     ? $adj->adjustment_amount
    //                     : -$adj->adjustment_amount;
    //             });

    
    //         if ($emp->role == 'sales') {

    //             $assets = EmployeeAssets::where('employee_id', $emp->id)
    //                         ->where('status', 'Verified')
    //                         ->whereMonth('start_date', $month)
    //                         ->get();

    //             $presentDays = 0;
    //             $leaveDays = 0;
    //             $halfDays = 0;
    //             $holidayDays = 0;
    //             $weeklyOffDays = 0;

    //             $expenseTotal = 0;
    //             $unpaidDays = 0;

    //             $presentExpenseTypes = [
    //                 'traveling','hotel','telephone','postage','printing',
    //                 'advertisement','bankcharges','donation','mislinious',
    //                 'exgratia_perquisites'
    //             ];

    //             foreach ($assets as $a) {

    //                 if (in_array($a->type, $presentExpenseTypes)) {
    //                     $presentDays += 1;
    //                     $expenseTotal += $a->total_amount ?? 0;
    //                     continue;
    //                 }

    //                 switch ($a->type) {

    //                     case 'leave':
    //                         $leaveDays += 1;
    //                         $unpaidDays += 1;
    //                         break;

    //                     case 'halfday':
    //                         $halfDays += 1;
    //                         $unpaidDays += 0.5;
    //                         $expenseTotal += $a->total_amount ?? 0;
    //                         break;

    //                     case 'holiday':
    //                         $holidayDays += 1;
    //                         $expenseTotal += $a->total_amount ?? 0;
    //                         break;

    //                     case 'weekly_off':
    //                         $weeklyOffDays += 1;
    //                         $expenseTotal += $a->total_amount ?? 0;
    //                         break;

    //                     default:
    //                         $expenseTotal += $a->total_amount ?? 0;
    //                         break;
    //                 }
    //             }

    //             $leaveAmount = $unpaidDays * $perDaySalary;

    //             $remaining = $newSalary - $leaveAmount;

    //             $tdsAmount = $remaining * 0.10;

    //             $basic = $remaining * 0.50;
    //             $hra   = $remaining * 0.30;
    //             $conv  = $remaining * 0.20;

    //             $pf = 0;
    //             if ($emp->pf_aplicable == '1') {
    //                 $pf = round(($basic + $hra) * 0.12, 2);
    //             }

    //             $esi = 0;
    //             if ($emp->esi_aplicable == '1') {
    //                 $esi = round($remaining * 0.0075, 2);
    //             }

    //             $loanDeduction = LoanAdvance::where('employee_id', $emp->id)
    //                 ->where('status', 'Active')
    //                 ->sum('deduction_amount');

    //             $netSalary = $remaining - $pf - $esi - $loanDeduction - $tdsAmount + $expenseTotal + $lastmonthAdjustment;

    //             EmployeGenerateSalary::updateOrCreate(
    //                 ['employee_id' => $emp->id, 'month' => $month, 'year' => $year],
    //                 [
    //                     'basic_salary' => round($basic, 2),
    //                     'gross_salary' => $newSalary,
    //                     'hra_amount' => round($hra, 2),
    //                     'conveyance_amount' => round($conv, 2),

    //                     'tds_amount' => round($tdsAmount, 2),
    //                     'pf_amount' => $pf,
    //                     'esi_amount' => $esi,
    //                     'last_month_adjustment' => $lastmonthAdjustment,

    //                     'leave_amount' => $leaveAmount,
    //                     'loan_amount_deduction' => $loanDeduction,
    //                     'expense_total' => $expenseTotal,

    //                     'present_days' => $presentDays,
    //                     'absent_days'  => $leaveDays,
    //                     'half_day'     => $halfDays,
    //                     'holiday'      => $holidayDays,
    //                     'weekly_off'   => $weeklyOffDays,

    //                     'net_salary' => round($netSalary, 2),
    //                     'created_by' => auth()->id(),
    //                 ]
    //             );

    //             continue;
    //         }

    //         $loanDeduction = LoanAdvance::where('employee_id', $emp->id)
    //             ->whereDate('start_month', '<=', now())
    //             ->where('status', 'Active')
    //             ->sum('deduction_amount');
 
    //         $attendance = DB::table('employe_attandances')
    //             ->selectRaw('
    //                 COUNT(CASE WHEN status="Present" THEN 1 END) as present,
    //                 COUNT(CASE WHEN status="Leave" THEN 1 END) as `leave`,
    //                 COUNT(CASE WHEN status="Half Day" THEN 1 END) as half_day
    //             ')
    //             ->where('employee_id', $emp->id)
    //             ->whereMonth('date', $month)
    //             ->whereYear('date', $year)
    //             ->first();

    //         $fullLeave = $attendance->leave;
    //         $halfLeave = $attendance->half_day * 0.5;

    //         $unpaidDays = $fullLeave + $halfLeave;

    //         $leaveAmount = $unpaidDays * $perDaySalary;

    //         $remaining = $newSalary - $leaveAmount;

    //         $tdsAmount = $remaining * 0.10;

    //         $basic  = $remaining * 0.50;
    //         $hra    = $remaining * 0.30;
    //         $conv   = $remaining * 0.20;

    //         $pf = 0;
    //         if ($emp->pf_aplicable == '1') {
    //             $pf = round(($basic + $hra) * 0.12, 2);
    //         }

    //         $esi = 0;
    //         if ($emp->esi_aplicable == '1') {
    //             $esi = round($remaining * 0.0075, 2);
    //         }

    //         $netSalary = $remaining - $pf - $esi - $loanDeduction - $tdsAmount + $lastmonthAdjustment;

    //         EmployeGenerateSalary::updateOrCreate(
    //             ['employee_id'=>$emp->id,'month'=>$month,'year'=>$year],
    //             [
    //                 'basic_salary' => round($basic,2),
    //                 'gross_salary' => $newSalary,
    //                 'hra_amount'   => round($hra,2),
    //                 'conveyance_amount' => round($conv,2),

    //                 'pf_amount' => $pf,
    //                 'esi_amount' => $esi,
    //                 'tds_amount' => round($tdsAmount, 2),

    //                 'leave_amount' => $leaveAmount,
    //                 'loan_amount_deduction' => $loanDeduction,
    //                 'net_salary'   => round($netSalary,2),

    //                 'last_month_adjustment' => $lastmonthAdjustment,
    //                 'present_days' => $attendance->present,
    //                 'absent_days'  => $fullLeave,
    //                 'half_day'     => $attendance->half_day,

    //                 'created_by' => auth()->id(),
    //             ]
    //         );
    //     }

    //     return response()->json(['message' => 'Salary generated successfully!']);
    // }

    public function show($id)
    {
         $id  = decrypt($id);
        $salary = EmployeGenerateSalary::with('employee')->findOrFail($id);
        return view('admin.employee-generate-salary.show', compact('salary'));
    }


    public function downloadPdf($id)
    {
        $salary = EmployeGenerateSalary::with('employee')->findOrFail($id);

        $data = [
            'salary' => $salary
        ];

        $pdf = PDF::loadView('admin.employee-generate-salary.pdf', $data)->setPaper('A4', 'portrait');

        return $pdf->download('salary-slip-'.$salary->employee->full_name.'-'.$salary->month.'-'.$salary->year.'.pdf');
    }

    public function export(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year  = $request->year ?? now()->year;

        $salaries = EmployeGenerateSalary::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->orderBy('employee_id', 'ASC')
            ->get();

        return Excel::download(new SalaryGenerateExport($salaries), 'employee-salaries-'.$month.'-'.$year.'.xlsx');
    }



}