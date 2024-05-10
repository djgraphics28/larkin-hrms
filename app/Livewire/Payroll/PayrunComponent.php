<?php

namespace App\Livewire\Payroll;

use App\Models\Payrun;
use App\Models\Payslip;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Models\Business;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\Department;
use App\Models\BusinessUser;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PayrunComponent extends Component
{

    use WithPagination, LivewireAlert;

    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Payrun';
    public $updateMode = false;

    public $fortnights;
    public $businesses;
    public $departments = [];
    public $employees = [];

    public $fortnight_id;
    public $business_id;
    public $department_ids = [];
    public $employee_ids = [];

    public $business_code;

    protected $paginationTheme = 'bootstrap';

    #[Title('Payrun')]

    public function render()
    {
        return view('livewire.payroll.payrun-component', [
            'records' => $this->records
        ]);
    }

    public function getRecordsProperty()
    {

        return Payrun::where('business_id', $this->business_id)
            ->search(trim($this->search))->orderBy('id', 'desc')->paginate($this->perPage);
    }

    public function mount()
    {
        $this->fortnights = Fortnight::all();

        $this->business_id = BusinessUser::where('user_id', Auth::user()->id)->where('is_active', true)->first()->business_id;

        $this->departments = Department::all();
    }

    public function resetInputFields()
    {
        $this->fortnight_id = '';
        $this->department_ids = [];
        $this->employee_ids = [];
    }

    public function generateNew()
    {
        $this->resetInputFields();
        $this->dispatch('show-add-modal');
        $this->modalTitle = 'Generate New Payrun';
        $this->updateMode = false;
    }

    public function updatedDepartmentIds()
    {
        // Check if "all" is selected
        if (in_array("all", $this->department_ids)) {
            // Select all employee IDs if "all" is chosen
            $this->department_ids = $this->departments->pluck('id')->toArray();
        } else {
            // Unselect "all" if other options are selected
            if (array_key_exists("all", array_flip($this->department_ids))) {
                unset($this->department_ids["all"]);
            }
        }
        // dd($this->department_ids);

        $this->employee_ids = [];
        $this->employees = [];

        if (!empty($this->business_id)) {
            $this->employees = Employee::where('business_id', $this->business_id)
                ->whereIn('department_id', $this->department_ids)
                ->where('is_discontinued', 0)
                ->get();
        }
    }

    public function updatedEmployeeIds()
    {
        // Check if "all" is selected
        if (in_array("all", $this->employee_ids)) {
            // Select all employee IDs if "all" is chosen
            $this->employee_ids = $this->employees->pluck('id')->toArray();
        } else {
            // Unselect "all" if other options are selected
            if (array_key_exists("all", array_flip($this->employee_ids))) {
                unset($this->employee_ids["all"]);
            }
        }
    }

    public function submit()
    {
        $selected_dept = Department::whereIn('id', $this->department_ids)->get();

        $dept_array = [];
        foreach ($selected_dept as $selected_dept) {
            $dept_array[] = $selected_dept->name;
        }

        $this->validate([
            'fortnight_id' => 'required',
            'business_id' => 'required',
            'department_ids' => 'required',
            'employee_ids' => 'required'
        ]);

        $business_code = Business::where('id', $this->business_id)->first();

        $selected_dept = [];

        $count_dept = count($dept_array);

        if (count($this->departments) === count($this->department_ids)) {
            $remarks = $business_code->code . ' - ALL';
        } elseif (count($this->departments) > count($this->department_ids)) {
            if ($count_dept === 1) {
                $remarks = $business_code->code . ' - ' . $dept_array[0];
            } elseif ($count_dept > 1) {
                $depts = '';
                $counter = 0;
                foreach ($dept_array as $dept) {
                    $counter = $counter + 1;
                    if ($counter < $count_dept) {
                        $depts = $depts . $dept . ', ';
                    } elseif ($counter === $count_dept) {
                        $depts = $depts . $dept;
                    }
                }
                $remarks = $business_code->code . ' - ' . $depts;
            }
        }

        $payrun = Payrun::create(
            [
                'fortnight_id' => $this->fortnight_id,
                'business_id' => $this->business_id,
                'remarks' => $remarks
            ],
        );

        $payrun_id = $payrun->id;

        Helpers::computeHours($this->fortnight_id, $this->business_id, 'payrun', $payrun_id, $this->employee_ids);

        $this->resetInputFields();

        $this->dispatch('hide-add-modal');
        $this->alert('success', 'Payrun Generated');
    }

    public function generateAba($payrun_id)
    {

        return redirect()->route('aba-download', [
            'payrun_id' => $payrun_id
        ]);
    }
}
