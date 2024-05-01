<?php

namespace Database\Seeders;

use App\Models\EmailVariable;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmailVariableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variables = [
            'Employee Name',
            'Employee Number',
            'Employee Email',
            'Employee Address',
            'Company Name',
            'Business Name',
            'Business Address',
            'Approved By',
            'Date Approved',
            'Date Requested',
            'Date Start',
            'Date End',
            'Status',
        ];

        foreach ($variables as $data) {
            $variableName = str_replace(' ', '', ucwords(str_replace('_', ' ', $data)));

            EmailVariable::create([
                'name' => $data,
                'variable' => lcfirst($variableName),
            ]);
        }
    }
}
