<?php

namespace App\Livewire\Payroll;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Department;
use App\Models\SaveFilter;
use App\Models\Designation;
use App\Models\BusinessUser;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SaveFilterComponent extends Component
{
    use WithPagination, LivewireAlert;

    public $businessId;
    public $perPage = 10;
    public $search = '';

    public $editFilterId;

    public $updateMode = false;
    public $departments = [];
    public $selectedDepartment;
    public $designations = [];
    public $selectedDesignation;
    public $employees = [];

    public $selectAllEmployees = false;
    public $selectedEmployeeRows = [];

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    public $title = '';

    #[Title('Save Filter')]
    public function render()
    {
        return view('livewire.payroll.save-filter-component', [
            'records' => $this->records
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getRecordsProperty()
    {
        return SaveFilter::where('business_id', $this->businessId)->search(trim($this->search))->latest()->paginate($this->perPage);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRows = $this->records->pluck('id');
        } else {
            $this->selectedRows = [];
        }
    }

    public function updatedSelectAllEmployees($value)
    {
        if ($value) {
            $this->selectedEmployeeRows = $this->employees->pluck('id');
        } else {
            $this->selectedEmployeeRows = [];
        }
    }

    public function updatedSelectedEmployeeRows()
    {
        if (count($this->employees) == count($this->selectedEmployeeRows)) {
            $this->selectAllEmployees = true;
        } else {
            $this->selectAllEmployees = false;
        }
    }

    public function mount()
    {
        $this->businessId = BusinessUser::where('user_id', Auth::user()->id)->where('is_active', true)->first()->business_id;
        $this->departments = Department::where('is_active', true)->get();
        $this->designations = Designation::where('is_active', true)->get();
        $this->employees = Employee::where('is_discontinued', false)
            ->where('business_id', $this->businessId)
            ->get();
    }

    public function addNew()
    {
        $this->resetFields();
        $this->dispatch('show-add-modal');
        $this->updateMode = false;
    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'title' => 'required'
        ]);

        if (count($this->selectedEmployeeRows) == 0) {
            $this->alert('warning', 'Please Select Employees!');
            return;
        }

        $create = SaveFilter::create([
            'title' => $this->title,
            'business_id' => $this->businessId,
            'employee_lists' => json_encode($this->selectedEmployeeRows),
            'selected_department' => $this->selectedDepartment ? json_encode(array_map('intval', $this->selectedDepartment)) : null,
            'selected_designation' => $this->selectedDesignation ? json_encode(array_map('intval', $this->selectedDesignation)) : null,
        ]);

        if ($create) {
            if ($saveAndCreateNew) {
                $this->alert('success', 'New Saved Filter has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');

                $this->alert('success', 'New Saved Filter has been save successfully!');
            }
            $this->resetFields();
        }
    }

    public function edit($id)
    {
        $this->dispatch('show-add-modal');
        $this->updateMode = true;
        $this->editFilterId = $id;
        $data = SaveFilter::find($id);
        $this->title = $data->title;
        // Decode the JSON strings to arrays of integers
        $this->selectedDepartment = $data->selected_department ? array_map('intval', json_decode($data->selected_department, true)) : [];
        $this->selectedDesignation = $data->selected_designation ? array_map('intval', json_decode($data->selected_designation, true)) : [];
        $this->employees = Employee::where('is_discontinued', false)->where('business_id', $this->businessId)->whereIn('id', json_decode($data->employee_lists))->get();

        $this->updatedSelectAllEmployees(true);
        $this->selectAllEmployees = true;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required'
        ]);

        if (count($this->selectedEmployeeRows) == 0) {
            $this->alert('warning', 'Please Select Employees!');
            return;
        }
        $saveFilter = SaveFilter::find($this->editFilterId);
        $saveFilter->update([
            'title' => $this->title,
            'business_id' => $this->businessId,
            'employee_lists' => json_encode($this->selectedEmployeeRows),
            'selected_department' => $this->selectedDepartment ? json_encode(array_map('intval', $this->selectedDepartment)) : null,
            'selected_designation' => $this->selectedDesignation ? json_encode(array_map('intval', $this->selectedDesignation)) : null,
        ]);

        if ($saveFilter) {
            $this->alert('success', $saveFilter->title . ' Filter has been save successfully!');
            $this->dispatch('hide-add-modal');
        }
    }

    public function resetFields()
    {
        $this->updatedSelectAllEmployees(false);
        $this->selectAllEmployees = false;
        $this->title = '';
        $this->selectedDepartment = '';
        $this->selectedDesignation = '';
    }

    public function updatedSelectedDepartment()
    {
        $this->filterEmployees();
    }

    public function updatedSelectedDesignation()
    {
        $this->filterEmployees();
    }

    public function filterEmployees()
    {
        $this->employees = Employee::where('is_discontinued', false)
            ->where('business_id', $this->businessId)
            ->when($this->selectedDepartment, function ($query) {
                $query->whereIn('department_id', $this->selectedDepartment);
            })
            ->when($this->selectedDesignation, function ($query) {
                $query->whereIn('designation_id', $this->selectedDesignation);
            })
            ->get();
    }
}
