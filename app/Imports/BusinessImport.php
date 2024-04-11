<?php

namespace App\Imports;

use App\Models\Business;
use Maatwebsite\Excel\Concerns\ToModel;

class BusinessImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Business([
            //
        ]);
    }
}
