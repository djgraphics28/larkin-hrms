<?php

namespace App\Livewire\Payroll;

use App\Models\Payroll;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Attendance;
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

    #[Url]
    public $selectedFortnight = '';
    public $approvedPayrunId;
    public $showProgressBar = false;
    public $totalEmployees = 0;
    public $employeeDone = 0;

    public $employeeAttendances = [];
    public $employeeName = '';

    #[Title('Payroll')]
    public function render()
    {
        return view('livewire.payroll.payroll-component', [
            'records' => $this->records
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->businessId = BusinessUser::where('user_id', Auth::user()->id)->where('is_active', true)->first()->business_id;
        $this->departments = Department::where('is_active', true)->get();
        $this->designations = Designation::where('is_active', true)->get();
        $this->fortnights = Helpers::activeFortnights();
        $this->saveFilters = SaveFilter::where('business_id', $this->businessId)->get();
        $this->employees = Employee::withCount([
            'attendances' => function ($query) {
                $query->where('fortnight_id', $this->selectedFortnight);
            }
        ])->where('is_discontinued', false)
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
        $this->filterEmployees();
    }

    public function updatedSelectedEmployeeRows()
    {
        if (count($this->employees) == count($this->selectedEmployeeRows)) {
            $this->selectAllEmployees = true;
        } else {
            $this->selectAllEmployees = false;
        }
        $this->filterEmployees();
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

    public function updatedSelectedFortnight()
    {
        $this->filterEmployees();
    }

    public function filterEmployees()
    {
        $this->employees = Employee::withCount([
            'attendances' => function ($query) {
                $query->where('fortnight_id', $this->selectedFortnight);
            }
        ])->where('is_discontinued', false)
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
            $this->employees = Employee::withCount([
                'attendances' => function ($query) {
                    $query->where('fortnight_id', $this->selectedFortnight);
                }
            ])->where('is_discontinued', false)->where('business_id', $this->businessId)->get();
        } else {
            $data = SaveFilter::find($id);
            $this->employees = Employee::withCount([
                'attendances' => function ($query) {
                    $query->where('fortnight_id', $this->selectedFortnight);
                }
            ])->where('is_discontinued', false)->where('business_id', $this->businessId)->whereIn('id', json_decode($data->employee_lists))->get();
        }

        $this->updatedSelectAllEmployees(true);
        $this->selectAllEmployees = true;
    }

    public function resetEmployeeSelection()
    {
        $this->employees = Employee::withCount([
            'attendances' => function ($query) {
                $query->where('fortnight_id', $this->selectedFortnight);
            }
        ])->where('is_discontinued', false)->where('business_id', $this->businessId)->get();
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
            foreach ($this->selectedEmployeeRows as $employee) {
                //dito na lang ilalagay sir

                $label = Employee::where('id', $employee)->first()->label;

                $pay = Helpers::computePayslip($employee, $this->selectedFortnight);

                $gross = $pay['regular'] + $pay['overtime'] + $pay['sunday_ot'] + $pay['holiday_ot'];

                if ($gross === 0.0 || $gross === 0) {
                    continue;
                }

                $regular = $pay['regular'];
                $overtime = $pay['overtime'];
                $sundayOt = $pay['sunday_ot'];
                $holidayOt = $pay['holiday_ot'];
                $plpAlpFp = 0;
                $other = 0;
                $npf = Helpers::computeEmployeeNPF($employee, $regular);
                $ncsl = 0;
                $cash_adv = 0;

                if ($label === 'National') {
                    $taxable = $gross;
                } elseif ($label === 'Expatriate') {
                    $taxable = $gross - ($npf + $ncsl + $cash_adv);
                }

                $fn_tax = Helpers::computeTax($taxable);

                $payroll->payslips()->create([
                    'employee_id' => $employee,
                    'fortnight_id' => $this->selectedFortnight,
                    'business_id' => $this->businessId,
                    'regular' => $regular,
                    'overtime' => $overtime,
                    'sunday_ot' => $sundayOt,
                    'holiday_ot' => $holidayOt,
                    'plp_alp_fp' => $plpAlpFp,
                    'other' => $other,
                    'fn_tax' => $fnTax,
                    'npf' => $npf,
                    'ncsl' => $ncsl,
                    'cash_adv' => $cashAdv
                ]);
            }
            sleep(2);
            $this->alert('success', 'Payroll has been created successfully.');
        }
    }

    public function showAttendance($empNo)
    {
        $this->employeeName = Employee::where('employee_number',$empNo)->first()->first_name;
        $this->dispatch('show-attendance-modal');
        $this->employeeAttendances = Attendance::where('employee_number', $empNo)->where('fortnight_id',$this->selectedFortnight)->orderBy('date')->get();
        $this->filterEmployees();
    }
}
