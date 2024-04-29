<?php

namespace App\Livewire\Loan;

use App\Models\Loan;
use Livewire\Component;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class LoanRequestComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Request New Loan';
    public $updateMode = false;

    public $businessId;
    public $loan_type;
    public $employee;
    public $amount;
    public $reason;
    public $payable_for;
    public $percentage_to_be_deducted;
    public $status;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    #[Title('Loan/Cash Advance Request')]
    public function render()
    {
        return view('livewire.loan.loan-request-component',[
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
    }

    public function getRecordsProperty()
    {
        return Loan::with(['employee','loan_type'])->where('business_id', $this->businessId)
            ->latest()->paginate($this->perPage);
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
        $this->modalTitle = 'Request New Loan';
        $this->updateMode = false;

    }

    public function submit()
    {
        $this->validate([
            'name' => 'required'
        ]);

        $create = Loan::create([
            'name' => $this->name
        ]);

        if($create){
            $this->dispatch('hide-add-modal');
            $this->alert('success', 'New Loan Request has been save successfully!');
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

        $data = Department::find($this->edit_id);
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
        $delete = Department::find($this->approveConfirmed);
        $name = $delete->name;
        $delete->delete();
        if($delete){
            $this->alert('success', $name.' has been removed!');
        }
    }

    public function export()
    {
        return Excel::download(new DepartmentExport, 'department.xlsx');
    }
}
