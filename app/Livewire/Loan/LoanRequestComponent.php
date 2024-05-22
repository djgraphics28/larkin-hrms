<?php

namespace App\Livewire\Loan;

use App\Jobs\SendLoanRequestJob;
use App\Models\Loan;
use Livewire\Component;
use App\Models\Employee;
use App\Models\LoanType;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class LoanRequestComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove', 'selectedEmployee'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Request New Loan';
    public $updateMode = false;

    public $businessId;
    public $loan_type;
    #[Url]
    public $employeeId;
    public $employee;
    public $amount;
    public $reason;
    public $payable_for;
    public $date_requested;
    public $amount_to_be_deducted;
    public $status;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    public $loanTypes = [];
    public $employees = [];

    #[Title('Loan/Cash Advance Request')]
    public function render()
    {
        return view('livewire.loan.loan-request-component', [
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

        $this->loanTypes = LoanType::where('is_active', 1)->get();
        $this->employees = Employee::where('business_id', $this->businessId)->get();
    }

    public function getRecordsProperty()
    {
        return Loan::with(['employee', 'loan_type'])->where('business_id', $this->businessId)
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
        $this->resetInputFields();
        $this->dispatch('show-add-modal');
        $this->modalTitle = 'Request New Loan';
        $this->updateMode = false;

    }

    public function submit()
    {
        $this->validate([
            'employee' => 'required',
            'loan_type' => 'required',
            'payable_for' => 'required',
            'amount' => 'required',
        ]);

        // Check if there is an existing loan
        $loan = Loan::where('employee_id', $this->employeeId)->where('loan_type_id', $this->loan_type)->orderBy('id', 'desc')->first();

        if ($loan) {
            // Get the latest loan payment
            $latestPayment = $loan->loan_payments()->orderBy('created_at', 'desc')->first();

            // Check if there is a balance and if it is greater than 0
            if ($latestPayment) {
                if ($latestPayment->balance > 0) {
                    $this->alert('error', 'Please clear your current loan first!');
                    return;
                }
            } else {
                // Handle the case where there are no payments yet
                $this->alert('error', $this->employee.' have a pending loan without any payments yet!');
                return;
            }
        }


        $create = Loan::create([
            'employee_id' => $this->employeeId,
            'loan_type_id' => $this->loan_type,
            'payable_for' => $this->payable_for,
            'amount_to_be_deducted' => $this->amount_to_be_deducted,
            'reason' => $this->reason,
            'amount' => $this->amount,
            'date_requested' => now(),
            'status' => 'Pending',
            'business_id' => $this->businessId,
        ]);

        if ($create) {
            $employee = Employee::find($this->employeeId);
            dispatch(new SendLoanRequestJob($employee, $create));

            $this->dispatch('hide-add-modal');
            $this->alert('success', 'New Loan Request has been save successfully!');
        }
    }

    public function resetInputFields()
    {
        $this->employee = '';
        $this->loan_type = '';
        $this->payable_for = '';
        $this->amount_to_be_deducted = '';
        $this->reason = '';
        $this->amount = '';
        $this->status = '';
        $this->date_requested = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = Loan::find($id);
        $this->searchEmployee($data->employee_id);
        $this->loan_type = $data->loan_type_id;
        $this->status = $data->status;
        // $this->employee = $data->employee_id;
        $this->payable_for = $data->payable_for;
        $this->amount_to_be_deducted = $data->amount_to_be_deducted;
        $this->reason = $data->reason;
        $this->amount = $data->amount;
        $this->date_requested = $data->date_requested;

        $this->modalTitle = 'Edit Loan Request';
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'employee' => 'required',
            'loan_type' => 'required',
            'payable_for' => 'required',
            'amount' => 'required',
            'status' => 'required',
        ]);

        $data = Loan::find($this->edit_id);
        $data->update([
            'employee_id' => $this->employeeId,
            'loan_type_id' => $this->loan_type,
            'payable_for' => $this->payable_for,
            'amount_to_be_deducted' => $this->amount_to_be_deducted,
            'reason' => $this->reason,
            'amount' => $this->amount,
            'date_requested' => $this->date_requested,
            'status' => $this->status,
        ]);

        if ($this->status == 'Approved') {
            $data->update([
                'date_approved' => now(),
                'approved_by' => Auth::user()->id,
            ]);
        }

        if ($data) {
            $this->dispatch('hide-add-modal');

            $this->resetInputFields();

            $this->alert('success', 'Loan Request has been updated!');
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
        $delete = Loan::find($this->approveConfirmed);
        $delete->delete();
        if ($delete) {
            $this->alert('success', 'Loan Request has been removed!');
        }
    }

    public function selectedEmployee($employee_id)
    {
        $this->searchEmployee($employee_id);
    }

    public function searchEmployee($id)
    {
        $employee = Employee::find($id);
        $this->employeeId = $employee->id;
        $this->employee = $employee->employee_number . ' | ' . $employee->first_name . ' ' . $employee->last_name;
    }
}
