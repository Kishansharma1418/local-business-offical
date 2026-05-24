<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Cache;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {


            $roles = Role::query('*')->latest();

            if ($request->name) {
                $roles->where('name', 'LIKE', '%' . $request->name . '%');
            }
            return $data =  Datatables::of($roles)


            // ->editColumn('status',function($row){
            //     $type = "status";
            //     return view('admin.role.action',compact('row','type'));
            // })

           ->editColumn('created_at', fn($row) => formatDate($row->created_at))

            ->editColumn('action',function($row){
                $type = "action";
                return view('admin.role.action',compact('row','type'));
            })
            ->rawColumns(['name','action','status','created_at'])
            ->make(true);
        }
        $roles = Role::select('name')->groupBy('name')->get();
        return view('admin.role.index',compact('roles'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valid = Validator::make($request->all(),[
            'name' => 'required|max:20|unique:roles,name'
        ]);

        if ($valid->fails()) {
            return response()->json(['status'=>'errors','errors'=>$valid->errors()]);
        }

        DB::beginTransaction();
        try{

              $role = \Spatie\Permission\Models\Role::create([
                'name'       => $request->name,
                'slug'       => \Str::slug($request->name),
                'guard_name' => 'web',
            ]);

            // Role::create($request->all());
            DB::commit();
            $response['status'] = true;
            $response['message'] = "Role created successfully";
            return response()->json($response);

        }catch(Exception $e){
            DB::rollback();
            $response['status'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);

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
    public function edit($id)
    {
        $roles = Role::findOrFail($id);
        return view('admin.role.edit',compact('roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(),[
            'name' => 'required|max:20|unique:roles,name,'.$id
        ]);

        if ($valid->fails()) {
            return response()->json(['status'=>'errors','errors'=>$valid->errors()]);
        }

        DB::beginTransaction();
        try{
            $role = Role::findOrFail($id);
            $role->update($request->only('name'));
            DB::commit();
            $response['status'] = true;
            $response['message'] = "Role updated successfully";
            return response()->json($response);

        }catch(Exception $e){

            DB::rollback();
            $response['status'] = false;
            $response['message'] = "Error";
            return response()->json($response);

        }
    }
        
    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return response()->json(['success' => true, 'message' => 'Role deleted successfully!']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }


   public function assignPermission(Request $request, $id)
{   
    $role = Role::findOrFail($id);

    if ($request->isMethod('post')) {
        $request->validate([
            'permissions' => 'required|array'
        ]);
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $role->syncPermissions($request->permissions);

        foreach ($role->users as $user) {
            Cache::forget('user_permissions_' . $user->id);
        }
        return back()->with('success', 'Permissions of this role assigned successfully.');
    }

    // Fetch and group permissions for unique sub_group blocks in UI
    $allPermissions = $this->getAllPermissions();

    $permissions = $allPermissions->groupBy('sub_group'); // Now collection grouped by sub_group
    $rolePermissions = $role->permissions()->pluck('name')->toArray();

    return view("admin.role.assign_permissions", compact('role', 'permissions', 'rolePermissions'));
}


    /**
    * Get All Permissions
    **/
    // public function getAllPermissions()
  

    public function getAllPermissions()
    {
        return Permission::select('id', 'name', 'main_group', 'sub_group')->get();
    }


    /**
     * Remove the specified resource from storage.
     */
   
}
