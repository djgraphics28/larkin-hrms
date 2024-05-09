<?php

namespace App\Livewire\Employee\Partials;

use App\Models\Payslip;
use Livewire\Component;

class SummaryEarningsComponent extends Component
{
    public $id;
    public $payslip = [];
    public function render()
    {
        return view('livewire.employee.partials.summary-earnings-component');
    }

    public function mount()
    {
        $this->payslip = Payslip::where('employee_id', $this->id)->get();
    }
}
