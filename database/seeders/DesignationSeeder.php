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
            'Cook',
            'Accounts Clerk',
            'Aluminium Fitter',
            'Cleaner',
            'Financial Controller',
            'G&A Fitter',
            'Glass Cutter',
            'Glass Fitter',
            'Joiner',
            'Joinery',
            'Lead Carpenter',
            'Leadman',
            'Logistic Officer',
            'Mason',
            'Mechanic',
            'Office Clerk',
            'Foreman',
            'Plumber',
            'Security Guard',
            'Stone Installer',
            'Storeman',
            'Trade Supervisor',
            'Trade Supervisor (Finishing)',
            'Trade Supervisor-Welding',
            'Trade Supervisor/Coordinator (Carpentry)',
            'Trade Supervisor/Coordinator (Electrical)',
            'Trade Supervisor/Coordinator (Finishing)',
            'Carpenter',
            'Admin Director',
            'Fabricator',
            'Graphic Artist',
            'Managing Director',
            'Marketing',
            'Operations Manager',
            'Production Supervisor',
            'Production Staff',
            'Works Manager',
            'Sales & Marketing Manager',
            'Admin Director',
            'Security Manager',
            'General Manager',
            'Reserved Guard',
            'Supervisor',
            'Kitchen Staff',
            'Chef',
            'Assistant Chef',
            'Head Chef',
            'Wait Staff',
            'OJT',
            'Restaurant Manager',
        ];

        foreach ($designations as $designation) {
            Designation::create([
                'name' => $designation
            ]);
        }
    }
}
