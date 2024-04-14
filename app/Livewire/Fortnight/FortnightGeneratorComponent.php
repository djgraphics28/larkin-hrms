<?php

namespace App\Livewire\Fortnight;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Fortnight;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class FortnightGeneratorComponent extends Component
{
    use LivewireAlert;

    #[Url]
    public $fnStartDate = '';

    #[Title('Fortnight Generator')]
    public function render()
    {
        return view('livewire.fortnight.fortnight-generator-component',[
            'records' => $this->records
        ]);
    }

    public function getRecordsProperty()
    {
        $date = Carbon::parse($this->fnStartDate);

        if($date->month == 12) {
            $date = (int)$date->year + 1;
        }

        return Fortnight::search(trim($date))->get();
    }

    public function generate()
    {
        $this->validate([
            'fnStartDate' => 'required'
        ]);

        $date = Carbon::parse($this->fnStartDate);

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

            sleep(2);

            $this->alert('success', "Fortnight generation for year {$year} generated successfully!");
        } else {
            $this->alert('error', "Fortnight generation is only done for December!");
        }

    }
}
