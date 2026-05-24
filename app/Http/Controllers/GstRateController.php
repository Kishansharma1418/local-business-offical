<?php

namespace App\Http\Controllers;

use App\Models\GstRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Storage;


class GstRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = GstRate::query()->orderBy('id', 'DESC');

            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('gst_rate_name', function ($row) {

                    return $row->gst_rate_name . '%';
                })
                ->addColumn('cgst_rate', function ($row) {

                    return $row->cgst_rate . '%';
                })
                ->addColumn('sgst_rate', function ($row) {

                    return $row->sgst_rate . '%';
                })
                ->addColumn('igst_rate', function ($row) {

                    return $row->igst_rate . '%';
                })
                ->addColumn('created_at', fn($row) => formatDate($row->created_at))
                ->addColumn('updated_at', fn($row) => formatDate($row->updated_at))
                ->addColumn('action', function ($row) {
                    $type = "action";
                    return view('admin.gst-rate.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action', 'created_at'])
                ->make(true);
        }

        return view('admin.gst-rate.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gst-rate.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'gst_rate_name'        => 'required|string|max:255',
            'cgst_rate'       => 'required',
            'sgst_rate'        => 'required',
            'igst_rate'     => 'nullable|string',

        ]);

        DB::beginTransaction();
        try {
            //    $validated['created_by'] = auth()->id();
            $gst = GstRate::create($validated);

            DB::commit();
            return redirect()
                ->route('gst-rates.index')
                ->with('success', 'Gst Rate created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();



            return back()
                ->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()])
                ->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $gstRate = GstRate::findOrFail($id);
        return view('admin.gst-rate.edit', compact('gstRate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $gstRate = GstRate::findOrFail($id);

        $validated = $request->validate([

            'gst_rate_name' => 'required|string|max:255',
            'cgst_rate'       => 'required',
            'sgst_rate'        => 'required',
            'igst_rate'     => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $gstRate->update($validated);

            DB::commit();
            return redirect()
                ->route('gst-rates.index')
                ->with('success', 'Gst Rate updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();



            return back()
                ->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()])
                ->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        try {
            $gstRate = GstRate::findOrFail($id);
            $gstRate->delete();

            return response()->json(['success' => true, 'message' => 'GST deleted successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
