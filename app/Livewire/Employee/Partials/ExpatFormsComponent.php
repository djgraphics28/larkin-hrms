<?php

namespace App\Livewire\Employee\Partials;

use Livewire\Component;
use App\Models\Employee;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ExpatFormsComponent extends Component
{
    use LivewireAlert;
    public $id;
    public $passport_number;
    public $passport_expiry;
    public $work_permit_number;
    public $work_permit_expiry;
    public $visa_number;
    public $visa_expiry;

    public function render()
    {
        return view('livewire.employee.partials.expat-forms-component');
    }

    public function mount()
    {
        $employee = Employee::find($this->id);
        $this->passport_number = $employee->passport_number;
        $this->passport_expiry = $employee->passport_expiry;
        $this->work_permit_number = $employee->work_permit_number;
        $this->work_permit_expiry = $employee->work_permit_expiry;
        $this->visa_number = $employee->visa_number;
        $this->visa_expiry = $employee->visa_expiry;
    }

    public function update()
    {
        $employee = Employee::find($this->id);
        $employee->update([
            'passport_number' => $this->passport_number,
            'passport_expiry' => $this->passport_expiry,
            'work_permit_number' => $this->work_permit_number,
            'work_permit_expiry' =>  $this->work_permit_expiry,
            'visa_number' => $this->visa_number,
            'visa_expiry' =>  $this->visa_expiry
        ]);

        if($employee) {
            $this->alert('success', 'Expatriate Records saved successfully');
        } else {
            $this->alert('error', 'Expatriate Records failed to update');
        }

    }
}
