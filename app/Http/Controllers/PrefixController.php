<?php

namespace App\Http\Controllers;

use App\Models\Prefix;
use Illuminate\Http\Request;
use DB;
use Validator;
use Illuminate\Support\Str;
use DataTables;


class PrefixController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Prefix::query()->orderBy('id', 'DESC');

            return Datatables::of($query)
                ->filterColumn('prefix', function ($query, $keyword) {

                    $query->where(function ($q) use ($keyword) {

                        // 🔹 Prefix related fields
                        $q->where('prefix', 'LIKE', "%{$keyword}%")
                            ->orWhere('separator', 'LIKE', "%{$keyword}%")
                            ->orWhere('current_number', 'LIKE', "%{$keyword}%")

                            // 🔹 Module (invoice, sales_order, etc.)
                            ->orWhere('module', 'LIKE', "%{$keyword}%")

                            // 🔹 Start from
                            ->orWhere('start_from', 'LIKE', "%{$keyword}%")

                            // 🔹 Created date
                            ->orWhereRaw(
                                "DATE_FORMAT(created_at, '%d %b, %Y') LIKE ?",
                                ["%{$keyword}%"]
                            )

                            // 🔹 Updated date
                            ->orWhereRaw(
                                "DATE_FORMAT(updated_at, '%d %b, %Y') LIKE ?",
                                ["%{$keyword}%"]
                            );
                    });
                })

                ->addIndexColumn()
                ->addColumn('prefix', function ($row) {

                    if (in_array($row->module, ['invoice', 'sales_order', 'credit_note', 'purchase_order'])) {

                        $nextNumber = $row->current_number + 1;

                        return $row->prefix
                            . $row->separator
                            . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
                    }

                    return $row->prefix;
                })


                ->addColumn('module', function ($row) {

                    return Str::title(str_replace('_', ' ', $row->module));
                })

                ->addColumn('start_from', function ($row) {
                    return in_array($row->module, ['invoice', 'sales_order', 'credit_note', 'purchase_order'])
                        ? $row->start_from
                        : '-';
                })

                ->addColumn('created_at', fn($row) => formatDate($row->created_at))
                ->addColumn('updated_at', fn($row) => formatDate($row->updated_at))

                ->addColumn('action', function ($row) {
                    $type = "action";
                    return view('admin.prefix.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action', 'created_at', 'updated_at', 'prefix', 'module', 'start_from'])
                ->make(true);
        }

        return view('admin.prefix.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.prefix.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'prefix_name' => 'required|string|max:20',
            'module'      => 'required|string|unique:prefixes,module',
            'start_from'  => 'nullable|integer|min:1',
            'separator'   => 'nullable|string|max:2',
        ]);

        DB::beginTransaction();

        try {
            $isNumbered = in_array($request->module, [
                'invoice',
                'sales_order',
                'credit_note',
                'purchase_order',
            ]);

            Prefix::create([
                'module'         => $request->module,
                'prefix'         => $request->prefix_name,
                'separator'      => $request->separator ?? '-',
                'start_from'     => $isNumbered ? $request->start_from : 1,
                'current_number' => $isNumbered ? $request->start_from - 1 : 0,
                'status'         => 1,
            ]);

            DB::commit();

            return redirect()
                ->route('prefixes.index')
                ->with('success', 'Prefix added successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Prefix $prefix)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $prefix = Prefix::findOrFail($id);
        return view('admin.prefix.edit', compact('prefix'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $prefix = Prefix::findOrFail($id);

            $request->validate([
                'prefix_name' => 'required|string|max:20',
                'module' => 'required|string|unique:prefixes,module,' . $prefix->id,
                'start_from' => 'nullable|integer|min:1',
                'separator' => 'nullable|string|max:2',
            ]);

            $isNumbered = in_array($request->module, ['invoice', 'sales_order', 'credit_note', 'purchase_order']);

            if ($isNumbered && !in_array($prefix->module, ['invoice', 'sales_order', 'credit_note', 'purchase_order'])) {
                $prefix->current_number = $request->start_from - 1;
            }

            if (!$isNumbered) {
                $prefix->start_from = 1;
                $prefix->current_number = 0;
            }

            $prefix->update([
                'prefix' => $request->prefix_name,
                'module' => $request->module,
                'separator' => $request->separator ?? '-',
                'start_from' => $isNumbered ? $request->start_from : 1,
                'current_number' => $prefix->current_number,
            ]);

            DB::commit();

            return redirect()
                ->route('prefixes.index')
                ->with('success', 'Prefix updated successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong! ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prefix $prefix)
    {
        //
    }
}
