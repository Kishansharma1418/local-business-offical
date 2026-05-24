<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
           $query = User::where('id', '!=', 1);

            // Filter Example
            if ($request->full_name) {
                $query->where('full_name', 'like', "%{$request->full_name}%");
            }

            return Datatables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M, Y') : '-';
                })
                  ->addColumn('action', function($row){
                    return view('admin.user.action', compact('row'))->render();
                })
                ->editColumn('status', function ($row) {
        switch ($row->status) {
            case '0':
                return '<span class="badge bg-warning">Pending</span>';
            case '1':
                return '<span class="badge bg-success">Active</span>';
            case '2':
                return '<span class="badge bg-secondary">Inactive</span>';
            case '3':
                return '<span class="badge bg-danger">Blocked</span>';
            case '4':
                return '<span class="badge bg-dark">Locked</span>';
            default:
                return '<span class="badge bg-light text-dark">Unknown</span>';
        }
    })
               
                ->rawColumns(['created_at','status','action'])
                ->make(true);
        }
                 $query = User::where('id', '!=', 1)
              ->select('full_name')
              ->groupBy('full_name')
              ->get();

                return view('admin.user.index',compact('query'));
            }
            
   public function updateStatus(Request $request, $id)
{
      $request->validate([
        'status' => 'required',
        'remark' => 'nullable|string|max:500',
    ]);


    $user = User::findOrFail($id);
    $user->status = $request->status;
     $user->remark = $request->remark;
    $user->save();

    return redirect()->back()->with('success', 'User status updated successfully!');
}


public function show($id)
    {
        $id = decrypt($id);
       $user = User::with(['createdBy', 'updatedBy'])->findOrFail($id);
        return view('admin.user.show',compact('user'));
    }

    
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
