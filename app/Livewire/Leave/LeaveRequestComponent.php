<?php

namespace App\Livewire\Leave;

use Livewire\Component;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\LeaveRequest;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\ApproveLeaveRequest;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class LeaveRequestComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove','selectedEmployee'];
    public $approveConfirmed;
    // filters
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
        return view('livewire.leave.leave-request-component',[
            'records' => $this->records
        ]);
    }

    public function mount()
    {
        $this->leaveTypes = LeaveType::all();
    }

    public function getRecordsProperty()
    {
        return LeaveRequest::with(['employee','leave_type'])->search(trim($this->search))
            ->paginate($this->perPage);
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

        $create = LeaveRequest::create([
            'employee_id' => $this->employee_id,
            'leave_type_id' => $this->leave_type,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'reason' => $this->leave_type,
            'is_half_day' => $this->halfday,
            'choosen_half' => $this->halftime,
            'with_pay_number_of_days' => 0,
            'without_pay_number_of_days' => 0
        ]);

        if($create){

            Mail::to('darwin.ibay30@gmail.com')->send(new ApproveLeaveRequest());

            $this->resetInputFields();
            if($saveAndCreateNew) {
                $this->alert('success', 'Leave Request has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'Leave Request has been save successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->contact_number = '';
        $this->address = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = Department::find($id);
        $this->name = $data->name;
        $this->modalTitle = 'Edit '.$this->name;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        $data = Designation::find($this->edit_id);
        $data->update([
            'name' => $this->name
        ]);

        if($data) {
            $this->dispatch('hide-add-modal');

            $this->resetInputFields();

            $this->alert('success', $data->name.' has been updated!');
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
        $name = $delete->name;
        $delete->delete();
        if($delete){
            $this->alert('success', $name.' has been removed!');
        }
    }

    public function selectedEmployee($employee_id)
    {
        if($employee_id == null) {
            $this->employeeData = [];
        } else{
            $this->employee_id = $employee_id;
            $this->employeeData = Employee::with('leave_credits')->find($employee_id);
        }
    }
}
