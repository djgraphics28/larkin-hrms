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

            for ($y = 1; $y <= 27; $y++) {
                $period[] = $date->copy(); // Copy the date object to avoid modifying the original
                $date->addDays(14); // Add 14 days to the date
            }

            for ($i = 0; $i < 26; $i++) {
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
                        'start' => $period[$i],
                        'end' => $period[$i + 1],
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
