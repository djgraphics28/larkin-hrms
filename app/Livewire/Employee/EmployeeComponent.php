<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\EmployeeStatus;
use Livewire\Attributes\Title;
use App\Exports\EmployeeExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class EmployeeComponent extends Component
{
    use WithPagination, LivewireAlert;

    public $label;
    public $businessId;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    #[Url]
    public $perPage = 10;
    #[Url]
    public $search = '';
    #[Url]
    public $sortByLabel = '';
    #[Url]
    public $sortByDepartment = '';
    #[Url]
    public $sortByDesignation = '';
    #[Url]
    public $sortByEmployeeStatus = '';

    public $modalTitle = 'Add New Business|Branch';
    public $updateMode = false;

    public $name;
    public $edit_id;

    public $departments = [];
    public $designations = [];
    public $employeeStatuses = [];

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    #[Title('Employees')]

    public function render()
    {
        return view('livewire.employee.employee-component',[
            'records' => $this->records
        ]);
    }

    public function mount()
    {
        $this->departments = Department::where('is_active',1)->get();
        $this->designations = Designation::where('is_active',1)->get();
        $this->employeeStatuses = EmployeeStatus::where('is_active',1)->get();

        $this->businessId = BusinessUser::where('user_id',Auth::user()->id)->where('is_active', true)->first()->business_id;

    }

    public function getRecordsProperty()
    {
        $label = $this->label == 'all' ? $this->sortByLabel : $this->label;

        return Employee::search(trim($this->search))
            ->when($label, function($query) use ($label) {
                $query->where('label', $label);
            })
            ->when($this->sortByDepartment, function($query) use ($label) {
                $query->where('department_id', $this->sortByDepartment);
            })
            ->when($this->sortByDesignation, function($query) {
                $query->where('designation_id', $this->sortByDesignation);
            })
            ->when($this->sortByEmployeeStatus, function($query) {
                $query->where('employee_status_id', $this->sortByEmployeeStatus);
            })
            ->where('business_id', $this->businessId)
            ->paginate($this->perPage);
    }

    public function updatedSelectAll($value)
    {
        if($value){
            $this->selectedRows = $this->records->pluck('id');
        }else{
            $this->selectedRows = [];
        }
    }

    public function addNew()
    {
        $this->resetInputFields();
        $this->dispatch('show-add-modal');
        $this->modalTitle = 'Add New Designation';
        $this->updateMode = false;

    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'name' => 'required'
        ]);

        $create = Designation::create([
            'name' => $this->name
        ]);

        if($create){
            $this->resetInputFields();
            if($saveAndCreateNew) {
                $this->alert('success', 'New Designation has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'New Designation has been save successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->contact_number = '';
        $this->address = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = Designation::find($id);
        $this->name = $data->name;
        $this->modalTitle = 'Edit '.$this->name;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        $data = Designation::find($this->edit_id);
        $data->update([
            'name' => $this->name
        ]);

        if($data) {
            $this->dispatch('hide-add-modal');

            $this->resetInputFields();

            $this->alert('success', $data->name.' has been updated!');
        }
    }

    public function alertConfirm($id)
    {
        $this->approveConfirmed = $id;

        $this->confirm('Are you sure you want to delete this?', [
            'confirmButtonText' => 'Yes Delete it!',
            'onConfirmed' => 'remove',
        ]);
    }

    public function remove()
    {
        $delete = Designation::find($this->approveConfirmed);
        $name = $delete->name;
        $delete->delete();
        if($delete){
            $this->alert('success', $name.' has been removed!');
        }
    }

    public function export()
    {
        return Excel::download(new EmployeeExport, 'employee.xlsx');
    }
}
