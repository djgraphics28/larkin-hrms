<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Employee;
use App\Models\BusinessUser;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public $businessId;
    public $totalEmployees = 0;
    public $totalLeaveRequests = 0;

    #[Title('Dashboard')]
    public function render()
    {
        return view('livewire.dashboard.dashboard');
    }

    public function mount()
    {
        $this->businessId = BusinessUser::where('user_id',Auth::user()->id)->where('is_active', true)->first()->business_id;
        $this->totalEmployees = Employee::where('business_id', $this->businessId)->count();
    }
}
