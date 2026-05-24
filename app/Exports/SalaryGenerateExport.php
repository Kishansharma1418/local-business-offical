<?php

namespace App\Exports;

use App\Models\EmployeGenerateSalary;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalaryGenerateExport implements FromArray, WithHeadings
{
    protected $salaries;

    public function __construct($salaries)
    {
        $this->salaries = $salaries;
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->salaries as $salary) {
            $data[] = [
                'Employee Name' => $salary->employee->full_name,
                'Employee ID' => $salary->employee->code ?? 'N/A',
                'Employee Type' => $salary->employee->role ?? 'N/A',
                'UAN No' => $salary->employee->uan_no ?? 'N/A',
                'Designation' => $salary->employee->designation->name ?? 'N/A',
                'Department' => $salary->employee->departments->department_name ?? 'N/A',
                'Month' => $salary->month,
                'Year' => $salary->year,
                'Basic Salary' => $salary->basic_salary ?? 0,
                  'HRA' => $salary->hra_amount ?? 0,
                'Conveyance' => $salary->conveyance_amount ?? 0,
                'Gross Salary' => $salary->gross_salary ?? 0,
                'Leave Amount' => $salary->leave_amount ?? 0,
                'TDS Amount' => $salary->tds_amount ?? 0,
                'PF Amount' => $salary->pf_amount ?? 0,
                'ESI Amount' => $salary->esi_amount ?? 0,
                'Expense Total' => $salary->expense_total ?? 0,
                'Loan Deduction' => $salary->loan_amount_deduction ?? 0,
                'last Month Adjustment' => $salary->last_month_adjustment ?? 0,
                'Net Salary' => $salary->net_salary,

                'Present Days' => $salary->present_days ?? 0,
                'Absent Days' => $salary->absent_days ?? 0,
                'Weekly Off' => $salary->weekly_off ?? 0,
                'Half Day' => $salary->half_day ?? 0,
                'Holiday' => $salary->holiday ?? 0,

            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Employee Code',
            'Employee Type',
            'UAN No',
            'Designation',
            'Department',
            'Month',
            'Year',
            'Basic Salary',
            'HRA',
            'Conveyance',
            'Gross Salary',
            'Leave Adjustment',
            'TDS',
            'PF',
            'ESI',
            'Expense Total',
            'Loan Deduction',
            'last Month Adjustment',
            'Net Salary',
            'Present Days',
            'Absent Days',
            'Weekly Off',
            'Half Day',
            'Holiday',

        ];
    }

}
