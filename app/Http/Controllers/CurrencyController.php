<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $currencies = Currency::query();

            return DataTables::of($currencies)
                ->addColumn('currency_info', function ($row) {
                    return '
                        <div>
                            <strong>' . $row->currency . '</strong><br>
                            <small>' . $row->code . ' ' . $row->symbol . '</small>
                        </div>
                    ';
                })
                ->addColumn('country', fn($row) => $row->country ?? '-')
                ->addColumn('minor_unit', fn($row) => $row->minor_unit ?? '-')
                ->rawColumns(['currency_info'])
                ->make(true);
        }

        return view('admin.currencies.index');
    }
}
