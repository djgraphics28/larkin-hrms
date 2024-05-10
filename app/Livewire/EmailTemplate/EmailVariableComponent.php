<?php

namespace App\Livewire\EmailTemplate;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\EmailVariable;
use Livewire\Attributes\Title;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class EmailVariableComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Email Variable';
    public $updateMode = false;

    public $name;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    #[Title('Email Variable')]
    public function render()
    {
        return view('livewire.email-template.email-variable-component',[
            'records' => $this->records
        ]);
    }

    public function getRecordsProperty()
    {
        return EmailVariable::search(trim($this->search))
            ->orderBy('name', 'asc')
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
        $this->modalTitle = 'Add New Email Variable';
        $this->updateMode = false;

    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'name' => 'required'
        ]);

        $variableName = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->name)));

        $create = EmailVariable::create([
            'name' => $this->name,
            'variable' => lcfirst($variableName),
        ]);

        if($create){
            $this->resetInputFields();
            if($saveAndCreateNew) {
                $this->alert('success', 'New Email Variable has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'New Email Variable has been save successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->name = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = EmailVariable::find($id);
        $this->name = $data->name;
        $this->modalTitle = 'Edit '.$this->name;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        $variableName = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->name)));

        $data = EmailVariable::find($this->edit_id);
        $data->update([
            'name' => $this->name,
            'variable' => lcfirst($variableName)
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
        $delete = EmailVariable::find($this->approveConfirmed);
        $name = $delete->name;
        $delete->delete();
        if($delete){
            $this->alert('success', $name.' has been removed!');
        }
    }
}
