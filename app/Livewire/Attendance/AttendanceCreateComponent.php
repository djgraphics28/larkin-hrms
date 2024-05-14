<?php

namespace App\Livewire\Attendance;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Designation;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AttendanceCreateComponent extends Component
{
    use LivewireAlert;

    public $businessId;
    public $fortnights = [];

    #[Url]
    public $search = '';
    #[Url]
    public $selectedFortnight = '';
    public $days = [];
    #[Url]
    public $selectedDay = '';
    public $records = [];
    public $attendances = [];
    #[Url]
    public $selectedDepartment = '';
    public $departments = [];
    #[Url]
    public $selectedDesignation = '';
    public $designations = [];

    public function mount()
    {
        $this->businessId = BusinessUser::where('user_id', auth()->user()->id)
            ->where('is_active', true)
            ->first()->business_id;

        $this->designations = Designation::where('is_active', 1)->get();

        $this->departments = Department::where('is_active', 1)->get();

        $this->fortnights = Fortnight::all();

        $this->getDays();

        $this->records = $this->getRecords();

        // Fetch existing attendance data
        foreach ($this->records as $record) {
            $existingAttendance = Attendance::where('employee_number', $record->employee_number)
                ->where('fortnight_id', $this->selectedFortnight)
                ->where('date', $this->selectedDay)
                // ->when($this->selectedDay, function ($query, $selectedDay) {
                //     $query->where('date', $selectedDay);
                // })
                ->latest()
                ->first();

            if ($existingAttendance) {
                $this->attendances[$record->employee_number] = [
                    'time_in' => $existingAttendance->time_in,
                    'time_out' => $existingAttendance->time_out,
                    'time_in_2' => $existingAttendance->time_in_2,
                    'time_out_2' => $existingAttendance->time_out_2,
                ];
            } else {
                $this->attendances[$record->employee_number] = [
                    'time_in' => null,
                    'time_out' => null,
                    'time_in_2' => null,
                    'time_out_2' => null,
                ];
            }
        }
    }

    #[Title('Create Attendance')]
    public function render()
    {
        return view('livewire.attendance.attendance-create-component', [
            'records' => $this->records
        ]);
    }

    public function getRecords()
    {
        $selectedFortnight = $this->selectedFortnight;
        $selectedDay = $this->selectedDay;

        return Employee::with([
            'workshift',
            'attendances' => function ($query) use ($selectedFortnight, $selectedDay) {
                $query->where('fortnight_id', $selectedFortnight)
                ->when($selectedDay, function ($query, $selectedDay) {
                    $query->where('date', $selectedDay);
                });
            }
        ])
        ->search(trim($this->search))
        ->where('business_id', $this->businessId)
        ->where('is_discontinued', 0)
        ->when($this->selectedDepartment, function($query) {
            $query->where('department_id', $this->selectedDepartment);
        })
        ->when($this->selectedDesignation, function($query) {
            $query->where('designation_id', $this->selectedDesignation);
        })
        ->orderBy('employee_number', 'ASC')
        // ->limit(2) // Adjust limit as needed (optional with WithPagination)
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
            // if($first == true) {
            //     $this->selectedDay = $date->format('Y-m-d');
            // }
            $first = false;
        }

        $this->days = $days;
    }

    public function updatedSelectedFortnight()
    {
        $this->selectedDay = ''; // Reset selected day when fortnight changes
        $this->getDays();
        $this->records = $this->getRecords();
        // Set the selectedDay to the first day of the selected fortnight
        if (!empty($this->days[0]['date'])) {
            $this->selectedDay = $this->days[0]['date'];
        }
        $this->mount();

    }

    public function updatedSearch()
    {
        $this->resetValidation();
        $this->mount();
    }

    public function updatedSelectedDay()
    {
        // dd($this->selectedDay);
        $this->resetValidation(); // Reset validation errors
        $this->mount();
    }

    public function updatedSelectedDepartment()
    {
        $this->resetValidation(); // Reset validation errors
        $this->mount();
    }

    public function updatedSelectedDesignation()
    {
        $this->resetValidation(); // Reset validation errors
        $this->mount();
    }

    public function store()
    {
        // $messages = [
        //     'attendances.*.time_out.required' => 'The time out field is required.',
        //     'attendances.*.time_in_2.required' => 'The 2nd time in field is required.',
        //     'attendances.*.time_out_2.required' => 'The 2nd time out field is required.',
        //     'attendances.*.time_in.date_format' => 'The time in field must be a valid time format (HH:MM).',
        //     'attendances.*.time_out.date_format' => 'The time out field must be a valid time format (HH:MM).',
        //     'attendances.*.time_in_2.date_format' => 'The 2nd time in field must be a valid time format (HH:MM).',
        //     'attendances.*.time_out_2.date_format' => 'The 2nd time out field must be a valid time format (HH:MM).',
        // ];

        // // Initialize validation rules
        // $rules = [
        //     'selectedFortnight' => 'required',
        //     'attendances.*.time_out' => 'required_if:attendances.*.time_in,*',
        //     'attendances.*.time_in_2' => 'required_if:attendances.*.time_in,*',
        //     'attendances.*.time_out_2' => 'required_if:attendances.*.time_in,*',
        // ];

        if($this->selectedFortnight == '') {
            $this->alert('error', 'Please select fortnight first!');
            return;
        }
        if($this->selectedDay == '') {
            $this->alert('error', 'Please select Day!');
            return;
        }

        // // Validate the data
        // // $validatedData = $this->validate($rules, $messages);

        // foreach ($this->attendances as $employeeId => $attendance) {
        //     // Only add 'time_in' validation rule if 'time_in' field is present and not null
        //     if (isset($attendance['time_in'])) {
        //         $rules["attendances.$employeeId.time_in"] = 'required|date_format:H:i';
        //     }
        // }

        // $validatedData = $this->validate($rules, $messages);

        foreach ($this->attendances as $employeeId => $attendance) {
            if (!empty($attendance['time_in'])) {
                Attendance::updateOrCreate(
                    [
                        'fortnight_id' => $this->selectedFortnight,
                        'employee_number' => $employeeId,
                        'date' => $this->selectedDay,
                    ],
                    [
                        'fortnight_id' => $this->selectedFortnight,
                        'employee_number' => $employeeId,
                        'date' => $this->selectedDay,
                        'created_by' => Auth::user()->id,
                        'time_in' => $attendance['time_in'],
                        'time_out' => $attendance['time_out'],
                        'time_in_2' => $attendance['time_in_2'],
                        'time_out_2' => $attendance['time_out_2']
                    ]
                );
            }
        }

        $this->alert('success', 'Attendance has been saved successfully!');
    }
}
