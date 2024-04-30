<?php

namespace App\Livewire\Employee\Partials;

use App\Models\Loan;
use Livewire\Component;
use Livewire\WithPagination;

class LoanHistoryComponent extends Component
{
    use WithPagination;

    public $id;
    public $records = [];
    public $perPage = 10;

    public function render()
    {
        return view('livewire.employee.partials.loan-history-component');
    }

    public function mount()
    {
        $this->records = Loan::where('employee_id', $this->id)->latest()->get();
    }
}
