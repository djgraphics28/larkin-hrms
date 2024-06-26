<?php

namespace App\Livewire\Loan;

use Livewire\Component;
use App\Models\LoanType;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class LoanTypeComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Loan Type';
    public $updateMode = false;

    public $name;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    #[Title('Loan Type')]
    public function render()
    {
        return view('livewire.loan.loan-type-component', [
            'records' => $this->records
        ]);
    }

    public function getRecordsProperty()
    {
        return LoanType::search(trim($this->search))
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
        $this->resetInputFields();
        $this->dispatch('show-add-modal');
        $this->modalTitle = 'Add New Loan Type';
        $this->updateMode = false;

    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'name' => 'required'
        ]);

        $create = LoanType::create([
            'name' => $this->name
        ]);

        if ($create) {
            $this->resetInputFields();
            if ($saveAndCreateNew) {
                $this->alert('success', 'New Loan Type has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'New Loan Type has been save successfully!');
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
        $data = LoanType::find($id);
        $this->name = $data->name;
        $this->modalTitle = 'Edit ' . $this->name;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        $data = LoanType::find($this->edit_id);
        $data->update([
            'name' => $this->name
        ]);

        if ($data) {
            $this->dispatch('hide-add-modal');

            $this->resetInputFields();

            $this->alert('success', $data->name . ' has been updated!');
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
        $delete = LoanType::find($this->approveConfirmed);
        $name = $delete->name;
        $delete->delete();
        if ($delete) {
            $this->alert('success', $name . ' has been removed!');
        }
    }
}
