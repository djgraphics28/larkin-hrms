<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;

class NasfundReportingComponent extends Component
{
    public $businessId;
    public $fortnights = [];
    public $selectedFortnight = '';
    #[Url]
    public $year = '';
    #[Url]
    public $fortnight = '';
    public $records = [];
    #[Title('Nasfund Reporting')]
    public function render()
    {
        return view('livewire.reports.nasfund-reporting-component');
    }

    public function mount()
    {
        $this->businessId = BusinessUser::where('user_id', auth()->user()->id)
            ->where('is_active', true)
            ->first()->business_id;
        $this->generateNasfund();
    }

    public function generateNasfund()
    {
        $this->records = Employee::where('is_discontinued', 0)->where('business_id',$this->businessId)->get();
    }

    public function getFortnight()
    {
        if($this->year == '') {
            $this->fortnights = [];
        }else{
            $this->fortnights = Fortnight::where('year', $this->year)->get();
        }
    }
}
