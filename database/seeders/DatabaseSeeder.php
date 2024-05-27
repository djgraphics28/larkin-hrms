<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\WeekDaySeeder;
use Database\Seeders\BusinessSeeder;
use Database\Seeders\LoanTypeSeeder;
use Database\Seeders\LeaveTypeSeeder;
use Database\Seeders\TaxRangesSeeder;
use Database\Seeders\WorkshiftSeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\SuperAdminSeeder;
use Database\Seeders\CompanyBankSeeder;
use Database\Seeders\DesignationSeeder;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\EmployeeStatusSeeder;
use Database\Seeders\EmailTemplateTypeSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(BusinessSeeder::class);
        $this->call(SuperAdminSeeder::class);
        $this->call(WeekDaySeeder::class);
        $this->call(WorkshiftSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(EmployeeStatusSeeder::class);
        $this->call(LeaveTypeSeeder::class);
        $this->call(LoanTypeSeeder::class);
        $this->call(EmailTemplateTypeSeeder::class);
        $this->call(EmailVariableSeeder::class);
        $this->call(CompanyBankSeeder::class);
        $this->call(TaxRangesSeeder::class);

        Artisan::call('app:generate-fortnight 2023-12-28');
    }
}
