<?php

namespace App\Livewire\Payroll;


use App\Models\Payrun;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\Payslip;
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
    public $payslip = [];
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
        $this->validate([
            'selectedFN' => 'required'
        ]);

        $this->payslip = Payslip::where('fortnight_id', $this->selectedFN)->get();
    }
}
