<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the path to the SQL file
        $path = database_path('seeders/sqls/departments.sql');

        // Read the SQL file
        $sql = File::get($path);

        // Execute the SQL statements
        DB::unprepared($sql);
    }
}
