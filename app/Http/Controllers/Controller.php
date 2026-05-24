<?php

namespace App\Http\Controllers;

use App\Models\Prefix;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    public function generateCode($module)
    {
        return DB::transaction(function () use ($module) {
            $prefix = Prefix::where('module', $module)
                ->where('status', 1)
                ->lockForUpdate()
                ->firstOrFail();

            $nextNumber = $prefix->current_number + 1;

            $code = $prefix->prefix
                . $prefix->separator
                . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $prefix->increment('current_number');

            return $code;
        });
    }

    //     public function generateCode($module)
    // {
    //     return DB::transaction(function () use ($module) {

    //         $prefix = Prefix::where('module', $module)
    //             ->where('status', 1)
    //             ->lockForUpdate()
    //             ->firstOrFail();

    //       if (
    //         is_null($prefix->current_number) ||
    //             $prefix->current_number < $prefix->start_from
    //         ) {
    //             $nextNumber = $prefix->start_from;
    //         } else {
    //             $nextNumber = $prefix->current_number + 1;
    //         }

    //         $code = $prefix->prefix
    //             . $prefix->separator
    //             . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

    //         // current_number update karo
    //         $prefix->update([
    //             'current_number' => $nextNumber
    //         ]);

    //         return $code;
    //     });
    // }
}
