<?php

namespace App\Imports;

use App\Helpers\Helpers;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        //active business selected
        $businessId = Helpers::getActiveBusinessId();

        foreach ($collection as $row) {
            $nameParts = Helpers::explodeFullName($row['first_name']);
            $firstName = $nameParts['first_name'];
            $lastName = $nameParts['last_name'];

            $emp = Employee::create([
                'label' => $row['label'],
                'employee_number' => Helpers::generateEmployeeNumber($businessId),
                'is_discontinued' => $row['is_discontinued'] == "D" ? 1 : 0,
                'first_name' => $firstName,
                'middle_name' => $row['middle_name'] ?? null,
                'last_name' => $lastName,
                'ext_name' => $row['ext_name'] ?? null,
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'birth_date' => $row['birth_date'] ?? null,
                'joining_date' => $row['joining_date'],
                'end_date' => $row['end_date'] ?? null,
                'gender' => $row['gender'],
                'marital_status' => $row['marital_status'] ?? null,
                'address' => $row['address'] ?? null,
                'deployment_date_home_country' => $row['deployment_date_home_country'] ?? null,
                'nasfund_number' => $row['nasfund_number'] ?? null,
                'passport_number' => $row['passport_number'] ?? null,
                'passport_expiry' => $row['passport_expiry'] ?? null,
                'work_permit_number' => $row['work_permit_number'] ?? null,
                'work_permit_expiry' => $row['work_permit_expiry'] ?? null,
                'visa_number' => $row['visa_number'] ?? null,
                'visa_expiry' => $row['visa_expiry'] ?? null,
                'designation_id' => Helpers::designationToId($row['designation_id']),
                'employee_status_id' => Helpers::employeeStatusToId($row['employee_status_id']),
                'workshift_id' => 1,
                'department_id' => 1, // Administration
                'business_id' => $businessId,
            ]);

            //salary histories
            if(!is_null($row['salary_rate']) && isset($row['salary_rate'])) {
                $emp->salaries()->create([
                    'salary_rate' => $row['salary_rate'] ?? 0,
                    'is_active' => true
                ]);
            }

            //employee notes
            if(!is_null($row['notes']) && isset($row['notes'])) {
                $emp->employee_notes()->create([
                    'notes' => $row['notes'] ?? ''
                ]);
            }
        }
    }
}
