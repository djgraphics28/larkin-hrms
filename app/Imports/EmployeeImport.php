<?php

namespace App\Imports;

use App\Models\Employee;
use App\Imports\EmployeeITSheetImport;
use App\Imports\EmployeeLogisticSheetImport;
use App\Imports\EmployeeOperationSheetImport;
use App\Imports\EmployeePurchasingSheetImport;
use App\Imports\EmployeeHumanResourceSheetImport;
use App\Imports\EmployeeAdministrationSheetImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\EmployeeSalesAndMarketingSheetImport;
use App\Imports\EmployeeAccountingAndFinanceSheetImport;

class EmployeeImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            0 => new EmployeeAdministrationSheetImport(),
            1 => new EmployeeHumanResourceSheetImport(),
            2 => new EmployeeAccountingAndFinanceSheetImport(),
            3 => new EmployeeOperationSheetImport(),
            4 => new EmployeeSalesAndMarketingSheetImport(),
            5 => new EmployeePurchasingSheetImport(),
            6 => new EmployeeLogisticSheetImport(),
            7 => new EmployeeITSheetImport(),
        ];
    }
}
