<?php

namespace App\Livewire\Payroll;

use App\Models\Payroll;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Department;
use App\Models\SaveFilter;
use App\Models\Designation;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PayrollComponent extends Component
{

    use LivewireAlert, WithPagination;

    public $perPage = 10;
    public $search = '';

    public $businessId;
    public $fortnights = [];
    #[Url]
    public $selectedFN = '';
    public $updateMode = false;

    public $chooseFiltered = false;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    public $departments = [];
    public $selectedDepartment;
    public $designations = [];
    public $selectedDesignation;

    public $employees = [];

    public $selectAllEmployees = false;
    public $selectedEmployeeRows = [];

    public $saveFilters = [];
    public $selectedFilteredEmployees = [];

    #[Title('Payroll')]
    public function render()
    {
        return view('livewire.payroll.payroll-component', [
            'records' => $this->records
        ]);
    }

    public function mount()
    {
        $this->businessId = BusinessUser::where('user_id', Auth::user()->id)->where('is_active', true)->first()->business_id;
        $this->departments = Department::where('is_active', true)->get();
        $this->designations = Designation::where('is_active', true)->get();
        $this->fortnights = Helpers::activeFortnights();
        $this->saveFilters = SaveFilter::where('business_id', $this->businessId)->get();
        $this->employees = Employee::where('is_discontinued', false)
            ->where('business_id', $this->businessId)
                ->get();

    }

    public function getRecordsProperty()
    {
        return Payroll::where('business_id', $this->businessId)->latest()->paginate($this->perPage);
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
        $this->updateMode = false;
    }

    public function resetInputFields()
    {

    }

    public function updatedSelectAllEmployees($value)
    {
        if($value){
            $this->selectedEmployeeRows = $this->employees->pluck('id');
        }else{
            $this->selectedEmployeeRows = [];
        }
    }

    public function updatedSelectedEmployeeRows()
    {
        if(count($this->employees) == count($this->selectedEmployeeRows)) {
            $this->selectAllEmployees = true;
        } else {
            $this->selectAllEmployees = false;
        }
    }

    public function updatedSelectedDepartment()
    {
        $this->filterEmployees();
        $this->updatedSelectAllEmployees(false);
        $this->selectAllEmployees = false;
    }

    public function updatedSelectedDesignation()
    {
        $this->filterEmployees();
        $this->updatedSelectAllEmployees(false);
        $this->selectAllEmployees = false;
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

    public function selectedByFilteredEmployees($id)
    {
        if($id == 'all') {
            $this->employees = Employee::where('is_discontinued', false)->where('business_id', $this->businessId)->get();
        } else {
            $data = SaveFilter::find($id);
            $this->employees = Employee::where('is_discontinued', false)->where('business_id', $this->businessId)->whereIn('id', json_decode($data->employee_lists))->get();
        }

        $this->updatedSelectAllEmployees(true);
        $this->selectAllEmployees = true;
    }

    public function resetEmployeeSelection()
    {
        $this->employees = Employee::where('is_discontinued', false)->where('business_id', $this->businessId)->get();
        $this->updatedSelectAllEmployees(false);
        $this->selectAllEmployees = false;
    }
}
