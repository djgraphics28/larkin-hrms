<?php

namespace App\Livewire\Employee\Partials;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Workshift;
use App\Models\Department;
use App\Models\EmployeeStatus;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class WorkInfoComponent extends Component
{
    use LivewireAlert;
    public $id;
    public $employee_number;
    public $label;
    public $employee_status;
    public $department;
    public $workshift;
    public $joining_date;
    public $end_date;
    public $deployment_date_home_country;
    public $nasfund_number;

    public $departments = [];
    public $workshifts = [];
    public $employeeStatuses = [];

    public function render()
    {
        return view('livewire.employee.partials.work-info-component');
    }

    public function mount()
    {
        $this->departments = Department::where('is_active', 1)->get();
        $this->workshifts = Workshift::all();
        $this->employeeStatuses = EmployeeStatus::where('is_active', 1)->get();

        $data = Employee::find($this->id);
        $this->employee_number = $data->employee_number;
        $this->label = $data->label;
        $this->employee_status = $data->employee_status_id;
        $this->department = $data->department_id;
        $this->workshift = $data->workshift_id;
        $this->joining_date = $data->joining_date;
        $this->end_date = $data->end_date;
        $this->nasfund_number = $data->nasfund_number;
        $this->deployment_date_home_country = $data->deployment_date_home_country;
    }

    public function update()
    {
        $this->validate([
            'employee_number' => 'required',
            'label' => 'required',
            'employee_status' => 'required',
            'department' => 'required',
            'workshift' => 'required',
            'joining_date' => 'required',
            // 'end_date' => 'required',
            // 'nasfund_number' => 'required',
        ]);

        Employee::find($this->id)->update([
            'employee_number' => $this->employee_number,
            'label' => $this->label,
            'employee_status_id' => $this->employee_status,
            'department_id' => $this->department,
            'workshift_id' => $this->workshift,
            'joining_date' => $this->joining_date,
            'end_date' => $this->end_date,
            'nasfund_number' => $this->nasfund_number,
            'deployment_date_home_country' => $this->deployment_date_home_country
        ]);

        $this->alert('success', 'Work Info Updated successfully!');

    }
}
