<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeDataExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $employees = Employee::with([
            'countries',
            'states',
            'cities',
            'branches',
            'departments',
            'designation',
            'roles',
            'employee',
        ])->get();

        return $employees->map(function ($e) {
            return [
                'employee_type'             => $e->employee_type,
                'code'                      => $e->code,
                'first_name'                => $e->first_name,
                'middle_name'               => $e->middle_name,
                'last_name'                 => $e->last_name,
                'full_name'                 => $e->full_name,
                'gender'                    => $e->gender,
                'dob'                       => $e->dob,
                'official_mail'             => $e->official_mail,
                'personal_mail'             => $e->personal_mail,
                'mobile_no'                 => $e->mobile_no,
                'alternative_no'            => $e->alternative_no,
                'joining_date'              => $e->joining_date,

                'branch'                    => optional($e->branches)->branch_name,
                'department'                => optional($e->departments)->department_name,
                'designation'               => optional($e->designation)->name,
                'reporting_to'              => optional($e->employee)->full_name,
                'role'                      => optional($e->roles)->name,
                'city'                      => optional($e->cities)->name,
                'state'                     => optional($e->states)->name,
                'country'                   => optional($e->countries)->name,

                'address_line1'             => $e->address_line1,
                'address_line2'             => $e->address_line2,
                'pincode'                   => $e->pincode,
                'marital_status'            => $e->marital_status,
                'blood_group'               => $e->blood_group,
                'emergancy_contact_name'    => $e->emergancy_contact_name,
                'emergancy_contact_number'  => $e->emergancy_contact_number,
                'pan_no'                    => $e->pan_no,
                'aadhaar_no'                => $e->aadhaar_no,
                'is_login'                  => $e->is_login,
                'status'                    => $e->status,
                'uan_no'                    => $e->uan_no,
                'relieving_date'            => $e->relieving_date,
                'separation_type'           => $e->separation_type,
                'separation_remarks'        => $e->separation_remarks,
                'relieving_approved_by'     => $e->relieving_approved_by,
                'relieving_approvel_date'   => $e->relieving_approvel_date,
                'pf_aplicable'              => $e->pf_aplicable,
                'esi_aplicable'             => $e->esi_aplicable,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Employee Type',
            'Code',
            'First Name',
            'Middle Name',
            'Last Name',
            'Full Name',
            'Gender',
            'DOB',
            'Official Email',
            'Personal Email',
            'Mobile No',
            'Alternative No',
            'Joining Date',
            'Branch',
            'Department',
            'Designation',
            'Reporting To',
            'Role',
            'City',
            'State',
            'Country',
            'Address Line 1',
            'Address Line 2',
            'Pincode',
            'Marital Status',
            'Blood Group',
            'Emergency Contact Name',
            'Emergency Contact Number',
            'PAN No',
            'Aadhaar No',
            'Is Login',
            'Status',
            'UAN No',
            'Relieving Date',
            'Separation Type',
            'Separation Remarks',
            'Relieving Approved By',
            'Relieving Approval Date',
            'PF Applicable',
            'ESI Applicable',
        ];
    }
}
