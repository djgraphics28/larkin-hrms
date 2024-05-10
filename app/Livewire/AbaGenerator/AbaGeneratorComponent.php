<?php

namespace App\Livewire\AbaGenerator;

use Carbon\Carbon;
use App\Models\Payslip;
use Livewire\Component;
use App\Models\Business;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\BankDetail;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use ABWebDevelopers\AbaGenerator\Aba;


class AbaGeneratorComponent extends Component
{

    public $businessId;
    public $fortnights = [];
    #[Url]
    public $selectedFN;
    public $transaction = [];
    #[Title('ABA Generator')]
    public function render()
    {
        return view('livewire.aba-generator.aba-generator-component');
    }

    public function mount()
    {
        $businessUser = BusinessUser::where('user_id', Auth::user()->id)
            ->where('is_active', true)
            ->first();
        if (!$businessUser) {
            return redirect()->back();
        }

        $this->businessId = $businessUser->business_id;

        $this->fortnights = Fortnight::all();
    }

    public function generate()
    {
        $this->validate([
            'selectedFN' => 'required'
        ]);

        $employeeIds = Business::with('employees')->find($this->businessId)->employees->pluck('id');

        return redirect()->route('aba-download', [
            'businessId' => $this->businessId,
            'selectedFN' => $this->selectedFN,
            'employeeIds' => $employeeIds
        ]);
    }
}
