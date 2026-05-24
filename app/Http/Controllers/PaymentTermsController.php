<?php

namespace App\Http\Controllers;

use App\Models\PaymentTerms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class PaymentTermsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $terms = PaymentTerms::query()->orderBy('id','DESC');

            if ($request->name) {
                $terms->where('name', 'LIKE', '%' . $request->name . '%');
            }

            return DataTables::of($terms)
              
                ->addColumn('action', function ($row) {
                    return view('admin.paymentterms.action', compact('row'))->render();
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1
                        ?  '<span class="badge bg-success">Active</span>'
                        :  '<span class="badge bg-danger">Inactive</span>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
$terms = PaymentTerms::select('name')->groupBy('name')->get();
        return view('admin.paymentterms.index',compact('terms'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'name'  => 'required|string|max:255',
                'days'  => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $term = new PaymentTerms;
            $term->name       = $request->name;
            $term->days       = $request->days;
            $term->status     = $request->status ?? 1;
            $term->created_by = auth()->id();
            $term->save();

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => "Payment Term created successfully",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $term = PaymentTerms::findOrFail($id);
        return view('admin.paymentterms.edit', compact('term'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'name'  => 'required|string|max:255',
                'days'  => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $term = PaymentTerms::findOrFail($id);
            $term->name       = $request->name;
            $term->days       = $request->days;
            $term->status     = $request->status;
            $term->updated_by = auth()->id();
            $term->save();

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => "Payment Term updated successfully",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(PaymentTerms $paymentTerm)
    {
        try {
            $paymentTerm->delete();
            return response()->json([
                'success' => true,
                'message' => 'Payment Term deleted successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
}