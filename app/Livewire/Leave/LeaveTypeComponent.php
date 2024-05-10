<?php

namespace App\Livewire\Leave;

use App\Models\LeaveType;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class LeaveTypeComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Leave Type';
    public $updateMode = false;

    public $name;
    public $is_payable;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    #[Title('Leave Types')]
    public function render()
    {
        return view('livewire.leave.leave-type-component',[
            'records' => $this->records
        ]);
    }

    public function getRecordsProperty()
    {
        return LeaveType::search(trim($this->search))
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
        $this->resetInputFields();
        $this->dispatch('show-add-modal');
        $this->modalTitle = 'Add New Leave Type';
        $this->updateMode = false;

    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'name' => 'required',
            'is_payable' => 'required'
        ]);

        $create = LeaveType::create([
            'name' => $this->name,
            'is_payable' => $this->is_payable,
        ]);

        if($create){
            $this->resetInputFields();
            if($saveAndCreateNew) {
                $this->alert('success', 'New Leave Type has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'New Leave Type has been save successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->is_payable = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = LeaveType::find($id);
        $this->name = $data->name;
        $this->is_payable = $data->is_payable;
        $this->modalTitle = 'Edit '.$this->name;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'is_payable' => 'required',
        ]);

        $data = LeaveType::find($this->edit_id);
        $data->update([
            'name' => $this->name,
            'is_payable' => $this->is_payable,
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
        $delete = LeaveTypeComponent::find($this->approveConfirmed);
        $name = $delete->name;
        $delete->delete();
        if($delete){
            $this->alert('success', $name.' has been removed!');
        }
    }
}
