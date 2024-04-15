<?php

namespace App\Livewire\Employee;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Employee;

class EmployeeInfoComponent extends Component
{
    public $label;
    public $id;
    public $image = '';
    public $fullName = '';
    public $position = '';

    public $age = 0;
    public $gender = 'Male';

    public function render()
    {
        return view('livewire.employee.employee-info-component');
    }

    public function mount()
    {
        $data = Employee::find($this->id);
        $this->fullName = $data->first_name. " " . $data->last_name;
        $this->position = $data->designation->name;
        $this->gender = $data->gender;
        $this->age = Carbon::parse($data->birth_date)->age;
    }
}
