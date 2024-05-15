<?php

namespace App\Livewire\Attendance;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\Attendance;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AttendanceAdjustmentComponent extends Component
{
    use LivewireAlert;

    public $businessId;
    public $employees = [];
    public $employee;
    public $attendanceId;
    public $attendance = [];
    #[Url]
    public $selectedEmployee = '';

    #[Url]
    public $selectedDate = '';

    public $time_in = '';
    public $time_out = '';
    public $time_in_2 = '';
    public $time_out_2 = '';
    public $notes = '';
    public $is_break = false;

    public function mount()
    {
        $this->businessId = BusinessUser::where('user_id',Auth::user()->id)->where('is_active', true)->first()->business_id;
        $this->employees = Employee::where('business_id', $this->businessId)->orderBy('first_name','ASC')->get();

        if($this->selectedEmployee !== '') {
            $this->searchEmployee();
        }

        if($this->selectedDate !== '') {
            $this->searchAttendance();
        }
    }
    #[Title('Attendance Adjustment')]
    public function render()
    {
        return view('livewire.attendance.attendance-adjustment-component');
    }

    public function searchEmployee()
    {
        $this->employee = Employee::where('employee_number', $this->selectedEmployee)->first();
        $this->selectedDate = '';
        $this->searchAttendance();
    }

    public function searchAttendance()
    {
        // dd($this->selectedDate);
        $data = Attendance::where('employee_number', $this->selectedEmployee)->where('date', $this->selectedDate)->first();

        if(!empty($data)) {
            $this->attendance = $data;
            $this->attendanceId = $data->id;
            $this->time_in = $data->time_in;
            $this->time_out = $data->time_out;
            $this->time_in_2 = $data->time_in_2;
            $this->time_out_2 = $data->time_out_2;
            $this->notes = $data->notes;
            $this->is_break = $data->is_break == 1 ? true : false;
        } else {
            $this->attendance = [];
        }
    }

    public function update()
    {
        $attendance = Attendance::find($this->attendanceId);
        $attendance->update([
            'time_in' => $this->time_in,
            'time_out' => $this->time_out,
            'time_in_2' => $this->time_in_2,
            'time_out_2' => $this->time_out_2,
            'notes' => $this->notes,
            'is_break' => $this->is_break,
            'updated_by' => Auth::user()->id,
        ]);

        sleep(1);
        $this->alert('success', 'Attendance has been saved successfully!');
    }
}
