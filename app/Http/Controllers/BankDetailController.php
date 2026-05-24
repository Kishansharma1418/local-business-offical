<?php

namespace App\Http\Controllers;

use App\Models\BankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class BankDetailController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $banks = BankDetail::query()->orderBy('id', 'DESC');

            return DataTables::of($banks)
                ->addColumn('action', function ($row) {
                    return view('admin.bankdetails.action', compact('row'))->render();
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.bankdetails.index');
    }

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

            $bank = new BankDetail;
            $bank->name = $request->name;
            $bank->status = $request->status;

            $bank->save();
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Bank created successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $bank = BankDetail::findOrfail($id);
        return view('admin.bankdetails.edit', compact('bank'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $bank = BankDetail::findOrFail($id);
            $bank->name = $request->name;
            $bank->status = $request->status;
            $bank->save();

            DB::commit();
            $response['status'] = true;
            $response['message'] = 'Bank Updated successfully';
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(BankDetail $bankDetail)
    {
        try {
            $bankDetail->delete();
            return response()->json(['success' => true, 'message' => 'Bank deleted successfully!']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
}
