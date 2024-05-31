<?php

namespace App\Livewire\Compensation;

use App\Models\CompensationItem;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AllowanceAndDeductionComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Compensation';
    public $updateMode = false;

    public $name;
    public $type;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    #[Title('Department')]
    public function render()
    {
        return view('livewire.compensation.allowance-and-deduction-component',[
            'records' => $this->records
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getRecordsProperty()
    {
        return CompensationItem::search(trim($this->search))
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
        $this->modalTitle = 'Add New Compensantion';
        $this->updateMode = false;

    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'name' => 'required',
            'type' => 'required',
        ]);

        $create = CompensationItem::create([
            'name' => $this->name,
            'type' => $this->type,
        ]);

        if($create){
            $this->resetInputFields();
            if($saveAndCreateNew) {
                $this->alert('success', 'New Compensation has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'New Compensation has been save successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->type = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = CompensationItem::find($id);
        $this->name = $data->name;
        $this->modalTitle = 'Edit '.$this->name;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        $data = CompensationItem::find($this->edit_id);
        $data->update([
            'name' => $this->name,
            'type' => $this->type,
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
        $delete = CompensationItem::find($this->approveConfirmed);
        $name = $delete->name;
        $delete->delete();
        if($delete){
            $this->alert('success', $name.' has been removed!');
        }
    }
}
