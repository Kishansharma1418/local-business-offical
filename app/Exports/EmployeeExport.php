<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Spatie\Permission\Models\Role;


class EmployeeExport implements FromCollection, WithHeadings, WithEvents
{   
    protected $request;
    
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        return collect([]);
    }

    public function headings(): array
    {
        return [
            'Employee Code',
            'First Name',
            'Middle Name',
            'Last Name',
            'Gender',
            'DOB',
            'Employee Type',
            'Reporting Emp ID',
            'Branch ID',
            'Department ID',
            'Designation ID',
            'Official Email',
            'Personal Email',
            'Mobile No',
            'Alternate No',
            'Joining Date',
            'Relieving Date',
            'Separation Type',
            'Separation Remark',
            'Relieving Approved Date',
            'Country',
            'State',
            'City',
            'Address Line 1',
            'Address Line 2',
            'Pincode',
            'Marital Status',
            'Blood Group',
            'Emergency Contact Name',
            'Emergency Contact Number',
            'Employment Type',
            'PAN No',
            'Aadhar No',
            'UAN No',
            'PF Applicable',
            'ESI Applicable',
            'Login Allowed',
            'Role',
            'Status',
            'Fathers Name',
            'sales Head',
            'PF Number',
            "ESI Number",
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $spreadsheet = $event->sheet->getDelegate()->getParent();
                $dropdownSheet = $spreadsheet->createSheet();
                $dropdownSheet->setTitle('dropdown_data');

                $dropdownSheet->fromArray([
                    ['sales'],
                    ['other']
                ], null, 'A1');

                $dropdownSheet->fromArray([
                    ['Male'],
                    ['Female'],
                    ['Other']
                ], null, 'B1');

                $dropdownSheet->fromArray([
                    ['Resignation'],
                    ['Termination'],
                    ['Absconding']
                ], null, 'C1');

                $dropdownSheet->fromArray([
                    ['Single'],
                    ['Married'],
                    ['Other']
                ], null, 'D1');

                $dropdownSheet->fromArray([
                    ['Permanent'],
                    ['Contract'],
                    ['Intern'],
                    ['Consultant'],
                ], null, 'E1');

                $dropdownSheet->fromArray([
                    ['Yes'],
                    ['No'],
                ], null, 'F1');

                $dropdownSheet->fromArray([
                    ['Yes'],
                    ['No'],
                ], null, 'G1');

               $dropdownSheet->fromArray([
                ['Active'],
                ['Inactive'],
                 ], null, 'H1');


                $branches = Branch::orderBy('branch_name')->pluck('branch_name')->toArray();
                $departments = Department::orderBy('department_name')->pluck('department_name')->toArray();
                $designations = Designation::orderBy('name')->pluck('name')->toArray();
                $employees = Employee::orderBy('full_name')->pluck('full_name')->toArray();
                 $roles = Role::orderBy('name')->pluck('name')->toArray();
                // $countries = \DB::table('countries')->orderBy('name')->pluck('name')->toArray();
                // $states = \DB::table('states')->orderBy('name')->pluck('name')->toArray();
                // $cities = \DB::table('cities')->orderBy('name')->pluck('name')->toArray();


                $dropdownSheet->fromArray(array_map(fn($v) => [$v], $branches), null, 'I1');
                $dropdownSheet->fromArray(array_map(fn($v) => [$v], $departments), null, 'J1');
                $dropdownSheet->fromArray(array_map(fn($v) => [$v], $designations), null, 'K1');
                $dropdownSheet->fromArray(array_map(fn($v) => [$v], $employees), null, 'L1');
                $dropdownSheet->fromArray(array_map(fn($v) => [$v], $roles), null, 'AL1');

                // $dropdownSheet->fromArray(array_map(fn($v) => [$v], $designations), null, 'H1');
                // $dropdownSheet->fromArray(array_map(fn($v) => [$v], $countries), null, 'L1');
                // $dropdownSheet->fromArray(array_map(fn($v) => [$v], $states), null, 'M1');
                // $dropdownSheet->fromArray(array_map(fn($v) => [$v], $cities), null, 'N1');


                $lastBranchRow = count($branches);
                $lastDeptRow = count($departments);
                $lastDesigRow = count($designations);
                $lastEmplRow = count($employees);
                 $lastRoleRow = count($roles);
                // $lastCountryRow = count($countries);
                // $lastStateRow = count($states);
                // $lastCityRow = count($cities);


                $spreadsheet->setActiveSheetIndex(0);
                $sheet = $spreadsheet->getActiveSheet();
                $startRow = 2;
                $endRow = 300;
                $colGender = "E";
                $colEmpType = "G";
                $colBranch = "I";
                $colDept = "J";
                $colDesig = "K";
                $colEmpl = "H";
                $colRole = "AL";
                $colSeparation = "R";
                $colMarital = "AA";
                $colEmploymentType = "AE";
                $colPF = "AI";
                $colESI = "AJ";
                $colLogin = "AK";
                $colStatus = "AM";
                // $colCountry = "T";  
                // $colState = "U";    
                // $colCity = "V";         

                $applyDropdown = function($col, $formula) use ($sheet, $startRow, $endRow) {
                    for ($i = $startRow; $i <= $endRow; $i++) {
                        $validation = new DataValidation();
                        $validation->setType(DataValidation::TYPE_LIST);
                        $validation->setAllowBlank(false);
                        $validation->setShowDropDown(true);
                        $validation->setFormula1($formula);
                        $sheet->getCell($col.$i)->setDataValidation($validation);
                    }
                };

                    $applyDropdown($colGender, "dropdown_data!\$B\$1:\$B\$3"); 
                    $applyDropdown($colEmpType, "dropdown_data!\$A\$1:\$A\$2");
                    $applyDropdown($colSeparation, "dropdown_data!\$C\$1:\$C\$3");
                    $applyDropdown($colMarital, "dropdown_data!\$D\$1:\$D\$3");
                    $applyDropdown($colEmploymentType, "dropdown_data!\$E\$1:\$E\$4");
                    $applyDropdown($colPF, "dropdown_data!\$F\$1:\$F\$2");
                    $applyDropdown($colESI, "dropdown_data!\$F\$1:\$F\$2");
                    $applyDropdown($colLogin, "dropdown_data!\$G\$1:\$G\$2");
                    $applyDropdown($colStatus, "dropdown_data!\$H\$1:\$H\$2");

                    $applyDropdown($colBranch, "dropdown_data!\$I\$1:\$I\$".$lastBranchRow);
                    $applyDropdown($colDept, "dropdown_data!\$J\$1:\$J\$".$lastDeptRow);
                    $applyDropdown($colDesig, "dropdown_data!\$K\$1:\$K\$".$lastDesigRow);
                    $applyDropdown($colEmpl, "dropdown_data!\$L\$1:\$L\$".$lastEmplRow);
                    $applyDropdown($colRole, "dropdown_data!\$AL\$1:\$AL\$".$lastRoleRow);

                    //  $applyDropdown($colCountry, "dropdown_data!\$L\$1:\$L\$".$lastCountryRow);
                    //  $applyDropdown($colState, "dropdown_data!\$M\$1:\$M\$".$lastStateRow);
                    // $applyDropdown($colCity, "dropdown_data!\$N\$1:\$N\$".$lastCityRow);

                    $dateColumns = ['F', 'P', 'Q', 'T'];

                   foreach ($dateColumns as $colLetter) {

                      for ($i = $startRow; $i <= $endRow; $i++) {

                        $dateValidation = new DataValidation();
                        $dateValidation->setType(DataValidation::TYPE_DATE);
                        $dateValidation->setAllowBlank(true);
                        $dateValidation->setShowErrorMessage(true);
                        $dateValidation->setErrorTitle("Invalid Date");
                        $dateValidation->setError("Please enter valid date only");
                        $dateValidation->setFormula1('DATE(1950,1,1)');
                        $dateValidation->setFormula2('DATE(2090,12,31)');
                        $sheet->getCell($colLetter.$i)->setDataValidation($dateValidation);
                    }

                    $sheet->getStyle($colLetter.$startRow.':'.$colLetter.$endRow)
                        ->getNumberFormat()
                        ->setFormatCode('dd-mm-yyyy');
                }

                $dropdownSheet->setSheetState('hidden');
            }
        ];
    }
}