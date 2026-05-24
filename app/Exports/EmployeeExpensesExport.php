<?php

namespace App\Exports;

use App\Models\EmployeeAssets;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeExpensesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $employee_id;
    protected $month;

    public function __construct($employee_id = null, $month = null)
    {
        $this->employee_id = $employee_id;
        $this->month = $month;
    }

  public function collection()
{
    return EmployeeAssets::with('employee')
        ->when($this->employee_id, function ($q) {
            $q->where('employee_id', $this->employee_id);
        })
        ->when($this->month, function ($q) {
            $q->whereYear('start_date', date('Y', strtotime($this->month)))
              ->whereMonth('start_date', date('m', strtotime($this->month)));
        })
        ->orderBy('start_date', 'ASC')
        ->get();
}


    public function headings(): array
    {
        return [
            'Date',
            'Expense Type',
            'Employee Name',
            'City',         
             'HQ Allow',
             'Exstan Allow',
             'Outstan Allow',
             'Bus Ticket Amount ',
            'Amount',
            'Total Amount',
            'Status',
        ];
    }

    public function map($expense): array
    {
        return [
            optional($expense->created_at)->format('d-m-Y'),
            $expense->type,
            $expense->employee?->full_name ?? 'N/A',
            $expense->city?->name ?? 'N/A',       
            $expense->hq_allow ?? 'N/A',
            $expense->ex_stn_allow  ?? 'N/A',
            $expense->out_stn_allow ?? 'N/A',
            $expense->bus_ticket_amount ?? 'N/A',
            $expense->amount    ?? 'N/A',
            $expense->total_amount  ?? 'N/A',
            $expense->status,
        ];
    }
}
