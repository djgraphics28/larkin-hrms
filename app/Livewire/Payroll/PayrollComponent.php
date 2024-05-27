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

    protected $listeners = ['remove', 'selectedEmployee', 'approve', 'reject', 'onHold', 'cancel', 'revert', 'payrun'];


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

    public $selectedFortnight = '';
    public $approvedPayrunId;
    public $showProgressBar = false;
    public $totalEmployees = 0;
    public $employeeDone = 0;

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
        return Payroll::withCount('payslips')->where('business_id', $this->businessId)->search(trim($this->search))
            ->when($this->selectedFN, function ($query) {
                $query->where('fortnight_id', $this->selectedFN);
            })->latest()->paginate($this->perPage);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRows = $this->records->pluck('id');
        } else {
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
        $this->selectedFortnight = '';
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
        if ($id == 'all') {
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
        $this->selectedDepartment = '';
        $this->selectedDesignation = '';
    }

    public function payrunConfirm()
    {
        $this->confirm('Are you sure you want to Payrun?', [
            'confirmButtonText' => 'Yes proceed!',
            'onConfirmed' => 'payrun',
        ]);
    }

    public function payrun()
    {

        $this->validate([
            'selectedFortnight' => 'required'
        ]);

        if (count($this->selectedEmployeeRows) == 0) {
            $this->alert('warning', 'No employee has been selected.');
            return;
        }

        $payroll = Payroll::create([
            'payroll_code' => Helpers::payrollCodeGenerator($this->businessId, $this->selectedFortnight),
            'status' => 'For-Approval',
            'remarks' => '',
            'created_by' => Auth::user()->id,
            'fortnight_id' => $this->selectedFortnight,
            'business_id' => $this->businessId
        ]);

        if ($payroll) {
            $this->totalEmployees = count($this->selectedEmployeeRows);
            $this->employeeDone = 0;
            foreach ($this->selectedEmployeeRows as $employee) {
                //dito na lang ilalagay sir

                $pay = Helpers::computePayslip($employee, $this->selectedFortnight);

                $regular = $pay['regular'];
                $overtime = $pay['overtime'];
                $sunday_ot = $pay['sunday_ot'];
                $holiday_ot = $pay['holiday_ot'];
                $plp_alp_fp = 0;
                $other = 0;
                $fn_tax = Helpers::computeTax(900); //example lang yung 900
                $npf = 0;
                $ncsl = 0;
                $cash_adv = 0;

                $payroll->payslips()->create([
                    'employee_id' => $employee,
                    'fortnight_id' => $this->selectedFortnight,
                    'business_id' => $this->businessId,
                    'regular' => $regular,
                    'overtime' => $overtime,
                    'sunday_ot' => $sunday_ot,
                    'holiday_ot' => $holiday_ot,
                    'plp_alp_fp' => $plp_alp_fp,
                    'other' => $other,
                    'fn_tax' => $fn_tax,
                    'npf' => $npf,
                    'ncsl' => $ncsl,
                    'cash_adv' => $cash_adv
                ]);
                $this->employeeDone += 1;
            }
            sleep(3);
            $this->alert('success', 'Payroll has been created successfully.');
        }
    }
}
