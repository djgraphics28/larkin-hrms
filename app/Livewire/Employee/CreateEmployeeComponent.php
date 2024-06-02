<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Workshift;
use App\Models\Department;
use App\Models\Designation;
use App\Models\BusinessUser;
use App\Models\EmployeeStatus;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CreateEmployeeComponent extends Component
{
    use LivewireAlert;

    public $businessId;
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

    public $bankSelected = false;
    public $account_name;
    public $account_number;
    public $bank_name;
    public $bsb_code;

    public $departments = [];
    public $workshifts = [];
    public $employeeStatuses = [];
    public $designations = [];

    public $selectedLabel = '';
    public $monthly_rate = '';

    #[Title('Create Employee')]
    public function render()
    {
        return view('livewire.employee.create-employee-component');
    }

    public function mount()
    {
        $this->businessId = BusinessUser::where('user_id', Auth::user()->id)->where('is_active', true)->first()->business_id;
        $this->departments = Department::where('is_active', 1)->get();
        $this->workshifts = Workshift::all();
        $this->employeeStatuses = EmployeeStatus::where('is_active', 1)->get();
        $this->designations = Designation::where('is_active', 1)->get();
        $this->employee_number = Helpers::generateEmployeeNumber($this->businessId);

    }

    public function updateAccountName()
    {
        $this->account_name = $this->first_name . ' ' . $this->last_name;
    }

    public function submit($type)
    {
        // Validate the request data
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'marital_status' => 'required',
            'gender' => 'required',
            'birth_date' => 'required|date',
            'joining_date' => 'required|date',
            'designation' => 'required',
            'employee_status' => 'required',
            'department' => 'required',
            'workshift' => 'required',
            'label' => $this->label == 'all' ? 'required' : '',
            'passport_number' => $this->label == 'expatriate' ? 'required' : '',
            'passport_expiry' => $this->label == 'expatriate' ? 'required|date' : '',
            'work_permit_number' => $this->label == 'expatriate' ? 'required' : '',
            'work_permit_expiry' => $this->label == 'expatriate' ? 'required|date' : '',
            'visa_number' => $this->label == 'expatriate' ? 'required' : '',
            'visa_expiry' => $this->label == 'expatriate' ? 'required|date' : '',
        ]);

        $label = $this->selectedLabel == '' && $this->label !== 'all' ? ucfirst($this->label) : $this->selectedLabel;

        // Prepare the data for insertion
        $data = [
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
            'end_date' => $this->end_date ?? null,
            'deployment_date_home_country' => $this->deployment_date_home_country ?? null,
            'label' => $label,
            'nasfund_number' => $this->nasfund_number,
            'passport_number' => $this->passport_number ?? '',
            'passport_expiry' => $this->passport_expiry ?: null,
            'work_permit_number' => $this->work_permit_number ?? '',
            'work_permit_expiry' => $this->work_permit_expiry ?: null,
            'visa_number' => $this->visa_number ?? '',
            'visa_expiry' => $this->visa_expiry ?: null,
            'designation_id' => $this->designation,
            'employee_status_id' => $this->employee_status,
            'department_id' => $this->department,
            'workshift_id' => $this->workshift,
            'business_id' => $this->businessId,
            'default_pay_method' => 'cash',
        ];

        // Create a new employee record
        $create = Employee::create($data);

        // Handle salary rates if provided
        if ($this->salary_rate !== '' && $this->monthly_rate === '') {
            $create->salaries()->create([
                'monthly_rate' => null,
                'salary_rate' => $this->salary_rate,
                'is_active' => true
            ]);
        }

        if ($this->monthly_rate !== '' && $this->salary_rate === '') {
            $create->salaries()->create([
                'salary_rate' => null,
                'monthly_rate' => $this->monthly_rate,
                'is_active' => true
            ]);
        }

        // Handle bank details if provided
        if ($this->bankSelected) {
            if ($this->account_name && $this->account_number && $this->bank_name && $this->bsb_code) {
                $create->bank_details()->create([
                    'account_name' => $this->account_name,
                    'account_number' => $this->account_number,
                    'bank_name' => $this->bank_name,
                    'bsb_code' => $this->bsb_code,
                    'is_active' => true,
                ]);

                // Update pay method to bank
                $create->update(['default_pay_method' => 'bank']);
            } else {
                $this->alert('error', 'Please fill in all bank details fields!');
                return;
            }
        }

        // Respond to the outcome of the creation process
        if ($create) {
            if ($type == 1) {
                return redirect()->route('employee.index', $this->label)->with('success', 'New Employee has been saved successfully!');
            } else {
                $this->resetFields();
                $this->alert('success', 'New Employee has been saved successfully!');
            }
        } else {
            $this->alert('error', 'Something went wrong, please try again!');
        }
    }


    public function resetFields()
    {
        $this->first_name = '';
        $this->middle_name = '';
        $this->last_name = '';
        $this->ext_name = '';
        $this->phone = '';
        $this->email = '';
        $this->address = '';
        $this->marital_status = '';
        $this->birth_date = '';
        $this->joining_date = '';
        $this->end_date = '';
        $this->deployment_date_home_country = '';
        $this->nasfund_number = '';
        $this->passport_number = '';
        $this->passport_expiry = '';
        $this->work_permit_number = '';
        $this->work_permit_expiry = '';
        $this->visa_number = '';
        $this->visa_expiry = '';
        $this->designation_id = '';
        $this->employee_status_id = '';
        $this->department_id = '';
        $this->workshift_id = '';
        $this->salary_rate = '';
        $this->passport_number = '';
        $this->passport_expiry = '';
        $this->work_permit_number = '';
        $this->work_permit_number = '';
        $this->visa_number = '';
        $this->visa_expiry = '';
        $this->account_name = '';
        $this->account_number = '';
        $this->bank_name = '';
        $this->bsb_code = '';
    }

    public function updatedSelectedLabel($value)
    {
        if ($value !== '') {
            $this->selectedLabel = $value;

        } else {
            $this->selectedLabel = '';
        }
        $this->salary_rate = '';
        $this->monthly_rate = '';

        $this->passport_number = '';
        $this->passport_expiry = '';
        $this->work_permit_number = '';
        $this->work_permit_number = '';
        $this->visa_number = '';
        $this->visa_expiry = '';
    }
}
