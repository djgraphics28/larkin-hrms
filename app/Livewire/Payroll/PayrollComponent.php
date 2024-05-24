<?php

namespace App\Livewire\Payroll;

use Livewire\Component;
use App\Helpers\Helpers;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PayrollComponent extends Component
{

    use LivewireAlert, WithPagination;

    public $fortnights = [];
    #[Url]
    public $selectedFortnight = '';
    public $fortnight = '';
    public $updateMode = false;

    #[Title('Payroll')]
    public function render()
    {
        return view('livewire.payroll.payroll-component', [
            'records' => $this->records
        ]);
    }

    public function mount()
    {
        $this->fortnights = Helpers::activeFortnights();

    }

    public function getRecordsProperty()
    {
        // return Payroll
    }

    public function addNew()
    {
        $this->resetInputFields();
        $this->dispatch('show-add-modal');
        $this->updateMode = false;
    }

    public function resetInputFields()
    {

    }
}
