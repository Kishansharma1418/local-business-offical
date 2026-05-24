<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $bomTypes = ProductType::query()->orderBy('id', 'DESC');

            return DataTables::of($bomTypes)
   
                ->addColumn('action', function ($row) {
                    return view('admin.product-types.action', compact('row'))->render();
                })

                ->editColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.product-types.index');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'status'      => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            ProductType::create([
                'name'        => $request->name,
                'code'        => $request->code,
                'description' => $request->description,
                'status'      => $request->status,
                'created_by'  => auth()->id(),
             
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Product Type created successfully'
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
        $productType = ProductType::findOrFail($id);
        return view('admin.product-types.edit', compact('productType'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|max:255',
                'status'      => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $productType = ProductType::findOrFail($id);

            $productType->update([
                'name'        => $request->name,
                'description' => $request->description,
                'status'      => $request->status,
                'updated_by'  => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Product Type updated successfully'
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(ProductType $productType)
    {
        try {
            $productType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product Type deleted successfully!'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
    
}