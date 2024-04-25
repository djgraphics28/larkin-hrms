<?php

namespace Database\Seeders;

use App\Models\EmployeeStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmployeeStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employeeStatues = [
            'Active',
            'Present',
            'AWOL',
            'Terminated',
            'Resigned',
            'Deported',
            'New Contract',
            'Redundant',
            'Stand Down',
            'Transferred',
            'Project Completion',
            'Suspended',
            'Did not pass trade test'
        ];

        foreach ($employeeStatues as $status) {
            EmployeeStatus::create([
                'name' => $status
            ]);
        }
    }
}
