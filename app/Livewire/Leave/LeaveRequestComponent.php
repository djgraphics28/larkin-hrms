<?php

namespace App\Livewire\Leave;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\BusinessUser;
use App\Models\LeaveRequest;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Mail\SendLeaveRequest;
use Livewire\Attributes\Title;
use App\Jobs\sendLeaveRequestJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ApproveLeaveRequest;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class LeaveRequestComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove', 'selectedEmployee'];
    public $approveConfirmed;
    // filters
    public $businessId;
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Create Leave Request';
    public $updateMode = false;

    public $employee_id;
    public $leave_type;
    public $date_from;
    public $date_to;
    public $reason;
    public $halfday = false;
    public $halftime = null;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    public $employeeData = [];
    public $leaveTypes = [];

    #[Title('Leave Request')]
    public function render()
    {
        return view('livewire.leave.leave-request-component', [
            'records' => $this->records
        ]);
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

        $this->leaveTypes = LeaveType::all();

    }

    public function getRecordsProperty()
    {
        return LeaveRequest::with(['employee', 'leave_type'])->latest()->search(trim($this->search))
            ->paginate($this->perPage);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedRows = $this->records->pluck('id');
        } else {
            $this->selectedRows = [];
        }
    }

    public function addNew()
    {
        $this->employeeData = [];
        $this->resetInputFields();
        $this->dispatch('show-add-modal');
        $this->modalTitle = 'Create Leave Request';
        $this->updateMode = false;

    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'leave_type' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
            'reason' => 'required',
        ]);

        if(empty($this->employeeData)){
            $this->alert('error', 'Please select an employee.');
            return;
        }

        $start = Carbon::parse($this->date_from);
        $end = Carbon::parse($this->date_to);

        if ($end->lessThan($start)) {
            $this->alert('error', 'Date To must be greater than or equal to Date From.');
            return;
        }

        $diffInDays = $end->diffInDays($start);

        $leaveType = LeaveType::find($this->leave_type);

        $create = LeaveRequest::create([
            'employee_id' => $this->employee_id,
            'leave_type_id' => $this->leave_type,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'reason' => $this->reason,
            'is_half_day' => $this->halfday,
            'choosen_half' => $this->halftime,
            'with_pay_number_of_days' => $leaveType->is_payable ? $diffInDays + 1 : 0,
            'without_pay_number_of_days' => !$leaveType->is_payable ? $diffInDays + 1 : 0,
        ]);

        if ($create) {
            $employee = Employee::find($this->employee_id);
            dispatch(new sendLeaveRequestJob($employee, $create));


            $this->resetInputFields();
            if ($saveAndCreateNew) {
                $this->alert('success', 'Leave Request has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'Leave Request has been save successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->leave_type = '';
        $this->date_from = '';
        $this->date_to = '';
        $this->reason = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = LeaveRequest::find($id);
        $this->employee_id = $data->employee_id;
        $this->leave_type = $data->leave_type_id;
        $this->date_from = $data->date_from;
        $this->date_to = $data->date_to;
        $this->reason = $data->reason;

        $this->employeeData = Employee::with('leave_credits')->find($data->employee_id);

        $this->modalTitle = 'Edit Leave Request';
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        $data = LeaveRequest::find($this->edit_id);
        $data->update([
            'employee_id' => $this->employee_id,
            'leave_type_id' => $this->leave_type,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'reason' => $this->reason,
            'is_half_day' => $this->halfday,
            'choosen_half' => $this->halftime,
            'with_pay_number_of_days' => 0,
            'without_pay_number_of_days' => 0
        ]);

        if ($data) {
            $this->dispatch('hide-add-modal');

            $this->resetInputFields();

            $this->alert('success', 'Leave Request has been updated!');
        }
    }

    public function alertConfirm($id)
    {
        $this->approveConfirmed = $id;

        $this->confirm('Are you sure you want to delete this?', [
            'confirmButtonText' => 'Yes Delete it!',
            'onConfirmed' => 'remove',
        ]);
    }

    public function remove()
    {
        $delete = LeaveRequest::find($this->approveConfirmed);
        $delete->delete();
        if ($delete) {
            $this->alert('success', 'Leave Request has been removed!');
        }
    }

    public function selectedEmployee($employee_id)
    {
        if ($employee_id == null) {
            $this->employeeData = [];
        } else {
            $this->employee_id = $employee_id;
            $this->employeeData = Employee::with('leave_credits')->find($employee_id);
        }
    }
}
