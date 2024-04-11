<?php

namespace App\Exports;

use App\Models\EmployeeStatus;
use Maatwebsite\Excel\Concerns\FromCollection;

class EmployeeStatusExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return EmployeeStatus::all();
    }
}
