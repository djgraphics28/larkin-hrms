<?php

namespace App\Livewire\Nasfund;

use DateTime;
use Carbon\Carbon;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\Fortnight;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class NasfundComponent extends Component
{
    use WithPagination;

    // filters
    public $businessId;
    public $perPage = 10;
    public $search = '';
    public $fortnights = [];
    #[Url]
    public $selectedFN;
    public $records = [];
    public $ranges = [];
    public $holidays = [];
    public $filteredPayslips = [];

    public $fn_start;
    public $fn_end;
    public $fn_id;

    public $selectAll = false;
    public $selectedRows = [];

    public $employerRN;

    #[Title('Nasfund')]
    public function render()
    {
        return view('livewire.nasfund.nasfund-component');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRows = $this->records->pluck('id');
        } else {
            $this->selectedRows = [];
        }
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
        $this->employerRN = '131934';

        $this->generate();
    }

    public function generate()
    {
        $this->validate([
            'selectedFN' => 'required'
        ]);

        $fnId = $this->selectedFN;

        if($fnId == '') {
            $this->records = [];
            return;
        }

        $employees = Employee::with(['payslip' => function($query) use ($fnId) {
            $query->where('fortnight_id', $fnId)
                ->where('is_approved', 1);
        }])
            ->where('business_id', $this->businessId)
            ->where('collect_nasfund', 1)
            ->whereNotNull('nasfund_number')
            ->where('is_discontinued', false)
            ->get();

        sleep(2);

        $this->records = $employees;
    }

    public function generatePdf()
    {
        if($this->selectedFN == '') {
            $this->alert('warning', ' Please select Fortnight First!');
            return;
        }
        return redirect()->route('nasfund-pdf', ['businessId' => $this->businessId, 'fnId' => $this->selectedFN]);
    }
}
