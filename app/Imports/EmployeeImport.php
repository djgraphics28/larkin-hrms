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
            new EmployeeAdministrationSheetImport(),
            // 'Human Resource' => new EmployeeHumanResourceSheetImport(),
            // 'Accounting & Finance' => new EmployeeAccountingAndFinanceSheetImport(),
            // 'Operation' => new EmployeeOperationSheetImport(),
            // 'Sales & Marketing' => new EmployeeSalesAndMarketingSheetImport(),
            // 'Purchasing' => new EmployeePurchasingSheetImport(),
            // 'Logistic' => new EmployeeLogisticSheetImport(),
            // 'IT' => new EmployeeITSheetImport(),
        ];
    }
}
