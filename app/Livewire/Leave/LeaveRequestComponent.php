<?php

namespace App\Livewire\Leave;

use DateTime;
use Carbon\Carbon;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Attendance;
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

    protected $listeners = ['remove', 'selectedEmployee', 'approve', 'reject', 'onHold', 'cancel', 'revert'];
    public $approveConfirmed;
    public $approveApproveConfirmed;
    public $approveRevertConfirmed;
    public $approveRejectConfirmed;
    public $approveCancelConfirmed;
    public $approveOnHoldConfirmed;
    // filters
    public $businessId;
    public $perPage = 10;
    #[Url]
    public $search = '';
    #[Url]
    public $selectedLeaveType = '';
    #[Url]
    public $selectedStatus = '';
    public $modalTitle = 'Create Leave Request';
    public $updateMode = false;

    public $employee_id;
    public $leave_type;
    public $date_from;
    public $date_to;
    public $reason;
    public $halfday = false;
    public $choosen_half = null;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    public $employeeData = [];
    public $leaveTypes = [];
    public $employees = [];

    #[Title('Leave Request')]
    public function render()
    {
        return view('livewire.leave.leave-request-component', [
            'records' => $this->records
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
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
            ->when($this->selectedStatus, function ($query) {
                $query->where('status', $this->selectedStatus);
            })
            ->when($this->selectedLeaveType, function ($query) {
                $query->where('leave_type_id', $this->selectedLeaveType);
            })
            ->latest()->paginate($this->perPage);
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
    {   //check if the employee as leave credits
        // $leaveCredit = Employee
        $this->validate([
            'leave_type' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
            'reason' => 'required',
        ]);

        if (empty($this->employeeData)) {
            $this->alert('error', 'Please select an employee.');
            return;
        }

        $start = Carbon::parse($this->date_from);
        $end = Carbon::parse($this->date_to);

        if ($end->lessThan($start)) {
            $this->alert('error', 'Date To must be greater than or equal to Date From.');
            return;
        }

        $diffInDays = $end->diffInDays($start) + 1;

        $leaveType = LeaveType::find($this->leave_type);

        $create = LeaveRequest::create([
            'employee_id' => $this->employee_id,
            'leave_type_id' => $this->leave_type,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'reason' => $this->reason,
            'is_half_day' => $this->halfday,
            'choosen_half' => $this->choosen_half,
            'with_pay_number_of_days' => ($leaveType->is_payable ? ($this->halfday ? 0.5 * $diffInDays : $diffInDays) : 0),
            'without_pay_number_of_days' => (!$leaveType->is_payable ? ($this->halfday ? 0.5 * $diffInDays : $diffInDays) : 0),
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
        $this->halfday = $data->is_half_day == 1 ? true : false;

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
            'choosen_half' => $this->choosen_half,
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

    public function alertApproveConfirm($id)
    {
        $this->approveApproveConfirmed = $id;

        $this->confirm('Are you sure you want to approve this leave?', [
            'confirmButtonText' => 'Yes Approve it!',
            'onConfirmed' => 'approve',
        ]);
    }

    public function approve()
    {
        $data = LeaveRequest::find($this->approveApproveConfirmed);
        $data->update([
            'status' => 'Approved',
            'date_approved' => now(),
            'approved_by' => Auth::user()->id,
        ]);
        $employee = Employee::find($data->employee_id);
        if (!$data->is_half_day) {
            if ($data->with_pay_number_of_days > 1 || $data->without_pay_number_of_days > 1) {
                // Determine the number of days of leave
                $days = $data->with_pay_number_of_days != 0 ? $data->with_pay_number_of_days : $data->without_pay_number_of_days;

                // Convert the starting date to a DateTime object
                $dateFrom = new DateTime($data->date_from);

                // Loop through each day if $days is more than one
                for ($i = 0; $i < $days; $i++) {
                    // Clone the starting date and add $i days
                    $currentDate = (clone $dateFrom)->modify("+{$i} days");

                    // Update or create the attendance record for the current day
                    Attendance::updateOrCreate(
                        [
                            'employee_number' => $employee->employee_number,
                            'date' => $currentDate->format('Y-m-d'),
                            'leave_id' => $data->id,
                        ],
                        [
                            'leave_id' => $data->id,
                            'time_in' => $employee->workshift->start,
                            'time_out_2' => $employee->workshift->end,
                            'on_leave' => 'whole_day',
                            'created_by' => Auth::user()->id,
                            'fortnight_id' => Helpers::getFortnightIdByDate($currentDate->format('Y-m-d'))
                        ]
                    );
                }
            } else {
                Attendance::updateOrCreate(
                    [
                        'employee_number' => $employee->employee_number,
                        'date' => $data->date_from,
                        'leave_id' => $data->id,
                    ],
                    [
                        'leave_id' => $data->id,
                        'time_in' => $employee->workshift->start,
                        'time_out_2' => $employee->workshift->end,
                        'on_leave' => 'whole_day',
                        'created_by' => Auth::user()->id,
                        'fortnight_id' => Helpers::getFortnightIdByDate($data->date_from)
                    ]
                );
            }

        } else {
            if ($data->choosen_half == 'first_half') {

                $time_in = new DateTime($employee->workshift->start);
                $time_out = (clone $time_in)->modify('+4 hours');

                $attendance = [
                    'leave_id' => $data->id,
                    'time_in' => $time_in->format('H:i:s'),
                    'time_out' => $time_out->format('H:i:s'),
                    'on_leave' => 'first_half',
                    'created_by' => Auth::user()->id,
                    'fortnight_id' => Helpers::getFortnightIdByDate($data->date_from)
                ];
            } else {
                $time_out_2 = new DateTime($employee->workshift->end);
                $time_in_2 = (clone $time_out_2)->modify('-4 hours');

                $attendance = [
                    'leave_id' => $data->id,
                    'time_in_2' => $time_in_2->format('H:i:s'),
                    'time_out_2' => $time_out_2->format('H:i:s'),
                    'on_leave' => 'second_half',
                    'created_by' => Auth::user()->id,
                    'fortnight_id' => Helpers::getFortnightIdByDate($data->date_from)
                ];
            }

            Attendance::updateOrCreate(
                [
                    'employee_number' => $employee->employee_number,
                    'date' => $data->date_from,
                    'leave_id' => $data->id,
                ],
                $attendance
            );
        }

        if ($data) {
            $this->alert('success', 'Leave Request has been approved!');
        }
    }

    public function alertRejectConfirm($id)
    {
        $this->approveRejectConfirmed = $id;

        $this->confirm('Are you sure you want to reject this leave?', [
            'confirmButtonText' => 'Yes Reject it!',
            'onConfirmed' => 'reject',
        ]);
    }

    public function reject()
    {
        $data = LeaveRequest::find($this->approveRejectConfirmed);
        $data->update([
            'status' => 'Rejected',
            'updated_by' => Auth::user()->id,
        ]);

        if ($data) {
            $this->alert('success', 'Leave Request has been rejected!');
        }
    }

    public function alertCancelConfirm($id)
    {
        $this->approveCancelConfirmed = $id;

        $this->confirm('Are you sure you want to cancel this leave?', [
            'confirmButtonText' => 'Yes Cancel it!',
            'onConfirmed' => 'cancel',
        ]);
    }

    public function cancel()
    {
        $data = LeaveRequest::find($this->approveCancelConfirmed);
        $data->update([
            'status' => 'Cancelled',
            'updated_by' => Auth::user()->id,
        ]);

        if ($data) {
            $this->alert('success', 'Leave Request has been cancelled!');
        }
    }

    public function alertOnHoldConfirm($id)
    {
        $this->approveOnHoldConfirmed = $id;

        $this->confirm('Are you sure you want to change to on-hold this leave?', [
            'confirmButtonText' => 'Yes Change to On-Hold!',
            'onConfirmed' => 'onHold',
        ]);
    }

    public function onHold()
    {
        $data = LeaveRequest::find($this->approveOnHoldConfirmed);
        $data->update([
            'status' => 'On-Hold',
            'updated_by' => Auth::user()->id,
        ]);

        if ($data) {
            $this->alert('success', 'Leave Request has been changed to On-Hold!');
        }
    }

    public function alertRevertConfirm($id)
    {
        $this->approveRevertConfirmed = $id;

        $this->confirm('Are you sure you want to revert this leave?', [
            'confirmButtonText' => 'Yes revert it!',
            'onConfirmed' => 'revert',
        ]);
    }

    public function revert()
    {
        $data = LeaveRequest::find($this->approveRevertConfirmed);
        $data->update([
            'status' => 'Pending',
            'updated_by' => Auth::user()->id,
        ]);

        Attendance::where('leave_id', $this->approveRevertConfirmed)->forceDelete();

        if ($data) {
            $this->alert('success', 'Leave Request has been revert!');
        }
    }
}
