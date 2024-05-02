<?php

namespace App\Livewire\Asset;

use Livewire\Component;
use App\Models\AssetType;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AssetTypeComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Asset Type';
    public $updateMode = false;

    public $name;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    #[Title('Asset Type')]
    public function render()
    {
        return view('livewire.asset.asset-type-component',[
            'records' => $this->records
        ]);
    }

    public function getRecordsProperty()
    {
        return AssetType::search(trim($this->search))
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
        $this->modalTitle = 'Add New Asset Type';
        $this->updateMode = false;

    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'name' => 'required'
        ]);

        $create = AssetType::create([
            'name' => $this->name
        ]);

        if($create){
            $this->resetInputFields();
            if($saveAndCreateNew) {
                $this->alert('success', 'New Asset Type has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'New Asset Type has been save successfully!');
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
        $data = AssetType::find($id);
        $this->name = $data->name;
        $this->modalTitle = 'Edit '.$this->name;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        $data = AssetType::find($this->edit_id);
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
        $delete = AssetType::find($this->approveConfirmed);
        $name = $delete->name;
        $delete->delete();
        if($delete){
            $this->alert('success', $name.' has been removed!');
        }
    }
}
