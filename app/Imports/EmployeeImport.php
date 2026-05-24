<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class EmployeeImport implements
    ToModel,
    WithHeadingRow, 
    WithValidation,
 
    SkipsEmptyRows   
        
{
    use Importable, SkipsFailures;

        private int $importedRows = 0;

    public function prepareForValidation(array $row)
    {
        $clean = [];
        foreach ($row as $key => $val) {
            $k = strtolower(trim($key));
            $k = str_replace([' ', '-', '.', ':'], '_', $k);
          $clean[$k] = is_string($val) ? trim($val) : $val;

        }
        return $clean;
    }

 
    public function rules(): array
    {
        return [
            '*.employee_code'    => ['required', 'unique:employees,code'],
            '*.first_name'       => ['required'],
            '*.last_name'        => ['required'],
            '*.gender'           => ['required', 'in:Male,Female,Other'],
            '*.dob'              => ['required'],
            '*.employee_type'   => ['required','in:sales,other'],
            '*.official_email'   => [ 'required',  'email','unique:employees,official_mail'],
           '*.mobile_no'         => ['required','min:10','max:10','unique:employees,mobile_no'],
           '*.joining_date'      =>['required'],
           '*.address_line_1'    =>['required'],
           '*.employment_type'    =>['required'],
           '*.pf_applicable'     =>['in:Yes,No,1,0,true,false'],
           '*.esi_applicable'    =>['in:Yes,No,1,0,true,false'],
           '*.login_allowed'     =>['in:Yes,No,1,0,true,false'], 
            '*.role'            => ['required_if:*.login_allowed,Yes'],
            '*.sales_head'        => ['required'],
            '*.pf_number'         => ['requiredif:*.pf_applicable,Yes'],
            '*.esi_number'        => ['requiredif:*.esi_applicable,Yes'],

        ];
    }   

   public function customValidationMessages()
{
    return [
        '*.employee_code.required' => 'Employee Code is required.',
        '*.employee_code.unique'   => 'Employee Code already exists.',

        '*.first_name.required'    => 'First Name is required.',
        '*.last_name.required'     => 'Last Name is required.',

        '*.gender.required'        => 'Gender is required.',
        '*.gender.in'              => 'Gender must be Male, Female or Other.',

        '*.dob.required'           => 'Date of Birth is required.',

        '*.official_email.required'=> 'Official Email is required.',
        '*.official_email.email'   => 'Official Email must be a valid email.',
        '*.official_email.unique'  => 'Official Email already exists.',

        '*.mobile_no.required'     => 'Mobile Number is required.',
        '*.mobile_no.min'          => 'Mobile Number must be 10 digits.',
        '*.mobile_no.max'          => 'Mobile Number must be 10 digits.',
        '*.mobile_no.unique'       => 'Mobile Number already exists.',

        '*.joining_date.required'  => 'Joining Date is required.',
        '*.address_line_1.required'=> 'Address  is required.',
       '*.employee_type.required'  => 'Employee Type is required.',
       '*.employment_type.required'=> 'Employement Type is required.',

        '*.pf_applicable.in'       => 'PF Applicable must be Yes or No.',
        '*.esi_applicable.in'      => 'ESI Applicable must be Yes or No.',
        '*.login_allowed.in'       => 'Login Allowed must be Yes or No.',
       '*.role.required_if'        => 'Role is required when Login Allowed is Yes.',
        '*.role.in'                => 'Role must be sales or other.',
        '*.sales_head.required'    => 'Sales Head is required.',
        '*.pf_number.requiredif'   => 'PF Number is required when PF Applicable is Yes.',
        '*.esi_number.requiredif'  => 'ESI Number is required when ESI Applicable is Yes.',
    ];
}

    public function model(array $row)
    {    $this->importedRows++; 
        $isLogin = $this->yesNo($row['login_allowed'] ?? 'no');
      $mobile = (string) preg_replace('/\D/', '', $row['mobile_no'] ?? '');


        if ($isLogin == '1') {
            if (DB::table('users')->where('phone', $mobile)->exists()) {
                throw new \Exception("This mobile number is already registered for another user.");
            }   
        } else {
            if (Employee::where('mobile_no', $mobile)->exists()) {
                throw new \Exception("Mobile number already exists in employees.");
            }
        }

        $branchId = Branch::where('branch_name', trim($row['branch_id'] ?? ''))->value('id');
        $departmentId = Department::where('department_name', trim($row['department_id'] ?? ''))->value('id');
        $designationId = Designation::where('name', trim($row['designation_id'] ?? ''))->value('id');
        $roleId = Role::where('name', trim($row['role'] ?? ''))->value('id');

        return Employee::create([
            'role'                   => $row['employee_type'] ?? null,
            'first_name'             => $row['first_name'],
            'middle_name'            => $row['middle_name'] ?? null,
            'last_name'              => $row['last_name'],
            'full_name'              => trim(
                ($row['first_name'] ?? '').' '.
                ($row['middle_name'] ?? '').' '.
                ($row['last_name'] ?? '')
            ),
            'code'                       => $row['employee_code'],
            'official_mail'              => $row['official_email'] ?? null,
            'personal_mail'              => $row['personal_email'] ?? null,
            'mobile_no'                  => $mobile,
            'alternative_no'             => $row['alternate_no'] ?? null,
            'gender'                     => $row['gender'],
            'dob'                        => $this->formatDate($row['dob']),
            'joining_date'               => $this->formatDate($row['joining_date'] ?? null),
            'relieving_date'             => $this->formatDate($row['relieving_date'] ?? null),
            'separation_remarks'         => $row['separation_remark'] ?? null,
            'relieving_approvel_date'    => $this->formatDate($row['relieving_approved_date'] ?? null),
            'relieving_approved_by'      => $row['relieving_approved_by'] ?? null,
            'branch_id'                  => $branchId,
            'department_id'              => $departmentId,
            'designation_id'             => $designationId,
            'reporting_id' => Employee::whereRaw("LOWER(full_name) = ?", [strtolower(trim($row['reporting_emp_id'] ?? ''))])->value('id'),
            'role_id'                    => $roleId,
            'country_id'                 => $row['country'] ?? null,
            'state_id'                   => $row['state'] ?? null,
            'city_id'                    => $row['city'] ?? null,
            'address_line1'              => $row['address_line_1'] ?? null,
            'address_line2'              => $row['address_line_2'] ?? null,
            'pincode'                    => $row['pincode'] ?? null,
            'marital_status'             => $row['marital_status'] ?? null,
            'blood_group'                => $row['blood_group'] ?? null,
            'emergancy_contact_name'     => $row['emergency_contact_name'] ?? null,
            'emergancy_contact_number'   => $row['emergency_contact_number'] ?? null,
            'separation_type'            => $row['separation_type'] ?? null,
            'employee_type'              => $row['employment_type'] ?? null,
            'pan_no'                     => $row['pan_no'] ?? null,
            'aadhaar_no'                 => $row['aadhar_no'] ?? null,
            'uan_no'                     => $row['uan_no'] ?? null,
            'pf_aplicable'               => $this->yesNo($row['pf_applicable'] ?? 'no'),
            'esi_aplicable'              => $this->yesNo($row['esi_applicable'] ?? 'no'),
            'is_login'                   => $isLogin,
            'status'                     => $this->yesNoStatus($row['status'] ?? 'inactive'),
            'created_by'                 => auth()->id() ?? null,
            'relieving_approved_by'      =>  auth()->id() ?? null,
            'fathers_name'              => $row['fathers_name'] ?? null,
            'sales_head'                => $row['sales_head'] ?? null,
            'pf_number'                 => $row['pf_number'] ?? null,
            'esi_number'                => $row['esi_number'] ?? null,
        ]);
    }

    private function yesNo($val)
    {
        return in_array(strtolower(trim($val)), ['yes','y','1','true']) ? '1' : '0';
    }

    private function yesNoStatus($val)
    {
        return in_array(strtolower(trim($val)), ['active','1','yes']) ? '1' : '0';
    }

    private function formatDate($val)
    {
        if (!$val) return null;
        try {
            if (is_numeric($val)) {
                return Carbon::instance(
                    ExcelDate::excelToDateTimeObject($val)
                )->format('Y-m-d');
            }
            return Carbon::parse($val)->format('Y-m-d');
        } catch (\Throwable $e) {
                return null;
            }
        }

            public function isEmptyWhen(array $row): bool
        {
            return empty(trim($row['employee_code'] ?? ''));
        }

            public function getImportedCount(): int
            {
                return $this->importedRows; 
            }

            
            
            

}