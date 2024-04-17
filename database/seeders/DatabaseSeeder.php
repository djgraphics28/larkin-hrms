<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\WeekDaySeeder;
use Database\Seeders\BusinessSeeder;
use Database\Seeders\LeaveTypeSeeder;
use Database\Seeders\WorkshiftSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\DesignationSeeder;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\EmployeeStatusSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(DepartmentSeeder::class);
        $this->call(BusinessSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(WeekDaySeeder::class);
        $this->call(WorkshiftSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(EmployeeStatusSeeder::class);
        $this->call(LeaveTypeSeeder::class);

        Artisan::call('app:generate-fortnight 2023-12-28');
    }
}
