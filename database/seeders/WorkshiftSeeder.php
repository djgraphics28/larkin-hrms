<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Workshift;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WorkshiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Workshift::create([
            'title' => 'Regular Dayshift',
            'description' => 'Regular Dayshift',
            'number_of_hours' => 9,
            'start' => Carbon::createFromTime(8, 0), // Formatting start time using Carbon
            'end' => Carbon::createFromTime(17, 0), // Formatting end time using Carbon
            'created_at' => now(),
            'updated_at' => now() // 'update_at' corrected to 'updated_at'
        ]);
    }
}
