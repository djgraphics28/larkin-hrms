<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EmployeeOperationSheetImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $businessId = Helpers::getActiveBusinessId();

        foreach ($collection as $row) {

            $emp = Employee::create([
                'label' => $row['label'],
                'employee_number' => Helpers::generateEmployeeNumber($businessId),
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'],
                'last_name' => $row['last_name'],
                'ext_name' => $row['ext_name'],
                'email' => $row['email'],
                'phone' => $row['phone'],
                'birth_date' => $row['birth_date'],
                'joining_date' => $row['joining_date'],
                'end_date' => $row['end_date'],
                'gender' => $row['gender'],
                'marital_status' => $row['marital_status'],
                'address' => $row['address'],
                'deployment_date_home_country' => $row['deployment_date_home_country'],
                'nasfund_number' => $row['nasfund_number'],
                'passport_number' => $row['passport_number'],
                'passport_expiry' => $row['passport_expiry'],
                'work_permit_number' => $row['work_permit_number'],
                'work_permit_expiry' => $row['work_permit_expiry'],
                'visa_number' => $row['visa_number'],
                'visa_expiry' => $row['visa_expiry'],
                'designation_id' => $row['designation_id'],
                'employee_status_id' => $row['employee_status_id'],
                'workshift_id' => $row['workshift_id'],
                'department_id' => 4, // Operations
                'business_id' => $businessId,
            ]);
        }
    }
}
