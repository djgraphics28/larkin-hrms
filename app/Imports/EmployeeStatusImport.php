<?php

namespace App\Imports;

use App\Models\EmployeeStatus;
use Maatwebsite\Excel\Concerns\ToModel;

class EmployeeStatusImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new EmployeeStatus([
            //
        ]);
    }
}
