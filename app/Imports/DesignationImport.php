<?php

namespace App\Imports;

use App\Models\Designation;
use Maatwebsite\Excel\Concerns\ToModel;

class DesignationImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Designation([
            //
        ]);
    }
}
