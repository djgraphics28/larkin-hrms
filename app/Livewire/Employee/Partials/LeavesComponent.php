<?php

namespace App\Livewire\Employee\Partials;

use App\Models\LeaveRequest;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class LeavesComponent extends Component
{
    public $id;
    public $records = [];
    public $leaves = [];
    public $perPage = 10;

    public function render()
    {
        return view('livewire.employee.partials.leaves-component');
    }

    public function mount()
    {
        $this->records = DB::table('leave_types')
            ->leftJoin('leave_credits', function ($join) {
                $join->on('leave_credits.leave_type_id', '=', 'leave_types.id')
                     ->where('leave_credits.employee_id', $this->id);
            })
            ->select('leave_types.*', 'leave_credits.*')
            ->get();

        $this->leaves = LeaveRequest::where('employee_id', $this->id)->latest()->get();
    }
}
