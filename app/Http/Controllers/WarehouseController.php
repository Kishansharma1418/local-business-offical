<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;
use App\Models\{State,Branch};
use App\Models\City;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $warehouses = Warehouse::query()->orderBy('id','DESC');;
        if ($request->warehouse_name) {
        $warehouses->where('warehouse_name', 'LIKE', '%' . $request->warehouse_name . '%');
          }
            return DataTables::of($warehouses)
                ->addIndexColumn()
                ->editColumn('is_active', function ($row) {
                    return $row->IsActive
                        ? '<span class="badge bg-success px-2 py-1">Active</span>'
                        : '<span class="badge bg-danger px-2 py-1">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    return view('admin.warehouse.action', compact('row'))->render();
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }
$warehouse = Warehouse::select('warehouse_name')->groupBy('warehouse_name')->get();
        return view('admin.warehouse.index',compact('warehouse'));
    }

    public function create()
    {
        $countries = Country::orderBy('name','ASC')->get();
        $branches = Branch::select('id','branch_name')->where('status','Active')->get();

        return view('admin.warehouse.create', compact('countries','branches'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|max:50|unique:warehouses,code',
                'warehouse_name' => 'required|string|max:150',
                'branch_id' => 'required|numeric',
                'warehouse_purpose' => 'required|in:GeneralStorage,Quarantine,Testing,Dispatch,ColdStorage,Returns,Sampling',
                'material_type' => 'required|in:RawMaterial,PackagingMaterial,FinishedGood,SemiFinishedGood,All',
                'email' => 'nullable|email|max:100',
                'pincode' => 'nullable|string|max:10',
            ]);

            $warehouse = Warehouse::create([
                'code' => $request->code,
                'warehouse_name' => $request->warehouse_name,
                'branch_id' => $request->branch_id,
                'warehouse_purpose' => $request->warehouse_purpose,
                'material_type' => $request->material_type,
                'is_default_warehouse' => $request->boolean('is_default_warehouse', false),
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city_id' => $request->city_id,
                'state_id' => $request->state_id,
                'country_id' => $request->country_id,
                'pincode' => $request->pincode,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'contact_person' => $request->contact_person,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'temperature_controlled' => $request->temperature_controlled,
                'temperature_range_min' => $request->temperature_range_min,
                'temperature_range_max' => $request->temperature_range_max,
                'storage_conditions' => $request->storage_conditions,
                 'is_active' => $request->boolean('is_active'),
               
            ]);

            return redirect()->route('warehouse.index')->with('success', 'Warehouse created successfully!');
        }catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])->withInput();
        }
    }

    /**
     * Show the specified warehouse details (for edit modal).
     */
    public function edit($id)
    {
        try {
            $id = decrypt($id); 
            $warehouse = Warehouse::findOrFail($id);
            $countries = Country::orderBy('name','ASC')->get();
            $states = State::where('country_id', $warehouse->country_id)->orderBy('name')->get();
            $cities = City::where('state_id', $warehouse->state_id)->orderBy('name')->get();
            $branches = Branch::select('id','branch_name')->where('status','Active')->get();


            return view('admin.warehouse.edit', compact('warehouse','countries','states','cities','branches'));
        } catch (Exception $e) {
            return redirect()->route('warehouse.index')->with('error', 'Something went wrong: '.$e->getMessage());
        }
    }

    /**
     * Update the specified warehouse in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            
            $warehouse = Warehouse::findOrFail($id);
            $request->validate([
                'code' => 'required|string|max:50|unique:warehouses,code,'.$id,
                'warehouse_name' => 'required|string|max:150',
                'branch_id' => 'required|numeric',
                'warehouse_purpose' => 'required|in:GeneralStorage,Quarantine,Testing,Dispatch,ColdStorage,Returns,Sampling',
                'material_type' => 'required|in:RawMaterial,PackagingMaterial,FinishedGood,SemiFinishedGood,All',
                'email' => 'nullable|email|max:100',
                'pincode' => 'nullable|string|max:10',
            ]);

            $warehouse->update([
                'code' => $request->code,
                'warehouse_name' => $request->warehouse_name,
                'branch_id' => $request->branch_id,
                'warehouse_purpose' => $request->warehouse_purpose,
                'material_type' => $request->material_type,
                'is_default_warehouse' => $request->boolean('is_default_warehouse', false),
                'addressline1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city_id' => $request->city_id,
                'state_id' => $request->state_id,
                'country_id' => $request->country_id,
                'pincode' => $request->pincode,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'contact_person' => $request->contact_person,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'temperature_controlled' => $request->temperature_controlled,
                'temperatu_rerange_min' => $request->temperature_range_min,
                'temperature_range_max' => $request->temperature_range_max,
                'storage_conditions' => $request->storage_conditions,
                'is_active' => $request->boolean('is_active', true),
                'updatedby' => Auth::id() ?? 1,
            ]);

           return redirect()->route('warehouse.index')->with('success', 'Warehouse updated successfully');
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $id = decrypt($id);
        $warehouse = Warehouse::with(['branch', 'country', 'state', 'city'])->findOrFail($id);

        return view('admin.warehouse.show', compact('warehouse'));
    }


    /**
     * Remove the specified warehouse from storage.
     */
    public function destroy($id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);
            $warehouse->delete();

            return response()->json(['success' => true, 'message' => 'Warehouse deleted successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
        }
    }
}
