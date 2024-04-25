<?php

namespace App\Livewire\Employee\Partials;

use Livewire\Component;
use App\Models\Employee;
use App\Models\SalaryHistory;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SalaryHistoryComponent extends Component
{
    use LivewireAlert;
    public $id;
    public $salary_rate;
    public function render()
    {
        return view('livewire.employee.partials.salary-history-component',[
            'records' => $this->records
        ]);
    }

    public function getRecordsProperty()
    {
        return SalaryHistory::where('employee_id', $this->id)->latest()->get();
    }

    public function create()
    {
        $this->validate([
            'salary_rate' => 'required'
        ]);

        DB::table('salary_histories')->where('employee_id', $this->id)->update(['is_active'=> false]);

        $data = SalaryHistory::create([
            'salary_rate' => $this->salary_rate,
            'employee_id' => $this->id,
            'is_active' => true,
        ]);

        if($data) {
            $this->alert('success', 'Salary Rate updated successfuly!');
        }
    }
}
