<?php

namespace Database\Seeders;

use App\Models\TaxTable;
use App\Models\TaxTableRange;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaxRangesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxRanges = [
            [
                'description' => 'Does not exceed K20,000.00',
                'from' => '0.01',
                'to' => '769.00',
                'percentage' => '0',
            ],
            [
                'description' => 'Exceeds K20,001.00 but does not exceeds K33,000.00',
                'from' => '769.01',
                'to' => '1269.00',
                'percentage' => '30',
            ],
            [
                'description' => 'Exceeds K33,001.00 but does not exceeds K70,000.00',
                'from' => '1269.01',
                'to' => '2692.00',
                'percentage' => '35',
            ],
            [
                'description' => 'Exceeds 70,001.00 but does not exceeds K250,000.00',
                'from' => '2692.01',
                'to' => '9615.00',
                'percentage' => '40',
            ],
            [
                'description' => 'Exceeds K250,000.01',
                'from' => '9615.01',
                'to' => null,
                'percentage' => '42',
            ],
        ];

        $taxTable = TaxTable::create([
            'description' => 'Tax Table 1',
            'effective_date' => now(),
            'created_by' => 1,
            'is_active' => true
        ]);

        foreach ($taxRanges as $taxRange) {
            $taxTable->tax_table_ranges()->create($taxRange);
        }
    }
}
