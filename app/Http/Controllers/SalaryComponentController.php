<?php

namespace App\Http\Controllers;

use App\Models\SalaryComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SalaryComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SalaryComponent::query()->orderBy('id', 'DESC');

            return Datatables::of($query)
                ->filterColumn('component_name', function ($query, $keyword) {

                    $query->where(function ($q) use ($keyword) {

                        // 🔹 Text fields
                        $q->where('component_name', 'LIKE', "%{$keyword}%")
                            ->orWhere('component_type', 'LIKE', "%{$keyword}%")
                            ->orWhere('calculation_type', 'LIKE', "%{$keyword}%")

                            // 🔹 Status text search
                            ->orWhere(function ($s) use ($keyword) {
                                if (strtolower($keyword) === 'active') {
                                    $s->where('status', 1);
                                } elseif (strtolower($keyword) === 'inactive') {
                                    $s->where('status', 0);
                                }
                            })

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
                ->addColumn('component_name', function ($row) {

                    return $row->component_name ?? "N/A";
                })
                ->addColumn('component_type', function ($row) {

                    return $row->component_type ?? "N/A";
                })
                ->addColumn('calculation_type', function ($row) {

                    return $row->calculation_type ?? "N/A";
                })

                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })

                ->addColumn('created_at', fn($row) => formatDate($row->created_at))
                ->addColumn('updated_at', fn($row) => formatDate($row->updated_at))

                ->addColumn('action', function ($row) {
                    return view('admin.salary-component.action', compact('row'))->render();
                })
                ->rawColumns(['action', 'created_at', 'status', 'is_taxable'])
                ->make(true);
        }

        return view('admin.salary-component.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $components = SalaryComponent::where('status', '1')->get();
        return view('admin.salary-component.create', compact('components'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'component_name'     => 'required|string|max:255',
    //         'component_type'     => 'required|in:Earning,Deduction',
    //         'calculation_type'   => 'required|in:Fixed,Percentage',
    //         'based_component_id' => 'nullable|integer|exists:salary_components,id',
    //         'status'             => 'required|in:0,1',
    //         'percentage_value'   => 'nullable|numeric',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $validated['created_by'] = auth()->id();

    //         SalaryComponent::create($validated);

    //         DB::commit();
    //         return redirect()
    //             ->route('salary-component.index')
    //             ->with('success', 'Salary Component created successfully.');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();

    //         return back()
    //             ->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()])
    //             ->withInput();
    //     }
    // }


    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'component_name' => 'required',
    //         'component_type' => 'required',
    //         'calculation_type' => 'required',
    //         'percentage_value' => 'nullable|numeric|min:0',
    //         'based_component_id' => 'nullable|integer'
    //     ]);


    //     if ($request->calculation_type == "Fixed") {
    //         $request->merge([
    //             'percentage_value' => null,
    //             'based_component_id' => null
    //         ]);
    //     } else if ($request->calculation_type == "Percentage") {
    //         if (!$request->based_component_id) {
    //             return back()->withErrors("Please select base component for percentage calculation.");
    //         }
    //         if (!$request->percentage_value) {
    //             return back()->withErrors("Please enter percentage value.");
    //         }
    //     }

    //     SalaryComponent::create($request->all());

    //     return redirect()->route('salary-component.index')->with('success', 'Component Added Successfully');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'component_name'     => 'required|string',
            'component_type'     => 'required|in:Earning,Deduction',
            'calculation_type'   => 'required|in:Fixed,Percentage',
            'percentage_value'   => 'nullable|numeric|min:0|max:100',
            'based_component_id' => 'nullable|integer|exists:salary_components,id'
        ]);

        // RULE: For Fixed type → percentage & based_component must be null
        if ($request->calculation_type == "Fixed") {
            $request->merge([
                'percentage_value' => null,
                'based_component_id' => null,
            ]);
        }

        // RULE: For Percentage type → based_component_id & percentage_value must exist
        if ($request->calculation_type == "Percentage") {

            if (!$request->based_component_id) {
                return back()->withErrors("Please select a Base Component for percentage calculation.");
            }
            if (!$request->percentage_value) {
                return back()->withErrors("Please enter a Percentage value.");
            }

            // Extra Rule: total_pay cannot have percentage
            if (strtolower($request->component_name) == "total_pay") {
                return back()->withErrors("Total Pay cannot be percentage based.");
            }
        }

        SalaryComponent::create($request->all());

        return redirect()->route('salary-component.index')
            ->with('success', 'Component Added Successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(SalaryComponent $salaryComponent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $salaryComponent = SalaryComponent::findOrFail($id);
        $components = SalaryComponent::where('status', '1')->get();

        return view('admin.salary-component.edit', compact('salaryComponent', 'components'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'component_name'     => 'required|string|max:255',
            'component_type'     => 'required|in:Earning,Deduction',
            'calculation_type'   => 'required|in:Fixed,Percentage',
            'based_component_id' => 'nullable|integer|exists:salary_components,id',
            'status'             => 'required|in:0,1',
        ]);

        DB::beginTransaction();
        try {
            $id = decrypt($id);
            $salaryComponent = SalaryComponent::findOrFail($id);
            $validated['updated_by'] = auth()->id();

            $salaryComponent->update($validated);

            DB::commit();
            return redirect()
                ->route('salary-component.index')
                ->with('success', 'Salary Component updated successfully.');
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
    public function destroy(SalaryComponent $salaryComponent)
    {
        //
    }
}
