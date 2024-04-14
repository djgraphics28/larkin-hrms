<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Workshift;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeStatus;
use Livewire\Attributes\Title;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CreateEmployeeComponent extends Component
{
    use LivewireAlert;

    public $label;
    public $employee_number;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $ext_name;
    public $phone;
    public $email;
    public $address;
    public $marital_status;
    public $birth_date;
    public $joining_date;
    public $end_date;
    public $deployment_date_home_country;
    public $gender;
    public $image;
    public $salary_rate;
    public $nasfund_number;
    public $passport_number;
    public $passport_expiry;
    public $work_permit_number;
    public $work_permit_expiry;
    public $visa_number;
    public $visa_expiry;
    public $designation;
    public $employee_status;
    public $department;
    public $workshift;

    public $departments = [];
    public $workshifts = [];
    public $employeeStatuses = [];
    public $designations = [];

    #[Title('Create Employee')]
    public function render()
    {
        return view('livewire.employee.create-employee-component');
    }

    public function mount()
    {
        $this->departments = Department::where('is_active',1)->get();
        $this->workshifts = Workshift::all();
        $this->employeeStatuses = EmployeeStatus::where('is_active',1)->get();
        $this->designations = Designation::where('is_active',1)->get();
    }

    public function submit()
    {
        $this->validate([
            'employee_number' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'marital_status' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'joining_date' => 'required',
            'designation' => 'required',
            'end_date' => 'required',
            'salary_rate' => 'required',
            'nasfund_number' => 'required',
            'employee_status' => 'required',
            'department' => 'required',
            'workshift' => 'required',
            'label' => $this->label == 'all' ? 'required' : '', // Validation rule for label based on condition
            'passport_number' => $this->label == 'expatriate' ? 'required' : '', // Validation rule for passport_number based on condition
            'passport_expiry' => $this->label == 'expatriate' ? 'required' : '', // Validation rule for passport_expiry based on condition
            'work_permit_number' => $this->label == 'expatriate' ? 'required' : '', // Validation rule for work_permit_number based on condition
            'work_permit_expiry' => $this->label == 'expatriate' ? 'required' : '', // Validation rule for work_permit_expiry based on condition
            'visa_number' => $this->label == 'expatriate' ? 'required' : '', // Validation rule for visa_number based on condition
            'visa_expiry' => $this->label == 'expatriate' ? 'required' : '', // Validation rule for visa_expiry based on condition
        ]);

        $create = Employee::create([
            'employee_number' => $this->employee_number,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'ext_name' => $this->ext_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'email' => $this->email,
            'marital_status' => $this->marital_status,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'joining_date' => $this->joining_date,
            'end_date' => $this->end_date,
            'deployment_date_home_country' => $this->end_date,
            'label' => $this->label,
            'nasfund_number' => $this->nasfund_number,
            'passport_number' => $this->passport_number,
            'passport_expiry' => $this->passport_expiry,
            'work_permit_number' => $this->work_permit_number,
            'work_permit_expiry' => $this->work_permit_expiry,
            'visa_number' => $this->visa_number,
            'visa_expiry' => $this->visa_expiry,
            'designation_id' => $this->designation,
            'employee_status_id' => $this->employee_status,
            'department_id' => $this->department,
            'workshift_id' => $this->workshift,
            'business_id' => 1
        ]);

        if ($create) {
            $this->alert('success', 'New Employee has been saved successfully!');
        } else {
            $this->alert('error', 'Something went wrong, please try again!');
        }

    }
}
