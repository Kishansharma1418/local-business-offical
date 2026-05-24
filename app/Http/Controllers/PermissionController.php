<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Permission::query()->orderBy('id','DESC');

            return Datatables::of($query)
                ->addIndexColumn()

                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M, Y') : '-';
                })

               
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.permission.index');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name'       => 'required|max:50|unique:permissions,name'
        ]);

        if ($valid->fails()) {
            return response()->json(['status'=>'errors','errors'=>$valid->errors()]);
        }

        DB::beginTransaction();
        try {

            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            $permission = Permission::create([
                'name'       => $request->name,
                'main_group'        => $request->main_group,
                'sub_group'      => $request->sub_group,
                'guard_name' => 'web',
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => "Permission created successfully",
                'data'    => $permission
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       $permisison = Permission::findOrFail($id);

       return view('admin.permission.edit',compact('permisison'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), [
            'name'       => 'required|max:50|unique:permissions,name,'.$id
        ]);

        if ($valid->fails()) {
            return response()->json(['status' => 'errors', 'errors' => $valid->errors()]);
        }

        DB::beginTransaction();
        try {
            $permission = Permission::findOrFail($id);

            // Clear cache before update
            app()[PermissionRegistrar::class]->forgetCachedPermissions();
            $permission->update([
                'name'       => $request->name,
                'main_group' => $request->main_group,
                'sub_group'  => $request->sub_group,
                'guard_name' => 'web',
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => "Permission updated successfully",
                'data'    => $permission
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        //
    }
}
