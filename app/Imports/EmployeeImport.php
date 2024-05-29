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
            // $nameParts = Helpers::explodeFullName($row['first_name']);
            // $firstName = $nameParts['first_name'];
            // $lastName = $nameParts['last_name'];
            $employeeNumber = Helpers::generateEmployeeNumber($businessId);

            $emp = Employee::create([
                'label' => $row['label'],
                'employee_number' => $employeeNumber,
                'is_discontinued' => $row['is_discontinued'] == "D" ? 1 : 0,
                'first_name' => $row['first_name'],
                'middle_name' => $row['middle_name'] ?? null,
                'last_name' => $row['last_name'],
                'ext_name' => $row['ext_name'] ?? null,
                'email' => $row['email'] ?? null,
                'phone' => $row['phone'] ?? null,
                'birth_date' => $row['birth_date'] ?? null,
                'joining_date' => $row['joining_date'],
                'end_date' => $row['end_date'] ?? null,
                'gender' => $row['gender'] ?? null,
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
                'designation_id' => Helpers::designationToId($row['designation']),
                'employee_status_id' => Helpers::employeeStatusToId($row['employee_status']),
                'workshift_id' => 1,
                'department_id' => 1, // Administration
                'business_id' => $businessId,
            ]);

            //salary histories
            if($row['label'] == 'National' || $row['label'] == 'national') {
                if(!is_null($row['hourly_rate']) && isset($row['hourly_rate'])) {
                    $emp->salaries()->create([
                        'hourly_rate' => $row['hourly_rate'],
                        'is_active' => true
                    ]);
                }
            } elseif ($row['label'] == 'Expatriate' || $row['label'] == 'expatriate'){
                if(!is_null($row['monthly_rate']) && isset($row['monthly_rate'])) {
                    $emp->salaries()->create([
                        'monthly_rate' => $row['monthly_rate'],
                        'is_active' => true
                    ]);
                }
            }

            //employee notes
            if(!is_null($row['notes']) && isset($row['notes'])) {
                $emp->bank_details()->create([
                    'notes' => $row['notes'] ?? ''
                ]);
            }

            //bank details 1
            if(isset($row['bank_account_name_1']) && isset($row['bank_account_number_1']) && isset($row['bank_name_1']) && isset($row['bsb_code_1'])) {
                $emp->bank_details()->create([
                    'account_name' => $row['bank_account_name_1'] ?? '',
                    'account_number' => $row['bank_account_number_1'] ?? '',
                    'bank_name' => $row['bank_name_1'] ?? '',
                    'bsb_code' => $row['bsb_code_1'] ?? '',
                    'is_active' => true,
                ]);
            }

            //bank details 2
            if(isset($row['bank_account_name_2']) && isset($row['bank_account_number_2']) && isset($row['bank_name_2']) && isset($row['bsb_code_2'])) {
                $emp->bank_details()->create([
                    'account_name' => $row['bank_account_name_2'] ?? '',
                    'account_number' => $row['bank_account_number_2'] ?? '',
                    'bank_name' => $row['bank_name_2'] ?? '',
                    'bsb_code' => $row['bsb_code_2'] ?? '',
                ]);
            }
        }
    }
}
