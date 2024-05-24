<?php

namespace App\Livewire\Payroll;

use App\Models\Designation;
use Livewire\Component;
use App\Models\Employee;
use App\Models\Department;

class SaveFilterComponent extends Component
{
    public $updateMode = false;
    public $departments = [];
    public $selectedDepartment;
    public $designations = [];
    public $selectedDesignation;
    public $employees = [];
    public $selectAllEmployees = [];
    public $selectedEmployeeRows = [];
    public function render()
    {
        return view('livewire.payroll.save-filter-component', [
            'records' => $this->records
        ]);
    }

    public function getRecordsProperty()
    {

    }

    public function mount()
    {
        $this->departments = Department::where('is_active', true)->get();
        $this->designations = Designation::where('is_active', true)->get();
        $this->employees = Employee::where('is_discontinued', false)->get();
    }

    public function addNew()
    {
        $this->dispatch('show-add-modal');
        $this->updateMode = false;
    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'title' => 'required'
        ]);


    }
}
