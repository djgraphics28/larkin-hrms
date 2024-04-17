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
            [
                'name' => 'Larkin POM Branch',
                'code' => 'LKP'
            ],
            [
                'name' => 'Larkin Lae Branch',
                'code' => 'LKL'
            ],
            [
                'name' => 'Ad Focus',
                'code' => 'AFP'
            ],
            [
                'name' => 'Yellow Jacket POM',
                'code' => 'YJP'
            ],
            [
                'name' => 'Yellow Jacket Lae',
                'code' => 'JYL'
            ],
            [
                'name' => 'Wave Restaurant',
                'code' => 'WRP'
            ],
            [
                'name' => 'Caroline\'s Diner',
                'code' => 'CDP'
            ],
        ];

        foreach ($businesses as $business) {
            Business::create([
                'name' => $business['name'],
                'code' => $business['code'],
            ]);
        }

        $business = Business::find(1);
        $business->departments()->attach([1,2,3,4,5,6,7,8]);
    }
}
