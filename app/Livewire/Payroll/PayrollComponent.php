<?php

namespace App\Livewire\Payroll;

use App\Models\Payroll;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Models\SaveFilter;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PayrollComponent extends Component
{

    use LivewireAlert, WithPagination;

    public $perPage = 10;
    public $search = '';

    public $businessId;
    public $fortnights = [];
    #[Url]
    public $selectedFN = '';
    public $updateMode = false;

    public $chooseFiltered = false;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    public $saveFilters = [];
    public $selectedFilteredEmployees = [];

    #[Title('Payroll')]
    public function render()
    {
        return view('livewire.payroll.payroll-component', [
            'records' => $this->records
        ]);
    }

    public function mount()
    {
        $this->businessId = BusinessUser::where('user_id', Auth::user()->id)->where('is_active', true)->first()->business_id;
        $this->fortnights = Helpers::activeFortnights();
        $this->saveFilters = SaveFilter::where('business_id', $this->businessId)->get();

    }

    public function getRecordsProperty()
    {
        return Payroll::where('business_id', $this->businessId)->latest()->paginate($this->perPage);
    }

    public function updatedSelectAll($value)
    {
        if($value){
            $this->selectedRows = $this->records->pluck('id');
        }else{
            $this->selectedRows = [];
        }
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
