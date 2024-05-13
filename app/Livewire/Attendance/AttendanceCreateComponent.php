<?php

namespace App\Livewire\Attendance;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\Attendance;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AttendanceCreateComponent extends Component
{
    use LivewireAlert;

    public $businessId;
    public $fortnights = [];
    public $search = '';
    public $selectedFortnight = '';
    public $days = [];
    public $selectedDay = '';
    public $records = [];

    // public $rules = [
    //     'records.*.time_in' => 'required',
    //     'records.*.time_out' => 'required',
    //     'records.*.time_in_2' => 'required',
    //     'records.*.time_out_2' => 'required',
    // ];

    public function mount()
    {
        $this->businessId = BusinessUser::where('user_id', auth()->user()->id)
            ->where('is_active', true)
            ->first()->business_id;

        $this->fortnights = Fortnight::all();

        $this->getRecords();
        $this->getDays();

        // Fetch existing attendance data
        foreach ($this->records as $record) {
            $existingAttendance = Attendance::where('employee_number', $record->employee_number)->where('fortnight_id', $this->selectedFortnight)->where('date', $this->selectedDay)->latest()->first();
            if ($existingAttendance) {
                $this->attendances[$record->employee_number] = [
                    'time_in' => $existingAttendance->time_in,
                    'time_out' => $existingAttendance->time_out,
                    'time_in_2' => $existingAttendance->time_in_2,
                    'time_out_2' => $existingAttendance->time_out_2,
                ];
            }
        }
    }

    public function render()
    {
        return view('livewire.attendance.attendance-create-component');
    }

    public function getRecords()
    {
        $this->records = Employee::with('workshift')
            ->search(trim($this->search))
            ->orderBy('employees.employee_number', 'ASC')
            ->limit(2) // Adjust limit as needed (optional with WithPagination)
            ->get();
    }

    public function getDays()
    {
        if (!$this->selectedFortnight) {
            $this->days = [];
            $this->selectedDay = '';
            return;
        }

        $fn = Fortnight::find($this->selectedFortnight);

        $start_date = Carbon::parse($fn->start);
        $end_date = Carbon::parse($fn->end);

        $days = [];
        $first = true;

        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $days[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('D'),
                'is_checked' => $first, // Set based on your requirements
            ];
            $first = false;
        }

        $this->days = $days;
    }

    public function updatedSelectedFortnight()
    {
        $this->selectedDay = ''; // Reset selected day when fortnight changes
        $this->getRecords();
        $this->getDays();
    }

    public function store()
    {
        $validatedData = $this->validate([
            'records.*.time_in' => 'required|date_format:H:i',
            'records.*.time_out' => 'required|date_format:H:i',
            'records.*.time_in_2' => 'required|date_format:H:i',
            'records.*.time_out_2' => 'required|date_format:H:i',
        ]);

        dd($validatedData);

        foreach ($this->attendances as $employeeId => $attendance) {
            Attendance::create([
                'employee_number' => $employeeId,
                'time_in' => $attendance['time_in'],
                'time_out' => $attendance['time_out'],
            ]);
        }

        $this->alert('success', 'Attendance has been saved successfully!');
    }
}
