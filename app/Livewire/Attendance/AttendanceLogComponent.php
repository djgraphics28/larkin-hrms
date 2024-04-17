<?php

namespace App\Livewire\Attendance;

use DateTime;
use Carbon\Carbon;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\Attendance;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\EmployeeHours;
use Livewire\Attributes\Title;

class AttendanceLogComponent extends Component
{
    use WithPagination;

    // filters
    public $perPage = 10;
    public $search = '';
    public $fortnights = [];
    #[Url]
    public $selectedFN;
    public $records = [];
    public $ranges = [];

    public $fn_start;
    public $fn_end;
    public $fn_id;

    public $selectAll = false;
    public $selectedRows = [];

    #[Title('Attendance Logs')]
    public function render()
    {
        return view('livewire.attendance.attendance-log-component');
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRows = $this->records->pluck('id');
        } else {
            $this->selectedRows = [];
        }
    }

    public function mount()
    {
        $this->fortnights = Fortnight::all();
        $this->generate();
        $this->getDailyHr();
    }

    public function generate()
    {
        $this->validate([
            'selectedFN' => 'required'
        ]);

        $employees = Employee::withCount('attendances')
            ->search(trim($this->search))->get();

        $getDates = Fortnight::where('id', $this->selectedFN)->first();

        $this->fn_start = $getDates->start;
        $this->fn_end = $getDates->end;
        $this->fn_id = $getDates->id;

        $ranges = $this->getRanges();

        sleep(2);

        $this->records = $employees;
        $this->ranges = $ranges;



        Helpers::computeHours($this->selectedFN);
    }

    public function getRanges()
    {
        $dateRangeArray = [];

        $data = Fortnight::where('id', $this->selectedFN)->first();

        if (!$data) {
            return $dateRangeArray;
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $data->start);
        $endDate = Carbon::createFromFormat('Y-m-d', $data->end);

        // Iterate through each day between start and end dates
        $x = 1;
        while ($startDate->lte($endDate)) {
            // Format the date and day
            $fulldate = $startDate->format('Y-m-d');
            $formattedDate = $startDate->format('d-M');
            $formattedDay = $startDate->format('D');

            // Push to the array
            $dateRangeArray[] = ['day' => $formattedDay . ($x > 7 ? "2" : ""), 'date' => $formattedDate, "fortnight_id" => $data->id, "full_date" => $fulldate];

            // Move to the next day
            $startDate->addDay();
            $x++;
        }

        return $dateRangeArray;
    }

    public function getDailyHr()
    {
        $hourArray = [];

        $data = Fortnight::where('id', $this->selectedFN)->first();

        if (!$data) {
            return $hourArray;
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $data->start);
        $endDate = Carbon::createFromFormat('Y-m-d', $data->end);

        // dd($startDate, $endDate);


    }
}
