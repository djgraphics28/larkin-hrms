<?php

namespace App\Livewire\Payroll;


use App\Models\Payrun;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Fortnight;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

class PayslipComponent extends Component
{

    use WithPagination;

    // filters
    public $perPage = 10;
    public $search = '';
    public $fortnights = [];
    public $employees = [];
    public $payruns = [];
    public $latestByEmployee = [];
    #[Url]
    public $selectedFN;
    public $records = [];
    public $ranges = [];

    public $selectAll = false;
    public $selectedRows = [];

    #[Title('Payslip')]

    public function render()
    {
        return view('livewire.payroll.payslip-component');
    }

    public function mount()
    {
        $this->fortnights = Fortnight::all();
        // $this->employees = Employee::all();
        $this->view();
    }

    public function view()
    {
        $this->latestByEmployee = [];
        $this->validate([
            'selectedFN' => 'required'
        ]);

        $payruns = Payrun::where('fortnight_id', $this->selectedFN)
            ->get();


        foreach ($payruns as $payrun) {

            if ($payrun->payslip->isEmpty()) {
                continue;

                $employeeId = $payrun->payslip->first()->employee->id;

                if (!isset($this->latestByEmployee[$employeeId])) {
                    $this->latestByEmployee[$employeeId] = $payrun->payslip->first();
                } else {

                    $currentLatest = $this->latestByEmployee[$employeeId];
                    $newPayslip = $payrun->payslip->first();

                    if ($newPayslip->created_at > $currentLatest->created_at) {
                        $this->latestByEmployee[$employeeId] = $newPayslip;
                    }
                }
            }
        }
    }
}
