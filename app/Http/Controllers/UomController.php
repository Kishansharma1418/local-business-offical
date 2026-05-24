<?php

namespace App\Http\Controllers;

use App\Models\Uom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Uom::query()->orderBy('id','DESC');
            if ($request->name) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            }
            return Datatables::of($query)
                ->addIndexColumn()
              ->editColumn('created_at', fn($row) => formatDate($row->created_at))
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $type = "action";
                    return view('admin.uom.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
            $query = Uom::select('name')->groupBy('name')->get();
        return view('admin.uom.index',compact('query'));
    }
 

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $uom = new Uom;
            $uom->name   = $request->name;
            $uom->description   = $request->description;
            $uom->status   = $request->status;
            $uom->save();
         

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => "UOM created successfully",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

  
    /**
     * Show the form for editing the specified resource.
     * 
     */
    public function edit($id)
    {
       $uom = Uom::findOrfail($id);
        return view('admin.uom.edit',compact('uom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {

        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'name'      => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $uom = Uom::findOrFail($id);
            $uom->name   = $request->name;
            $uom->description   = $request->description;
            $uom->status   = $request->status;
            $uom->save();


            DB::commit();
             $response['status'] = true;
            $response['message'] = "Uom Updated successfully";
            return response()->json($response);


        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
   
    public function destroy(Uom $uom)
    {
        try {
            $uom->delete();
            return response()->json(['success' => true, 'message' => 'Uom deleted successfully!']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
}
