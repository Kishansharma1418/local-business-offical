<?php

namespace App\Http\Controllers;

use App\Models\{Vendor,PaymentTerms,Country};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $query = Vendor::orderBy('id','DESC');
           
            if ($request->value) {
                $search = $request->value;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            }

            $query->get();

            return Datatables::of($query)
                ->addIndexColumn()
 ->filterColumn('user', function ($query, $keyword) {

    $query->where(function ($q) use ($keyword) {

        // 🔹 Vendor table columns
        $q->where('name', 'LIKE', "%{$keyword}%")
          ->orWhere('code', 'LIKE', "%{$keyword}%")
          ->orWhere('email', 'LIKE', "%{$keyword}%")
          ->orWhere('phone', 'LIKE', "%{$keyword}%")
          ->orWhere('vendor_type', 'LIKE', "%{$keyword}%")

          // 🔹 Created By (relation search)
          ->orWhereHas('createdBy', function ($cb) use ($keyword) {
              $cb->where('full_name', 'LIKE', "%{$keyword}%");
          });

    });
})

                 ->addColumn('user', function($row) {

                    $user  = '<div class="d-flex align-items-center " style="gap:15px;">';
                 
                    $user .= '   <div class="flex-grow-1">';
                    $user .= '       <h6 class="mb-1" style="font-size:16px; font-weight:600;color:#333;">'.($row->name ?? 'N/A').'</h6>';
                  
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Vendor Code:</strong> '.($row->code ?? '-').'</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Email:</strong> '.($row->email ?? '-').'</p>';
                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Phone:</strong> '.($row->phone ?? '-').'</p>';

                    $user .= '   </div>';
                    $user .= '</div>';

                    return $user;
                })

                ->addColumn('vendor_type', function ($row) {
                    return ucfirst($row->vendor_type ?? 'N/A');
                })
                ->addColumn('created_by', function ($row) {
                    return $row->createdBy ? $row->createdBy->full_name : 'N/A';
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'active') {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
             
                ->addColumn('action', function ($row) {
                    $type = "action";
                    return view('admin.vendor.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action','status','user'])
                ->make(true);
        }
             
        return view('admin.vendor.index');

     }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paymentTerms = PaymentTerms::where('status', '1')->get();
          $countries = Country::select('id','name')->get();
        return view('admin.vendor.create', compact('paymentTerms', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $validated = $request->validate([
                'code'              => 'required|string|max:255|unique:vendors,code',
                'name'              => 'required|string|max:255',
                'email'             => 'required|email|max:255|unique:vendors,email',
                'phone'             => 'required|digits:10',
                'vendor_type'       => 'required|in:rawmaterial,packaging,service,transport,import,other',
                'contact_person'    => 'nullable|string|max:255',

                'is_gst_registered' => 'required|boolean',
                'gst_no'            => 'nullable|required_if:is_gst_registered,1|string|max:15',

                'pan_no'            => 'nullable|string|max:10',

                'address_line1'     => 'required|string',
                'address_line2'     => 'nullable|string',

                'country_id'        => 'nullable|integer',
                'state_id'          => 'nullable|integer',
                'city_id'           => 'nullable|integer',
                'pincode'           => 'nullable|digits:6',

                'payment_term_id'   => 'required|integer',
                'status'            => 'required|in:active,inactive',

                'remarks'           => 'nullable|string',
            ]);

            Vendor::create([
                'code'              => $validated['code'],
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'phone'             => $validated['phone'],
                'vendor_type'       => $validated['vendor_type'],
                'contact_person'    => $request->contact_person,

                'is_gst_registered' => $validated['is_gst_registered'],
                'gst_no'            => $request->gst_no,

                'pan_no'            => $request->pan_no,

                'address_line1'     => $validated['address_line1'],
                'address_line2'     => $request->address_line2,

                'country_id'        => $request->country_id,
                'state_id'          => $request->state_id,
                'city_id'           => $request->city_id,
                'pincode'           => $request->pincode,

                'payment_term_id'   => $validated['payment_term_id'],
                'status'            => $validated['status'],

                'remarks'           => $request->remarks,

                'created_by'        => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('vendor.index')
                ->with('success', 'Vendor created successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create vendor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vendor = Vendor::with(['countries', 'states', 'cities', 'createdBy', 'updatedBy'])->findOrFail(decrypt($id));
        return view('admin.vendor.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vendor = Vendor::findOrFail(decrypt($id));
        $paymentTerms = PaymentTerms::where('status', '1')->get();
        $countries = Country::select('id','name')->get();
        return view('admin.vendor.edit', compact('vendor', 'paymentTerms', 'countries'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $vendor = Vendor::findOrFail($id);

            $validated = $request->validate([
                'code'              => 'required|string|max:255|unique:vendors,code,' . $vendor->id,
                'name'              => 'required|string|max:255',
                'email'             => 'required|email|max:255|unique:vendors,email,' . $vendor->id,
                'phone'             => 'required|digits:10',
                'vendor_type'       => 'required|in:rawmaterial,packaging,service,transport,import,other',
                'contact_person'    => 'nullable|string|max:255',

                'is_gst_registered' => 'required|boolean',
                'gst_no'            => 'nullable|required_if:is_gst_registered,1|string|max:15',

                'pan_no'            => 'nullable|string|max:10',

                'address_line1'     => 'required|string',
                'address_line2'     => 'nullable|string',

                'country_id'        => 'nullable|integer',
                'state_id'          => 'nullable|integer',
                'city_id'           => 'nullable|integer',
                'pincode'           => 'nullable|digits:6',

                'payment_term_id'   => 'required|integer',
                'status'            => 'required|in:active,inactive',

                'remarks'           => 'nullable|string',
            ]);

            $vendor->update([
                'code'              => $validated['code'],
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'phone'             => $validated['phone'],
                'vendor_type'       => $validated['vendor_type'],
                'contact_person'    => $request->contact_person,

                'is_gst_registered' => $validated['is_gst_registered'],
                'gst_no'            => $validated['is_gst_registered'] ? $request->gst_no : null,

                'pan_no'            => $request->pan_no,

                'address_line1'     => $validated['address_line1'],
                'address_line2'     => $request->address_line2,

                'country_id'        => $request->country_id,
                'state_id'          => $request->state_id,
                'city_id'           => $request->city_id,
                'pincode'           => $request->pincode,

                'payment_term_id'   => $validated['payment_term_id'],
                'status'            => $validated['status'],

                'remarks'           => $request->remarks,

                'updated_by'        => Auth::id(),
            ]);

            DB::commit();

            return redirect()
                ->route('vendor.index')
                ->with('success', 'Vendor updated successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update vendor: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        //
    }
}