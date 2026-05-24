<?php

namespace App\Http\Controllers;

use App\Models\BomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class BomTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bomTypes = BomType::query()->orderBy('id', 'DESC');

            return DataTables::of($bomTypes)
                ->addColumn('action', function ($row) {
                    return view('admin.bom-types.action', compact('row'))->render();
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.bom-types.index');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'order_no' => 'nullable|integer',
                'status' => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            BomType::create([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
                'order_no' => $request->order_no,
                'status' => $request->status,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'BOM Type created successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $bomType = BomType::findOrFail($id);
        return view('admin.bom-types.edit', compact('bomType'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'order_no' => 'nullable|integer',
                'status' => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $bomType = BomType::findOrFail($id);

            $bomType->update([
                'name' => $request->name,
                'order_no' => $request->order_no,
                'status' => $request->status,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'BOM Type updated successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(BomType $bomType)
    {
        try {
            $bomType->delete();

            return response()->json([
                'success' => true,
                'message' => 'BOM Type deleted successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
}
