<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the path to the SQL file
        $path = database_path('seeders/sqls/employees.sql');

        // Read the SQL file
        $sql = File::get($path);

        // Execute the SQL statements
        DB::unprepared($sql);
    }
}
