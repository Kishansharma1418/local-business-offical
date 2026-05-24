<?php

namespace App\Http\Controllers;

use App\Models\{Country, State, City};
use App\Models\Broker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Exception;

class BrokerController extends Controller
{
    /* ================= INDEX ================= */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $brokers = Broker::with(['city', 'state'])
                ->orderBy('id', 'DESC');

            // 🔍 Filter by broker name
            if ($request->broker_name) {
                $brokers->where('broker_name', 'LIKE', '%' . $request->broker_name . '%');
            }

            return DataTables::of($brokers)
                ->filterColumn('broker_info', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        // 🔹 Broker basic fields
                        $q
                            ->where('broker_name', 'LIKE', "%{$keyword}%")
                            ->orWhere('code', 'LIKE', "%{$keyword}%")
                            ->orWhere('contact_person', 'LIKE', "%{$keyword}%")
                            ->orWhere('mobile_no', 'LIKE', "%{$keyword}%")
                            ->orWhere('email', 'LIKE', "%{$keyword}%")
                            // 🔹 Location relations
                            ->orWhereHas('city', function ($c) use ($keyword) {
                                $c->where('name', 'LIKE', "%{$keyword}%");
                            })
                            ->orWhereHas('state', function ($s) use ($keyword) {
                                $s->where('name', 'LIKE', "%{$keyword}%");
                            })
                            ->orWhereHas('country', function ($co) use ($keyword) {
                                $co->where('name', 'LIKE', "%{$keyword}%");
                            })
                            // 🔹 Commission
                            ->orWhere('commission_type', 'LIKE', "%{$keyword}%")
                            ->orWhere('commission_value', 'LIKE', "%{$keyword}%")
                            // 🔹 Status
                            ->orWhere('status', 'LIKE', "%{$keyword}%")
                            // 🔹 Created date (text search)
                            ->orWhereRaw("DATE_FORMAT(created_at, '%d %b %Y') LIKE ?", ["%{$keyword}%"]);
                    });
                })
                /* ================= BROKER INFO COLUMN ================= */
                ->addColumn('broker_info', function ($row) {
                    $html = '<div class="d-flex align-items-start" style="gap:15px;">';
                    $html .= '  <div class="flex-grow-1">';

                    $html .= '      <h6 class="mb-1" style="font-size:16px;font-weight:600;color:#333;">'
                        . ($row->broker_name ?? 'N/A')
                        . '</h6>';

                    $html .= '      <p class="mb-1" style="font-size:13px;color:#666;">
                                <strong>Broker Code:</strong> ' . ($row->code ?? '-') . '
                              </p>';

                    $html .= '      <p class="mb-1" style="font-size:13px;color:#666;">
                                <strong>Contact Person:</strong> ' . ($row->contact_person ?? '-') . '
                              </p>';

                    $html .= '      <p class="mb-1" style="font-size:13px;color:#666;">
                                <strong>Mobile:</strong> ' . ($row->mobile_no ?? '-') . '
                              </p>';

                    $html .= '      <p class="mb-1" style="font-size:13px;color:#666;">
                                <strong>Email:</strong> ' . ($row->email ?? '-') . '
                              </p>';

                    $html .= '      <p class="mb-1" style="font-size:13px;color:#666;">
                                <strong>Address:</strong> '
                        . ($row->city->name ?? '-') . ', '
                        . ($row->state->name ?? '-') . ','
                        . ($row->country->name ?? '-')
                        . '</p>';

                    $html .= '  </div>';
                    $html .= '</div>';

                    return $html;
                })
               ->editColumn('created_at', fn($row) => formatDate($row->created_at))
                /* ================= STATUS COLUMN ================= */
                ->addColumn('commission', function ($row) {
                    if ($row->commission_type === 'Percentage') {
                        return '
                        <div style="font-size:15px;color:#666;">
                            <div><strong>' . $row->commission_type . '</strong></div>
                            <div>' . $row->commission_value . '%</div>
                        </div>
                    ';
                    }

                    return '
                    <div style="font-size:15px;color:#666;">
                        <div><strong>' . $row->commission_type . '</strong></div>
                        <div>₹ ' . number_format($row->commission_value, 2) . '</div>
                    </div>
                ';
                })
                ->editColumn('status', function ($row) {
                    return $row->status === 'Active'
                        ? '<span class="badge bg-success px-2 py-1">Active</span>'
                        : '<span class="badge bg-danger px-2 py-1">Inactive</span>';
                })
                /* ================= ACTION COLUMN ================= */
                ->addColumn('action', function ($row) {
                    return view('admin.brokers.action', compact('row'))->render();
                })
                ->rawColumns(['broker_info', 'status', 'action', 'commission'])
                ->make(true);
        }

        /* ================= NORMAL PAGE LOAD ================= */
        $brokers = Broker::select('broker_name')
            ->groupBy('broker_name')
            ->orderBy('broker_name')
            ->get();

        return view('admin.brokers.index', compact('brokers'));
    }

    /* ================= CREATE ================= */
    public function create()
    {
        $countries = Country::select('id', 'name')->get();
        return view('admin.brokers.create', compact('countries'));
    }

    /* ================= STORE ================= */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|unique:brokers,code|min:3',
                'broker_name' => 'required|string|max:255',
                'mobile_no' => 'nullable|regex:/^[0-9+\-\s]{7,20}$/|unique:brokers,mobile_no',
                'email' => 'nullable|email|max:150|unique:brokers,email',
                'pan_number' => 'required|string|max:20',
                'address_line1' => 'required|string|max:255',
                'commission_type' => 'required|in:Percentage,Fixed',
                'commission_value' => 'required|numeric|min:0',
                'status' => 'required|in:Active,Inactive',
            ]);

            Broker::create([
                'code' => $request->code,
                'broker_name' => $request->broker_name,
                'contact_person' => $request->contact_person,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'gst_number' => $request->gst_number,
                'pan_number' => $request->pan_number,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city_id' => $request->city_id,
                'state_id' => $request->state_id,
                'country_id' => $request->country_id,
                'pincode' => $request->pincode,
                'commission_type' => $request->commission_type,
                'commission_value' => $request->commission_value,
                'status' => $request->status,
                'remarks' => $request->remarks,
            ]);

            return redirect()
                ->route('brokers.index')
                ->with('success', 'Broker created successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])
                ->withInput();
        }
    }

    /* ================= EDIT ================= */
    public function edit($id)
    {
        try {
            $id = decrypt($id);
            $countries = Country::select('id', 'name')->get();
            $broker = Broker::findOrFail($id);

            return view('admin.brokers.edit', compact('broker', 'countries'));
        } catch (Exception $e) {
            return redirect()
                ->route('brokers.index')
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /* ================= UPDATE ================= */
    public function update(Request $request, $id)
    {
        try {
            $id = decrypt($id);
            $broker = Broker::findOrFail($id);

            $request->validate([
                'code' => 'required|min:3|unique:brokers,code,' . $broker->id,
                'broker_name' => 'required|string|max:255',
                'mobile_no' => 'nullable|regex:/^[0-9+\-\s]{7,20}$/|unique:brokers,mobile_no,' . $broker->id,
                'email' => 'nullable|email|max:150|unique:brokers,email,' . $broker->id,
                'pan_number' => 'required|string|max:20',
                'address_line1' => 'required|string|max:255',
                'commission_type' => 'required|in:Percentage,Fixed',
                'commission_value' => 'required|numeric|min:0',
                'status' => 'required|in:Active,Inactive',
            ]);

            $broker->update([
                'code' => $request->code,
                'broker_name' => $request->broker_name,
                'contact_person' => $request->contact_person,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'gst_number' => $request->gst_number,
                'pan_number' => $request->pan_number,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city_id' => $request->city_id,
                'state_id' => $request->state_id,
                'country_id' => $request->country_id,
                'pincode' => $request->pincode,
                'commission_type' => $request->commission_type,
                'commission_value' => $request->commission_value,
                'status' => $request->status,
                'remarks' => $request->remarks,
            ]);

            return redirect()
                ->route('brokers.index')
                ->with('success', 'Broker updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()])
                ->withInput();
        }
    }

    public function show($id)
    {
        $id = decrypt($id);

        $broker = Broker::with([
            'country',
            'state',
            'city',
        ])->findOrFail($id);

        return view('admin.brokers.show', compact('broker'));
    }

    /* ================= DESTROY ================= */
    public function destroy($id)
    {
        try {
            $broker = Broker::findOrFail($id);
            $broker->delete();

            return response()->json([
                'success' => true,
                'message' => 'Broker deleted successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
