<?php

namespace App\Http\Controllers;

use App\Models\{User, Branch, CustomerAddress, Country, CustomerContact, FinishedGood, CustomerProductDiscount, State, City, PaymentTerms, CreditNote, DebitNote, InvoiceOrder, SalesOrder, Payment};
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Customer::withCount([
                'invoices as due_invoices_count' => function ($q) {
                    $q->where('payment_status', '!=', 'Paid');
                }
            ])
                ->with('branches')
                ->orderBy('id', 'DESC');

            if ($request->name) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            }

            if ($request->branch_id) {
                $query->where('branch_id', $request->branch_id);
            }

            $query->get();

            return Datatables::of($query)
                ->addIndexColumn()
                ->filterColumn('user', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q
                            ->where('name', 'like', "%{$keyword}%")
                            ->orWhere('code', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhere('mobile_no', 'like', "%{$keyword}%")
                            ->orWhereHas('branches', function ($b) use ($keyword) {
                                $b->where('branch_name', 'like', "%{$keyword}%");
                            });
                    });
                })
             ->editColumn('created_at', fn($row) => formatDate($row->created_at))
                ->editcolumn('branch_id', function ($row) {
                    return $row->branches ? $row->branches->branch_name : '-';
                })
                ->addColumn('user', function ($row) {
                    $user = '<div class="d-flex align-items-center " style="gap:15px;">';

                    $user .= '   <div class="flex-grow-1">';

                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Name:</strong> ' . ($row->name ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Customer Code:</strong> ' . ($row->code ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Email:</strong> ' . ($row->email ?? '-') . '</p>';
                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Phone:</strong> ' . ($row->mobile_no ?? '-') . '</p>';
                    $user .= '      <p class="mb-0" style="font-size:13px;color:#666;">
                                        <strong>Branch:</strong> ' . ($row->branches->branch_name ?? '-') . '
                                    </p>';
                    $user .= '   </div>';
                    $user .= '</div>';

                    return $user;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $type = 'action';

                    return view('admin.customer.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action', 'status', 'user'])
                ->make(true);
        }
        $branches = Branch::where('status', 'Active')->get();

        $query = Customer::select('name')->groupBy('name')->get();
        return view('admin.customer.index', compact('query', 'branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::select('id', 'branch_name')->where('status', 'Active')->get();
        $state = State::select('id', 'name', 'iso2')->where('country_id', '101')->get();
        $countries = Country::select('id', 'name')->get();
        $paymentTerms = PaymentTerms::where('status', 1)->get();
        return view('admin.customer.create', compact('branches', 'state', 'countries', 'paymentTerms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email',
            'mobile_no' => 'required|digits:10|unique:customers,mobile_no',
            'code' => 'required|string|max:50|unique:customers,code',
            'contact_person' => 'required|string|max:255',
            'branch_id' => 'nullable|exists:branches,id',
            'customer_type' => 'required|string|in:Doctor,Chemist,Distributor,Stockist,Hospital,Other',
            'gst_no' => [
                'nullable',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'
            ],
            'pan_no' => [
                'nullable',
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'
            ],
            'payment_terms_id' => 'nullable|exists:payment_terms,id',
            'credit_limit' => 'nullable|numeric|min:0',
            'credit_days' => 'nullable|numeric|min:0',
            'is_blocked' => 'required|boolean',
            'blocked_reason' => 'nullable|string|max:500',
            'is_login' => 'required|boolean',
            'status' => 'required|boolean',
        ]);

        DB::beginTransaction();

        try {
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->mobile_no = $request->mobile_no;
            $customer->code = strtoupper($request->code);
            $customer->contact_person = $request->contact_person;
            $customer->branch_id = $request->branch_id;
            $customer->payment_terms_id = $request->payment_terms_id;
            $customer->customer_type = $request->customer_type;
            $customer->gst_no = strtoupper($request->gst_no);
            $customer->gst_type = $request->gst_type;
            $customer->state_id = $request->state_id;
            $customer->pan_no = strtoupper($request->pan_no);
            $customer->credit_limit = $request->credit_limit ?? 0;
            $customer->credit_days = $request->credit_days ?? 0;
            $customer->payment_terms_id = $request->payment_terms_id;
            $customer->is_blocked = $request->is_blocked;
            $customer->blocked_reason = $request->is_blocked ? $request->blocked_reason : null;
            $customer->is_login = $request->is_login;
            $customer->status = $request->status;
            $customer->created_by = auth()->id();
            $customer->save();

            if ($request->has('addresses')) {
                foreach ($request->addresses as $address) {
                    CustomerAddress::create([
                        'customer_id' => $customer->id,
                        'address_type' => $address['address_type'],
                        'country_id' => $address['country_id'],
                        'state_id' => $address['state_id'],
                        'city_id' => $address['city_id'],
                        'address_line1' => $address['address_line1'],
                        'address_line2' => $address['address_line2'] ?? null,
                        'pincode' => $address['pincode'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('customers.index')->with('success', 'Customer created with address successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $th->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $id = decrypt($id);
        $customer = Customer::with('branches', 'createdBy', 'updatedBy', 'getCustomerAddress', 'paymentTerm', 'states')->findOrFail($id);
        return view('admin.customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = decrypt($id);

        $customer = Customer::with([
            'addresses.states',
            'addresses.cities'
        ])->findOrFail($id);

        $branches = Branch::select('id', 'branch_name')
            ->where('status', 'Active')
            ->get();

        $countries = Country::select('id', 'name')
            ->orderBy('name', 'ASC')
            ->get();

        $gst_states = State::select('id', 'name', 'iso2')
            ->where('country_id', 101)
            ->orderBy('name', 'ASC')
            ->get();
        $paymentTerms = PaymentTerms::where('status', 1)->get();
        return view('admin.customer.edit', compact(
            'customer',
            'branches',
            'countries',
            'gst_states',
            'paymentTerms'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'code' => 'required|string|max:255|unique:customers,code,' . $customer->id,
            'mobile_no' => 'required|string|max:255|unique:customers,mobile_no,' . $customer->id,
            'contact_person' => 'required|string|max:255',
            'branch_id' => 'nullable|exists:branches,id',
            'customer_type' => 'required|string|in:Doctor,Chemist,Distributor,Stockist,Hospital,Other',
            'gst_no' => [
                'nullable',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'
            ],
            'pan_no' => [
                'nullable',
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'
            ],
            'payment_terms_id' => 'nullable|exists:payment_terms,id',
            'credit_limit' => 'nullable|numeric|min:0',
            'credit_days' => 'nullable|numeric|min:0',
            'is_blocked' => 'required|boolean',
            'blocked_reason' => 'nullable|string|max:500',
            'is_login' => 'required|boolean',
            'status' => 'required|boolean',
        ]);

        DB::beginTransaction();

        try {
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->mobile_no = $request->mobile_no;
            $customer->code = strtoupper($request->code);
            $customer->contact_person = $request->contact_person;
            $customer->branch_id = $request->branch_id;
            $customer->payment_terms_id = $request->payment_terms_id;
            $customer->customer_type = $request->customer_type;
            $customer->gst_no = strtoupper($request->gst_no);
            $customer->gst_type = $request->gst_type;
            $customer->state_id = $request->state_id;
            $customer->pan_no = strtoupper($request->pan_no);
            $customer->credit_limit = $request->credit_limit ?? 0;
            $customer->credit_days = $request->credit_days ?? 0;
            $customer->payment_terms_id = $request->payment_terms_id;
            $customer->is_blocked = $request->is_blocked;
            $customer->blocked_reason = $request->is_blocked ? $request->blocked_reason : null;
            $customer->is_login = $request->is_login;
            $customer->status = $request->status;
            $customer->updated_by = auth()->id();
            $customer->save();

            if ($request->has('addresses')) {
                foreach ($request->addresses as $addr) {
                    CustomerAddress::updateOrCreate(
                        [
                            'id' => $addr['id'] ?? null,
                            'customer_id' => $customer->id
                        ],
                        [
                            'address_type' => $addr['address_type'] ?? 'Billing',
                            'address_line1' => $addr['address_line1'],
                            'address_line2' => $addr['address_line2'] ?? null,
                            'country_id' => $addr['country_id'],
                            'state_id' => $addr['state_id'] ?? null,
                            'city_id' => $addr['city_id'] ?? null,
                            'pincode' => $addr['pincode'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }
            }

            DB::commit();

            return redirect()
                ->route('customers.index')
                ->with('success', 'Customer updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $th->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json(['success' => true, 'message' => 'Customer deleted successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function showCustomerAddressForm($customer_id)
    {
        $customer_id = decrypt($customer_id);

        $customer = Customer::find($customer_id);
        $countries = Country::select('id', 'name')->get();
        $customerAddress = CustomerAddress::where('customer_id', $customer_id)->first();

        return view('admin.customer.customer-address.create', compact('customer_id', 'customer', 'customerAddress', 'countries'));
    }

    public function storeCustomerAddress(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'address_title' => 'nullable|string|max:255',
            'address_type' => 'nullable|string|max:100',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city_id' => 'required|integer|exists:cities,id',
            'state_id' => 'required|integer|exists:states,id',
            'country_id' => 'required|integer|exists:countries,id',
            'pincode' => 'nullable|digits:6',
            'is_default' => 'nullable|boolean',
        ]);

        $customer = Customer::find($request->customer_id);
        if (!$customer) {
            return redirect()->back()->with('error', 'Customer not found.');
        }

        $data = $request->only([
            'customer_id',
            'address_title',
            'address_type',
            'address_line1',
            'address_line2',
            'city_id',
            'state_id',
            'country_id',
            'pincode',
            'is_default',
        ]);

        $data['created_by'] = auth()->id() ?? null;
        $data['updated_by'] = auth()->id() ?? null;

        $existing = CustomerAddress::where('customer_id', $request->customer_id)->first();

        if ($existing) {
            $existing->update($data);
            $message = 'Customer address updated successfully.';
        } else {
            CustomerAddress::create($data);
            $message = 'Customer address added successfully.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function showCustomerContactlist(Request $request, $customer_id)
    {
        if ($request->ajax()) {
            $query = CustomerContact::where('customer_id', $customer_id)->orderBy('id', 'DESC');

            return Datatables::of($query)
                ->addIndexColumn()
                ->filterColumn('user', function ($query, $keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q
                            ->where('contact_name', 'LIKE', "%{$keyword}%")
                            ->orWhere('designation', 'LIKE', "%{$keyword}%")
                            ->orWhere('email', 'LIKE', "%{$keyword}%")
                            ->orWhere('mobile_no', 'LIKE', "%{$keyword}%");
                    });
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M, Y') : '-';
                })
                ->addColumn('user', function ($row) {
                    $user = '<div class="d-flex align-items-center " style="gap:15px;">';

                    $user .= '   <div class="flex-grow-1">';

                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Name:</strong> ' . ($row->contact_name ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Designation:</strong> ' . ($row->designation ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Email:</strong> ' . ($row->email ?? '-') . '</p>';
                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Phone:</strong> ' . ($row->mobile_no ?? '-') . '</p>';
                    $user .= '   </div>';
                    $user .= '</div>';

                    return $user;
                })
                ->editColumn('is_default', function ($row) {
                    if ($row->is_default == 1) {
                        return '<span class="badge bg-success">Yes</span>';
                    } else {
                        return '<span class="badge bg-danger">No</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $type = 'action';
                    return view('admin.customer.customer-contact.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action', 'is_default', 'user'])
                ->make(true);
        }

        return view('admin.customer.customer-contact.index', compact('customer_id'));
    }

    public function showCustomerContactForm($customer_id)
    {
        $customer_id = decrypt($customer_id);

        return view('admin.customer.customer-contact.create', compact('customer_id'));
    }

    public function showCustomerContactStore(Request $request)
    {
        $customer_id = decrypt($request->customer_id);

        $request->validate([
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_no' => 'required|digits:10',
            'designation' => 'nullable|string|max:255',
            'is_default' => 'nullable|boolean',
        ]);

        $customer = Customer::find($customer_id);
        if (!$customer) {
            return redirect()->back()->with('error', 'Customer not found.');
        }

        if ($request->is_default == 1) {
            CustomerContact::where('customer_id', $customer_id)
                ->update(['is_default' => '0']);
        }

        $data = [
            'customer_id' => $customer_id,
            'contact_name' => $request->contact_name,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'designation' => $request->designation,
            'is_default' => $request->is_default == 1 ? '1' : '0',
            'created_by' => auth()->id(),
        ];

        CustomerContact::create($data);

        return redirect()
            ->route('customer.contactlist.index', $customer_id)
            ->with('success', 'Customer contact added successfully.');
    }

    public function showCustomerContactEditForm($customer_id)
    {
        $customer_id = decrypt($customer_id);
        $contact = CustomerContact::findOrFail($customer_id);

        return view('admin.customer.customer-contact.edit', compact('customer_id', 'contact'));
    }

    public function showCustomerContactUpdate(Request $request, $contact_id)
    {
        $request->validate([
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_no' => 'required|digits:10',
            'designation' => 'nullable|string|max:255',
            'is_default' => 'nullable|boolean',
        ]);

        $contact = CustomerContact::findOrFail($contact_id);

        if ($request->is_default == '1') {
            CustomerContact::where('customer_id', $contact->customer_id)
                ->where('id', '!=', $contact->id)
                ->update(['is_default' => '0']);
        }

        $contact->update([
            'contact_name' => $request->contact_name,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'designation' => $request->designation,
            'is_default' => $request->is_default ?? '0',
        ]);

        return redirect()
            ->route('customer.contactlist.index', $contact->customer_id)
            ->with('success', 'Customer contact updated successfully.');
    }

    public function delete($customer_id)
    {
        try {
            $contact = CustomerContact::findOrFail($customer_id);
            $contact->delete();

            return response()->json(['success' => true, 'message' => 'Customer Contact deleted successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function showCustomerCreateProductDiscount($customer_id)
    {
        $customer_id = decrypt($customer_id);

        $customer = Customer::findOrFail($customer_id);
        $products = FinishedGood::select('id', 'name', 'code')->get();

        $discounts = CustomerProductDiscount::where('customer_id', $customer_id)
            ->get()
            ->keyBy('finish_goods_id');

        $overallDiscount = CustomerProductDiscount::where('customer_id', $customer_id)
            ->where('discount_type', 'overall')
            ->first();

        return view('admin.customer.customer-discount.create', compact(
            'customer_id', 'customer', 'products', 'discounts', 'overallDiscount'
        ));
    }

    public function storeCustomerProductDiscount(Request $request)
    {
        $customer_id = $request->customer_id;

        if ($request->ajax() && $request->has('product_id')) {
            $productId = $request->product_id;
            $percent = $request->discount_percent;

            if ($percent !== null && $percent !== '') {
                CustomerProductDiscount::updateOrCreate(
                    [
                        'customer_id' => $customer_id,
                        'finish_goods_id' => $productId,
                    ],
                    [
                        'discount_percent' => $percent,
                        'discount_type' => 'specific',
                    ]
                );
            } else {
                CustomerProductDiscount::where('customer_id', $customer_id)
                    ->where('finish_goods_id', $productId)
                    ->delete();
            }

            return response()->json(['success' => true, 'message' => 'Discount updated successfully']);
        }

        $discounts = $request->input('discounts', []);
        $overallDiscount = $request->input('overall_discount');

        if (!empty($discounts)) {
            foreach ($discounts as $productId => $percent) {
                if ($percent !== null && $percent !== '') {
                    CustomerProductDiscount::updateOrCreate(
                        [
                            'customer_id' => $customer_id,
                            'finish_goods_id' => $productId,
                        ],
                        [
                            'discount_percent' => $percent,
                            'discount_type' => 'specific',
                        ]
                    );
                }
            }
        }

        if ($overallDiscount !== null && $overallDiscount !== '') {
            CustomerProductDiscount::updateOrCreate(
                [
                    'customer_id' => $customer_id,
                    'discount_type' => 'overall',
                ],
                [
                    'discount_percent' => $overallDiscount,
                    'finish_goods_id' => null,
                ]
            );

            $products = FinishedGood::select('id')->get();
            foreach ($products as $product) {
                CustomerProductDiscount::updateOrCreate(
                    [
                        'customer_id' => $customer_id,
                        'finish_goods_id' => $product->id,
                    ],
                    [
                        'discount_percent' => $overallDiscount,
                        'discount_type' => 'specific',
                    ]
                );
            }
        }

        return back()->with('success', 'Customer discounts saved successfully.');
    }

    public function downloadPdf(Customer $customer)
    {
        $invoices = InvoiceOrder::with('payments')
            ->where('customer_id', $customer->id)
            ->where('payment_status', '!=', 'Paid')
            // ->where('balance_amount', '>=', 0)
            ->orderBy('date')
            ->get();

        $ledger = $invoices->map(function ($invoice) {
            $totalReceived = $invoice
                ->payments()
                ->selectRaw('SUM(amount_paid + amount_withheld) as total')
                ->value('total');

            $received = $totalReceived ?? 0;
            $invoiceAmount = $invoice->net_amount;
            $due = $invoiceAmount - $received;

            return [
                'date' => $invoice->date,
                'invoice_no' => $invoice->code,
                'invoice_amount' => $invoiceAmount,
                'received_amount' => $received,
                'due_amount' => $due,
            ];
        });

        return $this->generatePdf($customer, $ledger);
    }

    private function generatePdf(Customer $customer, $ledger)
    {
        $totalInvoice = $ledger->sum('invoice_amount');
        $totalReceived = $ledger->sum('received_amount');
        $totalDue = $ledger->sum('due_amount');

        $pdf = Pdf::loadView(
            'admin.customer.pdf',
            compact(
                'customer',
                'ledger',
                'totalInvoice',
                'totalReceived',
                'totalDue'
            )
        );

        return $pdf->download(
            'Customer-Invoice-Ledger-' . $customer->code . '.pdf'
        );
    }
}
