<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $businesses = [
            'Larkin Enterprise Ltd - Port Moresby',
            'Larkin Enterprise Ltd - Lae',
            'Ad Focus',
            'Yellow Jacket Security Ltd - Port Moresby',
            'Yellow Jacket Security Ltd - Lae',
            'Wave Restaurant',
            'Caroline\'s Diner'
        ];

        foreach ($businesses as $business) {
            Business::create([
                'name' => $business
            ]);
        }

    }
}
