<?php

namespace App\Livewire\Payroll;


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
        $this->employees = Employee::all();
        $this->generate();
    }

    public function generate()
    {
        $this->validate([
            'selectedFN' => 'required'
        ]);

        $employees = Employee::withCount('employee_hours')
            ->search(trim($this->search))->get();



        // dd($this->selectedFN);

        Helpers::computePay($this->selectedFN);

        sleep(2);

        $this->records = $employees;
    }
}
