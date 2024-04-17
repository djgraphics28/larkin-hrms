<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Fortnight;
use Illuminate\Console\Command;

class GenerateFortnight extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-fortnight {date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Fortnight';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->argument('date');

        $date = Carbon::parse($date);

        if($date->month == 12) {
            $year = (int)$date->year + 1;
            $year2 = (int)substr($date->year, 2) + 1;

            $period = [];

            $periodStart = $date->copy(); // Initialize period start date

            for ($y = 1; $y <= 27; $y++) {
                $periodEnd = $periodStart->copy()->addDays(14)->subDay(); // Calculate period end date
                $period[] = ['start' => $periodStart, 'end' => $periodEnd]; // Store period start and end dates

                $periodStart = $periodEnd->copy()->addDay(); // Set the next period start date

                // Ensure the last period ends on 31-Dec of the current year
                if ($y === 26) {
                    $period[] = ['start' => $periodStart, 'end' => Carbon::create($year, 12, 31)->endOfDay()];
                }
            }

            foreach ($period as $i => $p) {
                if ($i >= 26) {
                    break; // Stop the loop after 26 periods
                }
                // Add leading zero if iteration number is less than 10
                $iteration = ($i < 9) ? '0' . ($i + 1) : ($i + 1);
                $code = "FN{$year2}{$iteration}";

                Fortnight::updateOrCreate(
                    [
                        'code' => $code
                    ],
                    [
                        'fn_number' => $iteration,
                        'code' => $code,
                        'start' => $p['start']->toDateString(), // Convert to MySQL date string
                        'end' => $p['end']->toDateString(), // Convert to MySQL date string
                        'year' => $year,
                    ]
                );
            }
        }
    }
}
