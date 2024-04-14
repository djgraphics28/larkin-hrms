<?php

namespace App\Livewire\Employee;

use Livewire\Component;

class EmployeeInfoComponent extends Component
{
    public $label;
    public $id;
    public function render()
    {
        return view('livewire.employee.employee-info-component');
    }
}
