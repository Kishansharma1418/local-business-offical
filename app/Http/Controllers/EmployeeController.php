<?php

namespace App\Http\Controllers;

use App\Models\{
    Employee,
    Country,
    State,
    City,
    User,
    Branch,
    Department,
    EmployeBank,
    BankDetail,
    EmployeeDocument,
    Designation,
    EmployeeAssets,
    EmployeeSalary,
    SalaryComponent,
    EmployeeSalaryRevision,
    EmployeeSalaryComponent,
    EmployeeAllowance
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Exports\EmployeeExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeeImport;
use App\Exports\EmployeeDataExport;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{


    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = Employee::orderBy('id', 'DESC');
            if (!auth()->user()->hasRole('admin')) {
                $query->where('id', auth()->user()->reference_id);
            }
            // if ($request->full_name) {
            //     $query->where('full_name', 'LIKE', '%' . $request->full_name . '%');
            // }

            if ($request->role) {
                $query->where('role', $request->role);
            }

            if ($request->branch_id) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->department_id) {
                $query->where('department_id', $request->department_id);
            }

            if ($request->designation_id) {
                $query->where('designation_id', $request->designation_id);
            }
            if ($request->role_id) {
                $query->where('role_id', $request->role_id);
            }

            if ($request->reporting_id) {
                $query->where('reporting_id', $request->reporting_id);
            }

            if ($request->value) {
                $search = $request->value;

                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'LIKE', "%{$search}%")
                        ->orWhere('code', 'LIKE', "%{$search}%")
                        ->orWhere('official_mail', 'LIKE', "%{$search}%")
                        ->orWhere('mobile_no', 'LIKE', "%{$search}%");
                });
            }

            $query->with('branches', 'departments', 'designation', 'employee', 'createdBy', 'roles');
            $query->get();

            return Datatables::of($query)

                ->editColumn('joining_date', function ($row) {
                    return formatDate($row->joining_date);
                })

                ->addColumn('created_by', function ($row) {
                    return $row->createdBy?->full_name ?? 'N/A';
                })


                ->addColumn('user', function ($row) {

                    $user  = '<div class="d-flex align-items-center " style="gap:15px;">';

                    $user .= '   <div class="flex-grow-1">';
                    $user .= '       <h6 class="mb-1" style="font-size:16px; font-weight:600;color:#333;">' . ($row->full_name ?? 'N/A') . '</h6>';

                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Employee Code:</strong> ' . ($row->code ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Email:</strong> ' . ($row->official_mail ?? '-') . '</p>';
                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Phone:</strong> ' . ($row->mobile_no ?? '-') . '</p>';
                    $user .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Branch:</strong> ' . ($row->branches->branch_name ?? 'N/A') . '</p>';

                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Type:</strong> ' . ($row->role ?? 'N/A') . '</p>';
                    $user .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Role:</strong> ' . ($row->roles->name ?? 'N/A') . '</p>';

                    $user .= '   </div>';
                    $user .= '</div>';

                    return $user;
                })

                ->addColumn('details', function ($row) {

                    $details  = '<div class="d-flex align-items-center " style="gap:15px;">';

                    $details .= '   <div class="flex-grow-1">';

                    $details .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Department:</strong> ' . ($row->departments->department_name ?? 'N/A') . '</p>';
                    $details .= '       <p class="mb-1" style="font-size:13px;color:#666;"><strong>Designation:</strong> ' . ($row->designation->name ?? 'N/A') . '</p>';
                    $details .= '       <p class="mb-0" style="font-size:13px;color:#666;"><strong>Reporting Manager:</strong> ' . ($row->employee->full_name ?? 'N/A') . '</p>';

                    $details .= '   </div>';
                    $details .= '</div>';

                    return $details;
                })

                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->editColumn('is_login', function ($row) {
                    if ($row->is_login == 1) {
                        return '<span class="badge bg-success">Yes</span>';
                    } else {
                        return '<span class="badge bg-danger">No</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $type = "action";
                    return view('admin.employee.action', compact('row', 'type'))->render();
                })
                ->rawColumns(['action', 'status', 'user', 'is_login', 'details'])
                ->make(true);
        }
        $branches = Branch::all();
        $departments = Department::all();
        $designations = Designation::all();
        $reporting_managers = Employee::all();
        $employee = Employee::select('full_name')->groupBy('full_name')->get();
        return view('admin.employee.index', compact(
            'employee',
            'branches',
            'departments',
            'designations',
            'reporting_managers'
        ));
    }


    public function create()
    {
        $countries = Country::select('id', 'name')->get();
        $branches = Branch::select('id', 'branch_name')->where('status', 'Active')->get();
        $department = Department::select('id', 'department_name')->where('status', 'Active')->get();
        $designaions = Designation::select('id', 'name')->where('status', '1')->get();
        $employee = Employee::select('id', 'full_name')->get();
        $roles = Role::select('id', 'name')->where('status', 1)->where('slug', '!=', 'admin')->get();
        return view('admin.employee.create', compact('countries', 'branches', 'department', 'designaions', 'roles', 'employee'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            // $validated = $request->validate([
            //     'first_name' => 'required|string|max:255',
            //        'role' => 'required|in:sales,other',
            //     'last_name' => 'required|string|max:255',
            //     'is_login' => 'required|in:0,1',
            //     'code' => 'required|string|max:255|unique:employees,code',

            //     'gender' => 'required|in:Male,Female,Other',
            //     'dob' => 'required|date',
            //     'status' => 'required|in:0,1',
            //     'mobile_no' => [
            //             'required',
            //                 'string',
            //                 'max:10',

            //                 function ($attribute, $value, $fail) use ($request) {
            //                     if ($request->is_login == 1) {                           
            //                         if (\DB::table('users')->where('phone', $value)->where('email',$value)->exists()) {
            //                             $fail('This mobile number is already registered for another user .');
            //                         }

            //                     } else {                             
            //                       if (\DB::table('employees')->where('mobile_no', $value)->exists()) {
            //                             $fail('This mobile number is already registered for another employee.');
            //                         }
            //                     }
            //                 },
            //             ],


            //     // 'country_id' => 'required|exists:countries,id',
            //     // 'state_id' => 'required|exists:states,id',
            //     // 'city_id' => 'required|exists:cities,id',
            // ]);

            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'role' => 'required|in:sales,other',
                'last_name' => 'required|string|max:255',
                'is_login' => 'required|in:0,1',
                'code' => 'required|string|max:255|unique:employees,code',
                'dob' => 'required|date|before_or_equal:' . now()->subYears(15)->format('Y-m-d'),

                'pan_no' => 'required|unique:employees,pan_no',
                'aadhaar_no' => 'required|digits:12|unique:employees,aadhaar_no',

                'official_mail' => [
                    'nullable',
                    'email',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->is_login == 1) {
                            if (\DB::table('users')->where('email', $value)->exists()) {
                                $fail('This email is already registered.');
                            }
                        }
                    }
                ],

                'mobile_no' => [
                    'nullable',
                    'digits:10',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->is_login == 1) {
                            if (\DB::table('users')->where('phone', $value)->exists()) {
                                $fail('This mobile number is already registered.');
                            }
                        } else {
                            if (\DB::table('employees')->where('mobile_no', $value)->exists()) {
                                $fail('This mobile number is already registered for another employee.');
                            }
                        }
                    }
                ],
            ]);
            // At least one of email or mobile is required
            if (empty($request->official_mail) && empty($request->mobile_no)) {
                return back()
                    ->withInput()
                    ->withErrors(['mobile_no' => 'Please enter either Official Email or Mobile No.']);
            }

            if ($request->hasFile('employee_image')) {
                $file = $request->file('employee_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/employee_images', $filename, 'public');
                $request->merge(['employee_image' => $filePath]);
            }

            $employee = new Employee();
            $employee->role = $request->role;
            $employee->first_name = $request->first_name;
            $employee->middle_name = $request->middle_name;
            $employee->last_name = $request->last_name;
            $employee->full_name = $request->first_name . ' ' . ($request->middle_name ? $request->middle_name . ' ' : '') . $request->last_name;
            $employee->code = $request->code;
            $employee->official_mail = $request->official_mail;
            $employee->personal_mail = $request->personal_mail;
            $employee->mobile_no = $request->mobile_no;
            $employee->employee_image = $filePath ?? null;
            $employee->alternative_no = $request->alternative_no;
            $employee->gender = $request->gender;
            $employee->dob = $request->dob;
            $employee->fathers_name = $request->fathers_name;
            $employee->sales_head = $request->sales_head;
            $employee->joining_date = $request->joining_date;
            $employee->branch_id = $request->branch_id;
            $employee->department_id = $request->department_id;
            $employee->designation_id = $request->designation_id;
            $employee->reporting_id = $request->reporting_id;
            $employee->role_id = $request->role_id;
            $employee->territory_id = $request->territory_id;
            $employee->country_id = $request->country_id;
            $employee->state_id = $request->state_id;
            $employee->city_id = $request->city_id;
            $employee->address_line1 = $request->address_line1;
            $employee->address_line2 = $request->address_line2;
            $employee->pincode = $request->pincode;
            $employee->marital_status = $request->marital_status;
            $employee->blood_group = $request->blood_group;
            $employee->emergancy_contact_name = $request->emergancy_contact_name;
            $employee->emergancy_contact_number = $request->emergancy_contact_number;
            $employee->employee_type = $request->employee_type;
            $employee->pan_no = $request->pan_no;
            $employee->aadhaar_no = $request->aadhaar_no;
            $employee->uan_no = $request->uan_no;
            $employee->is_login = $request->is_login ?? 0;
            $employee->status = $request->status;
            $employee->relieving_date = $request->relieving_date;
            $employee->separation_type = $request->separation_type;
            $employee->separation_remarks = $request->separation_remarks;
            $employee->relieving_approvel_date = $request->relieving_approvel_date;
            $employee->relieving_approved_by  = auth()->user()->id;
            $employee->pf_aplicable = $request->pf_aplicable ?? '0';
            $employee->esi_aplicable = $request->esi_aplicable ?? '0';
            $employee->pf_number = $request->pf_number;
            $employee->esi_number = $request->esi_number;
            // $employee->created_by = auth()->user()->id;
            $employee->save();


            //  if ($request->is_login == '1') {
            //         $savedUser = User::create([
            //             'full_name' => $employee->full_name,
            //             'email' => $employee->official_mail ?? null,
            //             'phone' => $employee->mobile_no,
            //             'reference_id' => $employee->id,
            //             'password' => Hash::make('123456'),
            //             'user_type' => 'employee',
            //             'status' => $employee->status,
            //         ]);

            //         if ($savedUser) {
            //             $role = Role::find($request->role_id);
            //             if ($role) {
            //                 $savedUser->assignRole($role->name);
            //             } else {
            //                 throw new \Exception("Invalid role ID: {$request->role_id}");
            //             }
            //         }
            //     }

            if ($request->is_login == '1') {

                $existingUser = User::where('email', $request->official_mail)
                    ->orWhere('phone', $request->mobile_no)
                    ->first();

                if ($existingUser) {
                    throw new \Exception('User with this email or mobile already exists.');
                }

                $savedUser = User::create([
                    'full_name' => $employee->full_name,
                    'email' => $employee->official_mail,
                    'phone' => $employee->mobile_no,
                    'reference_id' => $employee->id,
                    'password' => Hash::make('123456'),
                    'user_type' => 'employee',
                    'status' => $employee->status,
                ]);

                if ($savedUser) {
                    $role = Role::find($request->role_id);
                    if ($role) {
                        $savedUser->assignRole($role->name);
                    } else {
                        throw new \Exception("Invalid role ID: {$request->role_id}");
                    }
                }
            }



            DB::commit();
            return redirect()->route('employee.index')->with('success', 'Employee added successfully!');
        } catch (\Throwable $e) {
            DB::rollback();

            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }



    public function show($id)
    {
        $id = decrypt($id);
        $employee =  Employee::with('countries', 'states', 'cities', 'roles', 'employee', 'bankDetails', 'latestRevision', 'manager', 'team')->findOrFail($id);
        return view('admin.employee.show', compact('employee'));
    }


    public function edit($id)
    {
        $id = decrypt($id);
        $employee =  Employee::findOrFail($id);
        $countries = Country::select('id', 'name')->get();
        $designaions = Designation::select('id', 'name')->where('status', '1')->get();
        $branches = Branch::select('id', 'branch_name')->where('status', 'Active')->get();
        $department = Department::select('id', 'department_name')->where('status', 'Active')->get();
        $roles = Role::select('id', 'name')->where('status', 1)->where('slug', '!=', 'admin')->get();
        $employees = Employee::select('id', 'full_name')->get();
        return view('admin.employee.edit', compact('employee', 'countries', 'designaions', 'branches', 'department', 'roles', 'employees'));
    }



    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $id = decrypt($id);
            $employee = Employee::findOrFail($id);

            // $validated = $request->validate([
            //     'first_name' => 'required|string|max:255',
            //     'role' => 'required|in:sales,other',
            //     'last_name' => 'required|string|max:255',
            //     'code' => 'required|string|max:255|unique:employees,code,'.$id,

            //     'gender' => 'required|in:Male,Female,Other',
            //     'dob' => 'required|date',
            //     'status' => 'required|in:0,1',
            //     'mobile_no' => [
            //                 'required',
            //                 'string',
            //                 'max:10',

            //                 function ($attribute, $value, $fail) use ($request, $employee) {
            //                     if ($value === $employee->mobile_no) {
            //                         return;
            //                     }

            //                     if ($employee->user_id) {                         
            //                        $exists = DB::table('users')
            //                             ->where('phone', $value)
            //                             ->where('id', '!=', $employee->user_id)   
            //                             ->exists();

            //                         if ($exists) {
            //                             $fail('This mobile number is already registered for another user.');
            //                         }

            //                     } else {

            //                             $exists = DB::table('employees')                                ->where('mobile_no', $value)
            //                             ->where('id', '!=', $employee->id)   
            //                             ->exists();

            //                         if ($exists) {
            //                             $fail('This mobile number is already registered for another employee.');
            //                         }
            //                     }
            //                 },
            //             ],

            //             'official_mail' => [
            //                 'nullable',
            //                 'email',
            //                 function ($attribute, $value, $fail) use ($request, $employee) {

            //                     if ($request->is_login != 1 || empty($value)) {
            //                         return;
            //                     }

            //                     if ($value === $employee->official_mail) {
            //                         return;
            //                     }

            //                     if ($employee->user_id) {

            //                         $exists = DB::table('users')
            //                             ->where('email', $value)
            //                             ->where('id', '!=', $employee->user_id)
            //                             ->exists();

            //                         if ($exists) {
            //                             $fail('This email is already registered for another user.');
            //                         }

            //                     } else {

            //                         $exists = DB::table('users')
            //                             ->where('email', $value)
            //                             ->exists();

            //                         if ($exists) {
            //                             $fail('This email is already registered for another user.');
            //                         }
            //                     }
            //                 },
            //             ],


            // ]);

            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name'  => 'required|string|max:255',
                'role'       => 'required|in:sales,other',
                'code'       => 'required|string|max:255|unique:employees,code,' . $id,
                'gender'     => 'required|in:Male,Female,Other',
                'dob' => 'required|date|before_or_equal:' . now()->subYears(15)->format('Y-m-d'),
                'status'     => 'required|in:0,1',
                'pan_no' => 'required|unique:employees,pan_no,' . $employee->id,
                'aadhaar_no' => 'required|digits:12|unique:employees,aadhaar_no,' . $employee->id,

                'mobile_no' => [
                    'required',
                    'digits:10',
                    function ($attr, $value, $fail) use ($employee) {
                        $exists = User::where('phone', $value)
                            ->where('reference_id', '!=', $employee->id)
                            ->where('user_type', 'employee')
                            ->exists();

                        if ($exists) {
                            $fail('This mobile number is already registered.');
                        }
                    }
                ],

                'official_mail' => [
                    'nullable',
                    'email',
                    function ($attr, $value, $fail) use ($request, $employee) {

                        if ($request->is_login != 1 || empty($value)) return;

                        $exists = User::where('email', $value)
                            ->where(function ($q) use ($employee) {
                                $q->where('reference_id', '!=', $employee->id)
                                    ->orWhereNull('reference_id');
                            })
                            ->exists();

                        if ($exists) {
                            $fail('This email is already registered for another user.');
                        }
                    }
                ],
            ]);


            if ($request->hasFile('employee_image')) {
                $file = $request->file('employee_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/employee_images', $filename, 'public');
                $request->merge(['employee_image' => $filePath]);
            }

            $employee = Employee::findOrFail($id);
            $employee->role = $request->role;
            $employee->first_name = $request->first_name;
            $employee->middle_name = $request->middle_name;
            $employee->last_name = $request->last_name;
            $employee->full_name = $request->first_name . ' ' . ($request->middle_name ? $request->middle_name . ' ' : '') . $request->last_name;
            $employee->code = $request->code;
            $employee->official_mail = $request->official_mail;
            $employee->personal_mail = $request->personal_mail;
            $employee->mobile_no = $request->mobile_no;
            $employee->alternative_no = $request->alternative_no;
            $employee->gender = $request->gender;
            $employee->dob = $request->dob;
            $employee->sales_head = $request->sales_head;
            $employee->joining_date = $request->joining_date;
            $employee->branch_id = $request->branch_id;
            $employee->department_id = $request->department_id;
            $employee->designation_id = $request->designation_id;
            $employee->reporting_id = $request->reporting_id;
            $employee->role_id = $request->role_id;
            $employee->territory_id = $request->territory_id;
            $employee->country_id = $request->country_id;
            $employee->state_id = $request->state_id;
            $employee->city_id = $request->city_id;
            $employee->employee_image = $filePath ?? $employee->employee_image;
            $employee->address_line1 = $request->address_line1;
            $employee->address_line2 = $request->address_line2;
            $employee->pincode = $request->pincode;
            $employee->marital_status = $request->marital_status;
            $employee->blood_group = $request->blood_group;
            $employee->fathers_name = $request->fathers_name;
            $employee->emergancy_contact_name = $request->emergancy_contact_name;
            $employee->emergancy_contact_number = $request->emergancy_contact_number;
            $employee->employee_type = $request->employee_type;
            $employee->pan_no = $request->pan_no;
            $employee->aadhaar_no = $request->aadhaar_no;
            $employee->uan_no = $request->uan_no;
            $employee->pf_aplicable = $request->pf_aplicable ?? '0';
            $employee->esi_aplicable = $request->esi_aplicable ?? '0';
            $employee->pf_number = $request->pf_number;
            $employee->esi_number = $request->esi_number;
            $employee->is_login = $request->is_login ?? 0;
            $employee->status = $request->status;
            $employee->relieving_date = $request->relieving_date;
            $employee->separation_type = $request->separation_type;
            $employee->separation_remarks = $request->separation_remarks;
            $employee->relieving_approvel_date = $request->relieving_approvel_date;
            $employee->relieving_approved_by  = auth()->user()->id;
            $employee->save();

            // if ($request->is_login == '1') {
            //     $user = User::where('reference_id', $employee->id)
            //                 ->where('user_type', 'employee')
            //                 ->first();

            //     if ($user) {
            //         $user->full_name = $employee->full_name;
            //         $user->email = $employee->official_mail;
            //         $user->phone = $employee->mobile_no;
            //         $user->status = $employee->status;
            //         $user->save();
            //     } else {
            //         $user = User::create([
            //             'full_name' => $employee->full_name,
            //             'email' => $employee->official_mail,
            //             'phone' => $employee->mobile_no,
            //             'reference_id' => $employee->id,
            //             'password' => Hash::make('123456'),
            //             'user_type' => 'employee',
            //             'status' => $employee->status,
            //         ]);
            //     }

            //     if ($user) {
            //         $role = Role::find($request->role_id);
            //         if ($role) {
            //             $user->syncRoles([$role->name]);
            //         } else {
            //             throw new \Exception("Invalid role ID: {$request->role_id}");
            //         }
            //     }
            // } else {
            //     $user = User::where('reference_id', $employee->id)
            //                 ->where('user_type', 'employee')
            //                 ->first();

            //     if ($user) {
            //         $user->syncRoles([]); 
            //     }
            // }

            if ($request->is_login == '1') {

                $user = User::where('reference_id', $employee->id)
                    ->where('user_type', 'employee')
                    ->first();

                if ($user) {
                    $user->update([
                        'full_name' => $employee->full_name,
                        'email'     => $employee->official_mail,
                        'phone'     => $employee->mobile_no,
                        'status'    => $employee->status,
                    ]);
                } else {
                    // CREATE (safe now)
                    $user = User::create([
                        'full_name'    => $employee->full_name,
                        'email'        => $employee->official_mail,
                        'phone'        => $employee->mobile_no,
                        'reference_id' => $employee->id,
                        'password'     => Hash::make('123456'),
                        'user_type'    => 'employee',
                        'status'       => $employee->status,
                    ]);
                }

                if ($request->role_id) {
                    $role = Role::findOrFail($request->role_id);
                    $user->syncRoles([$role->name]);
                }
            } else {
                // Login disabled
                $user = User::where('reference_id', $employee->id)
                    ->where('user_type', 'employee')
                    ->first();

                if ($user) {
                    $user->syncRoles([]);
                }
            }



            DB::commit();
            return redirect()->route('employee.index')->with('success', 'Employee updated successfully!');
        } catch (\Throwable $e) {
            DB::rollback();

            return back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }



    public function destroy($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();

            return response()->json(['success' => true, 'message' => 'Employee deleted successfully!']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }


    public function getStates($country_id)
    {
        $states = State::where('country_id', $country_id)->select('id', 'name')->get();
        return response()->json($states);
    }


    public function getCities($state_id)
    {
        $cities = City::where('state_id', $state_id)->select('id', 'name')->get();
        return response()->json($cities);
    }


    public function showEmployeBankForm($employee_id)
    {
        $employee_id = decrypt($employee_id);

        $employee = Employee::find($employee_id);
        $banks = BankDetail::where('status', '1')->get();
        $bankDetails = EmployeBank::where('employee_id', $employee_id)->first();

        return view('admin.employee.employee-bank.create', compact('employee_id', 'employee', 'banks', 'bankDetails'));
    }


    public function storeEmployeeBank(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'bank_name' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:20',
            'account_number' => [
                'required',
                'digits_between:9,18',
                Rule::unique('employe_banks', 'account_number')->ignore(
                    EmployeBank::where('employee_id', $request->employee_id)->value('id')
                )
            ],
            'confirm_account_number' => 'required|same:account_number',
            'branch_address' => 'nullable|string|max:255',
            'bank_passbook' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'cheque' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

        ]);

        $employee = Employee::find($request->employee_id);
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        $data = $request->only([
            'employee_id',
            'bank_name',
            'ifsc_code',
            'account_number',
            'confirm_account_number',
            'branch_address'
        ]);
        $data['employee_name'] = $employee->full_name ?? null;

        $existing = EmployeBank::where('employee_id', $request->employee_id)->first();

        if ($request->hasFile('bank_passbook')) {
            if ($existing && $existing->bank_passbook && Storage::disk('public')->exists($existing->bank_passbook)) {
                Storage::disk('public')->delete($existing->bank_passbook);
            }
            $data['bank_passbook'] = $request->file('bank_passbook')->store('uploads/bank_passbooks', 'public');
        }

        if ($request->hasFile('cheque')) {
            if ($existing && $existing->cheque && Storage::disk('public')->exists($existing->cheque)) {
                Storage::disk('public')->delete($existing->cheque);
            }
            $data['cheque'] = $request->file('cheque')->store('uploads/cheques', 'public');
        }

        if ($existing) {
            $existing->update($data);
            $message = 'Employee bank details updated successfully.';
        } else {

            EmployeBank::create($data);
            $message = 'Employee bank details added successfully.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function showEmployedocumentForm($employee_id)
    {
        $employee_id = decrypt($employee_id);

        $employee = Employee::find($employee_id);
        $employeeDoc = EmployeeDocument::where('employee_id', $employee_id)->first();

        return view('admin.employee.employee-document.create', compact('employee_id', 'employee', 'employeeDoc'));
    }

    public function storeEmployeeDocument(Request $request)
    {

        $employeeId = $request->employee_id;
        $documentType = $request->document_type;

        if ($documentType === 'other' || $documentType === 'salary_slips' || $documentType === 'academic' || $documentType === 'degree_certificates') {

            $names = $request->document_name ?? [];
            $files = $request->file('document_filepath1') ?? [];

            $lastSavedFile = null;
            $lastExt = null;

            foreach ($files as $index => $file) {

                if (!$file && $documentType === 'salary_slips') continue;
                if (!$file && ($documentType === 'other' || $documentType === 'academic' || $documentType === 'degree_certificates') && empty($names[$index])) {
                    continue;
                }

                $doc = EmployeeDocument::where('employee_id', $employeeId)
                    ->where('document_type', $documentType)
                    ->skip($index)
                    ->first();

                if (!$doc) {
                    $doc = new EmployeeDocument();
                    $doc->employee_id = $employeeId;
                    $doc->document_type = $documentType;
                    $doc->status = 'Pending';
                    $doc->created_by = auth()->id();
                }

                if ($documentType === 'other' || $documentType === 'academic' || $documentType === 'degree_certificates') {
                    $doc->document_name = $names[$index] ?? null;
                }

                if ($file) {
                    $original = $file->getClientOriginalName();
                    $filename = time() . '_' . $original;
                    $ext = strtolower($file->getClientOriginalExtension());

                    $file->storeAs('employee_docs', $filename, 'public');
                    $doc->document_filepath1 = $filename;

                    $lastSavedFile = $filename;
                    $lastExt = $ext;
                }

                $doc->save();
            }

            return response()->json([
                'success' => true,
                'file_url' => $lastSavedFile ? asset('storage/employee_docs/' . $lastSavedFile) : null,
                'extension' => $lastExt,
                'message' => 'Documents saved successfully'
            ]);
        }

        // ---------- SINGLE DOCUMENTS ----------
        $doc = EmployeeDocument::firstOrNew([
            'employee_id' => $employeeId,
            'document_type' => $documentType
        ]);

        $savedFile = null;
        $ext = null;

        if ($request->hasFile('document_filepath1')) {
            $file1 = $request->file('document_filepath1');
            $filename1 = time() . '_' . $file1->getClientOriginalName();
            $ext = strtolower($file1->getClientOriginalExtension());

            $file1->storeAs('employee_docs', $filename1, 'public');
            $doc->document_filepath1 = $filename1;
            $savedFile = $filename1;
        }

        if ($request->hasFile('document_filepath2')) {
            $file2 = $request->file('document_filepath2');
            $filename2 = time() . '_' . $file2->getClientOriginalName();
            $file2->storeAs('employee_docs', $filename2, 'public');
            $doc->document_filepath2 = $filename2;
        }

        $doc->status = 'Pending';
        $doc->created_by = auth()->id();
        $doc->save();

        return response()->json([
            'success' => true,
            'file_url' => $savedFile ? asset('storage/employee_docs/' . $savedFile) : null,
            'extension' => $ext,
            'message' => 'Document saved successfully'
        ]);
    }


    public function showEmployeSalaryForm($employee_id)
    {
        $employee_id = decrypt($employee_id);

        $employee = Employee::find($employee_id);
        $employeSalary = EmployeeSalary::where('employee_id', $employee_id)->first();
        $components = SalaryComponent::where('status', '1')->get();
        return view('admin.employee.employe-salary.create', compact('employee_id', 'employee', 'employeSalary', 'components'));
    }



    public function storeEmployeeSalary(Request $request)
    {
        $request->validate([
            'employee_id'     => 'required|integer|exists:employees,id',
            'component_id'    => 'required|integer|exists:salary_components,id',
            'amount'          => 'nullable|numeric',
            'percentage'      => 'nullable|numeric',
            'effactive_from'  => 'required|date',
            'effactive_to'    => 'nullable|date|after_or_equal:effactive_from',
            'status'          => 'required|in:0,1',
            'remarks'         => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $employee = Employee::find($request->employee_id);
            if (!$employee) {
                return back()->with('error', 'Employee not found.');
            }

            $data = [
                'employee_id'    => $request->employee_id,
                'component_id'   => $request->component_id,
                'amount'         => $request->amount,
                'percentage'     => $request->percentage,
                'effactive_from' => $request->effactive_from,
                'effactive_to'   => $request->effactive_to,
                'status'         => $request->status ?? '0',
                'remarks'        => $request->remarks,
                'created_by'     => auth()->id(),
                'updated_by'     => auth()->id(),
            ];

            $existing = EmployeeSalary::where('employee_id', $request->employee_id)
                ->where('component_id', $request->component_id)
                ->first();

            if ($existing) {
                $existing->update($data);
                $message = 'Employee salary updated successfully.';
            } else {
                EmployeeSalary::create($data);
                $message = 'Employee salary added successfully.';
            }

            DB::commit();
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function showEmployeRevisionSalarylist(Request $request, $employee_id)
    {
        if ($request->ajax()) {
            $query = EmployeeSalaryRevision::with(['components.salaryComponet'])
                ->where('employee_id', $employee_id)
                ->orderByDesc('id');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('admin.employee.employe-salary-revision.action', compact('row'))->render();
                })
                ->editColumn('effective_from', fn($row) => $row->effective_from ? date('d M, Y', strtotime($row->effective_from)) : '-')

                ->addColumn('details', function ($row) {
                    $html = '<div style="font-size:13px;color:#444;">';

                    foreach ($row->components as $comp) {
                        $name = $comp->salaryComponet->component_name ?? 'Component';
                        $amount = number_format($comp->amount, 2);
                        $html .= "<p><strong>{$name}:</strong> ₹{$amount}</p>";
                    }

                    $html .= "<hr><p><strong>Total Salary:</strong> ₹" . number_format($row->new_salary_total, 2) . "</p>";

                    // $html .= "<hr><p><strong>Deduction:</strong> ₹" . number_format($row->total_deduction, 2) . "</p>";

                    // $html .= "<hr><p><strong>Total Net Salary:</strong> ₹" . number_format($row->net_salary, 2) . "</p>";

                    $html .= "<p><strong>Effective From:</strong> " . ($row->effective_from ? date('d M, Y', strtotime($row->effective_from)) : '-') . "</p>";

                    $html .= '</div>';
                    return $html;
                })

                ->editColumn('created_at', fn($row) => $row->created_at ? $row->created_at->format('d M, Y') : '-')

                ->editColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })

                // ->addColumn('action', function ($row) use ($employee_id) {
                //     $type = "revision";
                //     return view('admin.employee.employe-salary-revision.action', compact('row', 'type'))->render();
                // })

                ->rawColumns(['details', 'status', 'action'])
                ->make(true);
        }

        $employe = Employee::findOrFail($employee_id);
        return view('admin.employee.employe-salary-revision.index', compact('employee_id', 'employe'));
    }


    public function showEmployeRevisionSalaryCreate($employee_id)
    {
        $employe = Employee::where('id', $employee_id)->first();
        $components = SalaryComponent::where('status', '1')->get();
        return view('admin.employee.employe-salary-revision.create', compact('employee_id', 'employe', 'components'));
    }


    public function showEmployeRevisionSalaryStore(Request $request)
    {
        $request->validate([
            'employee_id'     => 'required|exists:employees,id',
            'components'      => 'required|array',
            'components.*.amount' => 'nullable|numeric|min:0',
            'effective_from'  => 'required|date',
            'revision_reason' => 'nullable|string|max:500',
            'status'          => 'required|in:0,1',
        ]);

        DB::beginTransaction();

        try {

            $employeeId = $request->employee_id;
            $employee = Employee::find($employeeId);

            $allComponents = SalaryComponent::whereIn('id', array_keys($request->components))
                ->get()
                ->keyBy('id');

            $calculatedComponents = [];

            $totalEarning = 0;
            $totalDeduction = 0;
            $totalPay = 0;

            foreach ($request->components as $componentId => $data) {

                $component = $allComponents[$componentId] ?? null;
                if (!$component) continue;

                $amount = isset($data['amount']) ? floatval($data['amount']) : 0;


                if (strtolower($component->component_name) === 'total pay') {
                    $totalPay = $amount;
                    continue;
                }

                if ($amount <= 0) continue;

                if (strtolower($component->component_type) == 'deduction') {
                    $totalDeduction += $amount;
                } else {
                    $totalEarning += $amount;
                }

                $calculatedComponents[$componentId] = $amount;
            }

            $basic = $this->getComponentAmount($allComponents, $request->components, 'Basic Pay');
            $hra   = $this->getComponentAmount($allComponents, $request->components, 'HRA');

            if ($employee->pf_aplicable == '1') {
                $pfAmount = round(($basic + $hra) * 0.12, 2);
                $totalDeduction += $pfAmount;
            }

            if ($employee->esi_aplicable == '1') {
                $esiAmount = round($totalPay * 0.0075, 2);
                $totalDeduction += $esiAmount;
            }

            $netSalary = $totalEarning - $totalDeduction;

            if ($request->status == '1') {
                EmployeeSalaryRevision::where('employee_id', $employeeId)
                    ->where('status', '1')
                    ->update(['status' => '0']);
            }

            $revision = EmployeeSalaryRevision::create([
                'employee_id'      => $employeeId,
                'new_salary_total' => $totalEarning,
                'total_deduction'  => $totalDeduction,
                'net_salary'       => $netSalary,
                'effective_from'   => $request->effective_from,
                'revision_reason'  => $request->revision_reason,
                'status'           => $request->status,
                'created_by'       => auth()->id(),
                'updated_by'       => auth()->id(),
            ]);

            foreach ($calculatedComponents as $componentId => $amount) {
                EmployeeSalaryComponent::create([
                    'revision_id'         => $revision->id,
                    'salary_component_id' => $componentId,
                    'amount'              => $amount,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('employee.revisionsalarylist.index', $employeeId)
                ->with('success', 'Salary revision added successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }


    private function getComponentAmount($allComponents, $requestComponents, $name)
    {
        foreach ($allComponents as $id => $c) {
            if (strtolower($c->component_name) == strtolower($name)) {
                return floatval($requestComponents[$id]['amount'] ?? 0);
            }
        }
        return 0;
    }


    public function showEmployeRevisionSalaryEditForm($id)
    {
        $id = decrypt($id);
        $revision = EmployeeSalaryRevision::with('components')->findOrFail($id);
        $components = SalaryComponent::orderBy('component_name')->get();

        $revisionComponents = [];
        foreach ($revision->components as $comp) {
            $revisionComponents[$comp->salary_component_id] = [
                'amount' => $comp->amount,
            ];
        }

        return view('admin.employee.employe-salary-revision.edit', compact('revision', 'components', 'revisionComponents'));
    }


    public function showEmployeRevisionSalaryUpdate(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'components'  => 'required|array',
            'effective_from' => 'required|date',
            'status' => 'required|in:0,1',
        ]);

        DB::beginTransaction();

        try {

            $revision = EmployeeSalaryRevision::with('components')->findOrFail($id);
            $employee = Employee::find($revision->employee_id);

            $allComponents = SalaryComponent::whereIn(
                'id',
                array_keys($request->components)
            )->get()->keyBy('id');

            $totalEarning = 0;
            $totalDeduction = 0;
            $totalPay = 0;

            $calculatedComponents = [];

            foreach ($request->components as $componentId => $data) {

                $component = $allComponents[$componentId] ?? null;
                if (!$component) continue;

                $amount = floatval($data['amount'] ?? 0);
                if ($amount <= 0) continue;

                if (strtolower($component->component_name) === 'total pay') {
                    $totalPay = $amount;
                    continue;
                }

                if ($component->component_type == 'deduction') {
                    $totalDeduction += $amount;
                } else {
                    $totalEarning += $amount;
                }

                $calculatedComponents[$componentId] = $amount;
            }

            $basic = $this->getComponentAmount($allComponents, $request->components, 'Basic Pay');
            $hra   = $this->getComponentAmount($allComponents, $request->components, 'HRA');

            if ($employee->pf_aplicable == 1) {
                $totalDeduction += round(($basic + $hra) * 0.12, 2);
            }

            if ($employee->esi_aplicable == 1) {
                $totalDeduction += round($totalPay * 0.0075, 2);
            }

            $netSalary = $totalEarning - $totalDeduction;

            // Update revision
            $revision->update([
                'new_salary_total' => $totalEarning,
                'total_deduction'  => $totalDeduction,
                'net_salary'       => $netSalary,
                'effective_from'   => $request->effective_from,
                'revision_reason'  => $request->revision_reason,
                'status'           => $request->status,
                'updated_by'       => auth()->id(),
            ]);

            // Remove old components
            EmployeeSalaryComponent::where('revision_id', $revision->id)->delete();

            // Insert updated components
            foreach ($calculatedComponents as $componentId => $amount) {
                EmployeeSalaryComponent::create([
                    'revision_id' => $revision->id,
                    'salary_component_id' => $componentId,
                    'amount' => $amount,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('employee.revisionsalarylist.index', $revision->employee_id)
                ->with('success', 'Salary revision updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors($e->getMessage());
        }
    }


    public function exportExcel(Request $request)
    {
        return Excel::download(new EmployeeExport($request), 'employee-list.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $import = new EmployeeImport();
            Excel::import($import, $request->file('file'));

            if ($import->getImportedCount() === 0) {
                return back()->with('import_error_single', 'Uploaded Excel file is empty. Please add employee data.');
            }

            return back()->with('success', 'Employees imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            $firstFailure = collect($e->failures())->first();


            return back()->with('import_first_error', [
                'row'   => $firstFailure->row(),
                'error' => $firstFailure->errors()[0], // sirf pehla error
            ]);
        } catch (\Throwable $e) {
            return back()->with('import_error_single', $e->getMessage());
        }
    }

    public function exportEmployeeData()
    {
        return Excel::download(new EmployeeDataExport, 'employee_data.xlsx');
    }


    // EmployeeController.php mein add karo

    public function showEmployeeAllowanceForm($employee_id)
    {
        $employee_id = decrypt($employee_id);

        $employee = Employee::find($employee_id);
        $allowance = EmployeeAllowance::where('employee_id', $employee_id)->first();

        return view('admin.employee.employee-allowance.create', compact('employee_id', 'employee', 'allowance'));
    }

    public function storeEmployeeAllowance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'hq'          => 'nullable|numeric|min:0',
            'exst'        => 'nullable|numeric|min:0',
            'outst'       => 'nullable|numeric|min:0',
            'phone'       => 'nullable|numeric|min:0',
            'hotel'       => 'nullable|numeric|min:0',
        ]);

        $employee = Employee::find($request->employee_id);
        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        $data = $request->only(['employee_id', 'hq', 'exst', 'outst', 'phone', 'hotel']);
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        $existing = EmployeeAllowance::where('employee_id', $request->employee_id)->first();

        if ($existing) {
            $existing->update($data);
            $message = 'Employee allowance updated successfully.';
        } else {
            EmployeeAllowance::create($data);
            $message = 'Employee allowance added successfully.';
        }

        return redirect()->back()->with('success', $message);
    }
}
