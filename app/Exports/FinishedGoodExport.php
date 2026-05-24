<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class FinishedGoodExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'name',
            'branch_id',
            'hsn_code',
            'category_id',
            'uom_id',
            'record_level',
            'lead_time_days',
            'status'
        ];
    }
}