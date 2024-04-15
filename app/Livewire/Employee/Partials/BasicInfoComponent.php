<?php

namespace App\Livewire\Employee\Partials;

use Livewire\Component;
use App\Models\Employee;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BasicInfoComponent extends Component
{
    use LivewireAlert;
    public $id;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $ext_name;
    public $email;
    public $phone;
    public $gender;
    public $marital_status;
    public $birth_date;
    public $address;

    public function render()
    {
        return view('livewire.employee.partials.basic-info-component');
    }

    public function mount()
    {
        $data = Employee::find($this->id);
        $this->first_name = $data->first_name;
        $this->middle_name = $data->middle_name;
        $this->last_name = $data->last_name;
        $this->ext_name = $data->ext_name;
        $this->email = $data->email;
        $this->phone = $data->phone;
        $this->gender = $data->gender;
        $this->marital_status = $data->marital_status;
        $this->birth_date = $data->birth_date;
        $this->address = $data->address;
    }

    public function update()
    {
        $this->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'phone' => 'required',
            'email' => 'required|email',
            'marital_status' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'address' => 'required',
        ]);

        Employee::find($this->id)->update([
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'ext_name' => $this->ext_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'birth_date' => $this->birth_date,
            'address' => $this->address
        ]);

        $this->alert('success', 'Basic Info Updated successfully!');

    }
}
