<?php

namespace App\Livewire\Payroll;

use App\Models\EmployeeHours;
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
        try {
            \DB::beginTransaction();

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

                    $employeeModel = Employee::find($employee);

                    if (!$employeeModel) {
                        continue; // Skip if the employee is not found.
                    }

                    $label = $employeeModel->label;
                    $salaryRate = 0;
                    if ($label == 'National') {
                        $salaryRate = $employeeModel->active_salary->salary_rate;

                    } elseif ($label == 'Expatriate') {
                        $monthlyRate = $employeeModel->active_salary->monthly_rate;
                        $convertToHourRate = (($monthlyRate * 12) / 26) / 84;
                        $salaryRate = $convertToHourRate;

                    }

                    $empHour = EmployeeHours::where('employee_id', $employee)->where('fortnight_id', $this->selectedFortnight)->first();
                    if (!$empHour)
                        continue;

                    $regular = $empHour->regular_hr * $salaryRate;
                    $overtime = $empHour->overtime_hr * $salaryRate;
                    $sundayOt = $empHour->sunday_ot_hr * $salaryRate;
                    $holidayOt = $empHour->holiday_ot_hr * $salaryRate;
                    // $pay = Helpers::computePayslip($employee, $this->selectedFortnight);

                    $gross = $regular + $overtime + $sundayOt + $holidayOt;


                    $plpAlpFp = 0;
                    $other = 0;
                    $npf = Helpers::computeEmployeeNPF($employee, $regular);
                    $ncsl = 0;
                    $cashAdv = 0;

                    if ($label === 'National') {
                        $taxable = $gross;
                    } elseif ($label === 'Expatriate') {
                        $taxable = $gross - ($npf + $ncsl + $cashAdv);
                    }

                    $fnTax = Helpers::computeTax($taxable);

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

                \DB::commit();
                $this->alert('success', 'Payroll has been created successfully.');
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->alert('error', $e->getMessage());
        }
    }

    public function showAttendance($empNo)
    {
        $this->employeeName = Employee::where('employee_number', $empNo)->first()->first_name;
        $this->dispatch('show-attendance-modal');
        $this->employeeAttendances = Attendance::where('employee_number', $empNo)->where('fortnight_id', $this->selectedFortnight)->orderBy('date')->get();
        $this->filterEmployees();
    }
}
