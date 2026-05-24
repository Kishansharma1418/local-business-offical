<?php

namespace App\Imports;

use App\Models\FinishedGood;
use Maatwebsite\Excel\Concerns\ToModel;

class FinishedGoodImport implements ToModel
{
    public function model(array $row)
    {
        // Header skip
        if ($row[0] == 'name') return null;

        return new FinishedGood([
            'name'            => $row[0],
            'branch_id'       => $row[1],
            'hsn_code'        => $row[2],
            'category_id'     => $row[3],
            'uom_id'          => $row[4],
            'record_level'    => $row[5],
            'lead_time_days'  => $row[6],
            'status'          => $row[7] ?? 1,

            // optional fields
            'code'            => uniqid('FG-'),
            'created_by'      => auth()->id(),
            'updated_by'      => auth()->id(),
        ]);
    }
}