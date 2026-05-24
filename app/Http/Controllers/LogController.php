<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Log::query()->orderBy('id','DESC');

            return Datatables::of($query)
                ->addIndexColumn()
              ->editColumn('created_at', fn($row) => formatDate($row->created_at, 'd-m-Y h:i:s'))

                ->addColumn('action_user',function($row){
                    return $row->users?->full_name;
                })

                ->addColumn('action_by',function($row){
                    return $row->action;
                })
               
                ->addColumn('action_data', function ($row) {
                    $type = "action_data";
                    return view('admin.logs.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action_data','action_user']) 
                ->make(true);
        }

        return view('admin.logs.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);
        $log = Log::findOrFail($id);
        return view('admin.logs.show',compact('log'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
