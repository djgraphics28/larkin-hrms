<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            'Admin Clerk',
            'Bricklayer',
            'Driver',
            'Electrician',
            'General Labourer',
            'Helper',
            'Painter',
            'Plasterer',
            'Plumber Helper',
            'Tiler',
            'Welder',
        ];

        foreach ($designations as $designation) {
            Designation::create([
                'name' => $designation
            ]);
        }
    }
}
