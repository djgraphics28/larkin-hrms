<?php

namespace Database\Seeders;

use App\Models\LoanType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LoanTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loanTypes = [
            'Cash-Advance',
            'Loan'
        ];

        foreach ($loanTypes as $loanType) {
            LoanType::create([
                'name' => $loanType
            ]);
        }
    }
}
