<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EmployeeAdministrationSheetImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row)
        {
            $emp = Employee::create([
                'label' => $row[0],
                'first_name' => $row[1],
                'middle_name' => $row[2],
                'last_name' => $row[3],
                'ext_name' => $row[4],
                'email' => $row[5],
                'phone' => $row[6],
                'birth_date' => $row[7],
                'joining_date' => $row[8],
                'end_date' => $row[9],
                'gender' => $row[10],
                'marital_status' => $row[11],
                'address' => $row[12],
                'deployment_date_home_country' => $row[13],
                'nasfund_number' => $row[14],
                'passport_number' => $row[15],
                'passport_expiry' => $row[16],
                'work_permit_number' => $row[17],
                'work_permit_expiry' => $row[18],
                'visa_number' => $row[19],
                'visa_expiry' => $row[20],
                'designation_id' => $row[21],
                'employee_status_id' => $row[22],
                'workshift_id' => $row[23],
                'department_id' => 1, //Administration
                'branch_id' => 1,
            ]);
        }
    }
}
