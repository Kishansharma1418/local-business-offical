<?php

namespace App\Http\Controllers;

use App\Models\ErrorLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ErrorLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ErrorLog::query();

            return Datatables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M, Y h:i:s') : '-';
                })

                ->addColumn('action_user',function($row){
                    return $row->users?->full_name;
                })

                
               
                ->addColumn('action_data', function ($row) {
                    $type = "action_data";
                    return view('admin.error-logs.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action_data','action_user']) 
                ->make(true);
        }

        return view('admin.error-logs.index');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);
        $errorLog =  ErrorLog::with('users')->findOrFail($id);
        return view('admin.error-logs.show',compact('errorLog'));
    }

   
}
