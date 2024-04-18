<?php

namespace App\Imports;

use App\Helpers\Helpers;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EmployeeImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Administration' =>  new EmployeeAdministrationSheetImport(),
            'Human Resource' => new EmployeeHumanResourceSheetImport(),
            '' => new EmployeeAccountingAndFinanceSheetImport(),
            '' => new EmployeeOperationSheetImport(),
            '' => new EmployeeSalesAndMarketingSheetImport(),
            '' => new EmployeePurchasingSheetImport(),
            '' => new EmployeeLogisticSheetImport(),
            '' => new EmployeeITSheetImport(),
        ];
    }
}
