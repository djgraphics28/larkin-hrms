<?php

namespace App\Livewire\Attendance;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Fortnight;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
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

    public $selectAll = false;
    public $selectedRows = [];

    #[Title('Attendance Logs')]
    public function render()
    {
        return view('livewire.attendance.attendance-log-component');
    }

    public function updatedSelectAll($value)
    {
        if($value){
            $this->selectedRows = $this->records->pluck('id');
        }else{
            $this->selectedRows = [];
        }
    }

    public function mount()
    {
        $this->fortnights = Fortnight::all();
        $this->generate();
    }

    public function generate()
    {
        $this->validate([
            'selectedFN' => 'required'
        ]);

        $employees = Employee::withCount('attendances')
            ->search(trim($this->search))->get();

        $ranges = $this->getRanges();

        sleep(2);

        $this->records = $employees;
        $this->ranges = $ranges;
    }

    public function getRanges()
    {
        $dateRangeArray = [];

        $data = Fortnight::where('code', $this->selectedFN)->first();

        if (!$data) {
            return $dateRangeArray;
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $data->start);
        $endDate = Carbon::createFromFormat('Y-m-d', $data->end);

        // Iterate through each day between start and end dates
        $x = 1;
        while ($startDate->lte($endDate)) {
            // Format the date and day
            $formattedDate = $startDate->format('d-M');
            $formattedDay = $startDate->format('D');

            // Push to the array
            $dateRangeArray[] = ['day' => $formattedDay.($x > 7 ? "2":""), 'date' => $formattedDate];

            // Move to the next day
            $startDate->addDay();
            $x++;
        }

        return $dateRangeArray;
    }

}
