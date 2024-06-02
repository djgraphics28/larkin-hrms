<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Get the path to the SQL file
         $path = database_path('seeders/sqls/businesses.sql');

         // Read the SQL file
         $sql = File::get($path);

         // Execute the SQL statements
         DB::unprepared($sql);

        $business = Business::find(1);
        $business->departments()->attach([1,2,3,4,5,6,7,8]);
    }
}
