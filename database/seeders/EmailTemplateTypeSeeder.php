<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplateType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EmailTemplateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Loan Request',
            'Leave Request',
            'User Management',
        ];

        foreach ($types as $type) {
            EmailTemplateType::create([
                'name' => $type
            ]);
        }
    }
}
