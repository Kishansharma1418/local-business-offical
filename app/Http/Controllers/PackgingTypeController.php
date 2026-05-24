<?php

namespace App\Http\Controllers;

use App\Models\PackgingType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class PackgingTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $packgingTypes = PackgingType::query()->orderBy('id', 'DESC');

            return DataTables::of($packgingTypes)

                ->addColumn('action', function ($row) {
                    return view('admin.packging-types.action', compact('row'))->render();
                })

                ->editColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.packging-types.index');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'code'    => 'nullable|integer',
                'status'      => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            PackgingType::create([
                'name'        => $request->name,
                'code'        => $request->code,
                'status'      => $request->status,
                'created_by'  => auth()->id(),
             
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Packging Type created successfully'
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $packgingTypes = PackgingType::findOrFail($id);
        return view('admin.packging-types.edit', compact('packgingTypes'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'code'    => 'nullable|integer',
                'status'      => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $packgingTypes = PackgingType::findOrFail($id);

            $packgingTypes->update([
                'name'        => $request->name,
                'code'    => $request->code,
                'status'      => $request->status,
                'updated_by'  => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Packging Type updated successfully'
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(PackgingType $packgingTypes)
    {
        try {
            $packgingTypes->delete();

            return response()->json([
                'success' => true,
                'message' => 'Packging Type deleted successfully!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
    
}