<?php

namespace Database\Seeders;

use App\Models\CompanyBank;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CompanyBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultBank = [
            'bank_name' => 'Bank South Pacific',
            'account_name' => 'Larkin Enterprises Limited',
            'account_number' => '7009276416',
            'account_bsb' => '088-950',
            'is_active' => true
        ];

        CompanyBank::create($defaultBank);
    }
}
