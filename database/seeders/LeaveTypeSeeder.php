<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveType::create([
            'name' => 'Leave with pay',
            'is_payable' => true,
        ]);

        LeaveType::create([
            'name' => 'Leave without pay',
            'is_payable' => false,
        ]);
    }
}
