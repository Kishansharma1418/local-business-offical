<?php

namespace App\Imports;

use App\Models\EmployeAttandance;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeAttandanceImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
       

        Log::info('🔹 Starting attendance import... Total rows: ' . count($rows));

        foreach ($rows as $rowIndex => $row) {
            $row = collect($row)->keyBy(fn($v, $k) => str_replace([' ', '_'], '-', strtolower(trim($k))))->toArray();

            $employeeId = $row['employee-id'] ?? null;
            $employeeName = $row['employee-name'] ?? null;
            $checkIn = $row['check-in'] ?? null;
            $checkOut = $row['check-out'] ?? null;
            $remarks = $row['remarks'] ?? null;

            if (empty($employeeId)) {
                Log::warning(" Skipping row {$rowIndex}: employee_id missing");
                continue;
            }

            foreach ($row as $key => $value) {
                if (in_array($key, ['employee-id', 'employee-name', 'check-in', 'check-out', 'remarks'])) continue;

                $date = null;
                try {
                    $keyFormatted = str_replace('_', '-', $key);
                    
                    $date = Carbon::parse($keyFormatted)->year(now()->year);
                } catch (\Exception $e1) {
                    try {
                        $date = Carbon::createFromFormat('d-M', ucfirst(str_replace('_', '-', $key)))->year(now()->year);
                    } catch (\Exception $e2) {
                        Log::warning("Invalid date format '$key' — skipping column");
                        continue;
                    }
                }

                $status = trim((string)$value);
                if ($status === '') $status = 'Leave';

                $isHoliday = in_array(strtolower($status), ['weekly off', 'holiday']);
                $holidayName = $isHoliday ? ucfirst(strtolower($status)) : null;

                try {
                    EmployeAttandance::updateOrCreate(
                        [
                            'employee_id' => $employeeId,
                            'date' => $date->format('Y-m-d'),
                        ],
                        [
                            'name' => $employeeName,
                            'check_in' => $checkIn,
                            'check_out' => $checkOut,
                            'remarks' => $remarks,
                            'status' => ucfirst(strtolower($status)),
                            'is_holiday' => $isHoliday,
                            'holiday_name' => $holidayName,
                        ]
                    );

                    Log::info("Imported: EmpID {$employeeId}, Date {$date->format('Y-m-d')}, Status {$status}");
                } catch (\Exception $e) {
                    Log::error(" DB error (row {$rowIndex}, date {$key}): " . $e->getMessage());
                }
            }
        }

        Log::info('Attendance import completed successfully.');
    }
}
