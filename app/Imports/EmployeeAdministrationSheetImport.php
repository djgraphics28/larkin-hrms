<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Department;
use App\Models\SalaryHistory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeAdministrationSheetImport implements ToCollection, WithHeadingRow
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
                // 'middle_name' => $row['middle_name'] ?? null,
                'last_name' => $row['last_name'],
                // 'ext_name' => $row['ext_name'] ?? null,
                // 'email' => $row['email'] ?? null,
                // 'phone' => $row['phone'] ?? null,
                'birth_date' => $row['birth_date'] ?? null,
                'joining_date' => $row['joining_date'],
                'end_date' => $row['end_date'] ?? null,
                'gender' => $row['gender'],
                'marital_status' => $row['marital_status'],
                'address' => $row['address'],
                // 'deployment_date_home_country' => $row['deployment_date_home_country'] ?? null,
                // 'nasfund_number' => $row['nasfund_number'] ?? null,
                // 'passport_number' => $row['passport_number'] ?? null,
                // 'passport_expiry' => $row['passport_expiry'] ?? null,
                // 'work_permit_number' => $row['work_permit_number'] ?? null,
                // 'work_permit_expiry' => $row['work_permit_expiry'] ?? null,
                // 'visa_number' => $row['visa_number'] ?? null,
                // 'visa_expiry' => $row['visa_expiry'] ?? null,
                'designation_id' => $row['designation_id'],
                'employee_status_id' => $row['employee_status_id'],
                'workshift_id' => 1,
                'department_id' => 1, // Administration
                'business_id' => $businessId,
            ]);

            $emp->salaries()->create([
                'salary_rate' => $row['salary_rate'] ?? 0,
                'is_active' => true
            ]);
        }
    }

    // public function limit(): int
    // {
    //     return 100; // only take 100 rows
    // }
}
