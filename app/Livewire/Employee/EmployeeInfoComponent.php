<?php

namespace App\Livewire\Employee;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Employee;
use App\Models\BusinessUser;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class EmployeeInfoComponent extends Component
{
    use WithFileUploads, LivewireAlert;
    public $businessId;
    public $label;
    public $id;
    #[Validate('image|max:1024')]
    public $image = '';
    public $fullName = '';
    public $position = '';

    public $age = 0;
    public $gender = 'Male';

    public function render()
    {
        return view('livewire.employee.employee-info-component');
    }

    public function mount()
    {
        $businessUser = BusinessUser::where('user_id', Auth::user()->id)
                                     ->where('is_active', true)
                                     ->first();
        if (!$businessUser) {
            return redirect()->route('employee.index', $this->label);
        }

        $this->businessId = $businessUser->business_id;

        $employee = Employee::where('id', $this->id)
                            ->where('business_id', $this->businessId)
                            ->first();

        if (!$employee) {
            return redirect()->route('employee.index', $this->label);
        }

        $this->fullName = $employee->first_name . " " . $employee->last_name;
        $this->position = $employee->designation->name;
        $this->gender = $employee->gender;
        $this->age = Carbon::parse($employee->birth_date)->age;
    }

    public function uploadImage(Employee $employee)
    {
        $employee->addMedia($this->image)->toMediaCollection('profile_picture', 'local');

        $this->alert('success', 'Image Updated successfully!');
    }
}
