<?php

namespace Database\Seeders;

use App\Models\WeekDay;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WeekDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = [
            ['day' => 'Sunday', 'code' => 'SUN'],
            ['day' => 'Monday', 'code' => 'MON'],
            ['day' => 'Tuesday', 'code' => 'TUE'],
            ['day' => 'Wednesday', 'code' => 'WED'],
            ['day' => 'Thursday', 'code' => 'THU'],
            ['day' => 'Friday', 'code' => 'FRI'],
            ['day' => 'Saturday', 'code' => 'SAT']
        ];

        foreach ($days as $day) {
            WeekDay::create([
                'day' => $day['day'],
                'code' => $day['code']
            ]);
        }
    }
}
