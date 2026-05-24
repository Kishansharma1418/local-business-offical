<?php
namespace App\Exports;
use App\Models\Employee;
use App\Models\EmployeHoliday;
use App\Models\Leave;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Illuminate\Support\Facades\Log;

class EmployeeAttendanceSampleExport implements FromArray, WithHeadings, WithEvents, ShouldAutoSize
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;         
        $this->year = $year;
    }
    
    public function array(): array
    {
        
        $employees = Employee::select('id', 'full_name')->get();
        $daysInMonth = Carbon::createFromDate($this->year, $this->month, 1)->daysInMonth;

        $holidays = EmployeHoliday::where(function ($query) {
            $query->whereMonth('start_date', $this->month)
                ->whereYear('start_date', $this->year);
        })
        ->orWhere(function ($query) {
            $query->whereMonth('end_date', $this->month)
                ->whereYear('end_date', $this->year);
        })
        ->get();

        $holidayDates = [];
        foreach ($holidays as $holiday) {
            $start = Carbon::parse($holiday->start_date);
            $end = Carbon::parse($holiday->end_date);
            while ($start->lte($end)) {
                if ($start->month == $this->month && $start->year == $this->year) {
                    $holidayDates[] = $start->toDateString();
                }
                $start->addDay();
            }
        }

   
                $monthStart = Carbon::createFromDate($this->year, $this->month, 1)->startOfMonth()->toDateString();
                $monthEnd = Carbon::createFromDate($this->year, $this->month, 1)->endOfMonth()->toDateString();
                $leaves = Leave::whereDate('start_date', '<=', $monthEnd)
                ->whereDate('end_date', '>=', $monthStart)
                ->whereIn('status', ['Verified', 'verified', 'VERIFIED'])
                 ->get();

                $employeeLeaveDates = [];

                foreach ($leaves as $leave) {
                 $employeeId = $leave->employee_id;
                 $dates = [];

    $start = Carbon::parse($leave->start_date);
    $end = Carbon::parse($leave->end_date);

    for ($date = $start; $date->lte($end); $date->addDay()) {
        $dates[] = $date->format('Y-m-d');
    }

    if (!isset($employeeLeaveDates[$employeeId])) {
        $employeeLeaveDates[$employeeId] = [];
    }

    $employeeLeaveDates[$employeeId] = array_merge(
        $employeeLeaveDates[$employeeId],
        $dates
    );
}

        $data = [];
        foreach ($employees as $employee) {
            $row = [
                'Employee ID'   => $employee->id,
                'Employee Name' => $employee->full_name,
            ];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::createFromDate($this->year, $this->month, $day);
                $dateString = $date->toDateString();

               if ($date->isSunday()) {
    $status = 'Weekly Off';
} elseif (
    isset($employeeLeaveDates[$employee->id]) &&
    in_array($dateString, $employeeLeaveDates[$employee->id])
) {
    $status = 'Leave'; // ✅ Leave first
} elseif (in_array($dateString, $holidayDates)) {
    $status = 'Holiday';
} else {
    $status = 'Present';
}
                $row[$date->format('d-M')] = $status;
            }
            $data[] = $row;
        }
        return $data;
    }

    public function headings(): array
    {
        $daysInMonth = Carbon::createFromDate($this->year, $this->month, 1)->daysInMonth;
        $headings = ['Employee ID', 'Employee Name'];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($this->year, $this->month, $day);
            $headings[] = $date->format('d-M');
        }
        return $headings;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $daysInMonth = Carbon::createFromDate($this->year, $this->month, 1)->daysInMonth;

                $sheet->getStyle("A1:{$highestColumn}1")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4F81BD']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_HAIR);

                $statusOptions = '"Present,Leave,Holiday,Half Day,Weekly Off"';
                for ($col = 3; $col <= 5 + $daysInMonth; $col++) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $validation = $sheet->getCell("{$colLetter}{$row}")->getDataValidation();
                        $validation->setType(DataValidation::TYPE_LIST);
                        $validation->setErrorStyle(DataValidation::STYLE_STOP);
                        $validation->setAllowBlank(true);
                        $validation->setShowDropDown(true);
                        $validation->setFormula1($statusOptions);
                    }
                }

                $sheet->freezePane('A2');
            },
        ];
    }


}


