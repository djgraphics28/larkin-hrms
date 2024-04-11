<?php

namespace App\Livewire\Business;

use Livewire\Component;
use App\Models\Business;
use App\Models\Department;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Exports\BusinessExport;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BusinessComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Business|Branch';
    public $updateMode = false;

    public $name;
    public $contact_number;
    public $address;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    public $selectAllDepartment = false;
    public $selectedDepartmentRows = [];

    public $departments = [];

    #[Title('Business')]
    public function render()
    {
        return view('livewire.business.business-component',[
            'records' => $this->records
        ]);
    }

    public function mount()
    {
        $this->departments = Department::where('is_active',1)->get();
    }

    public function getRecordsProperty()
    {
        return Business::withCount('employees')->search(trim($this->search))
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

    public function updatedSelectAllDepartment($value)
    {
        if($value){
            $this->selectedDepartmentRows = $this->departments->pluck('id');
        }else{
            $this->selectedDepartmentRows = [];
        }
    }

    public function addNew()
    {
        $this->resetInputFields();
        $this->dispatch('show-add-modal');
        $this->modalTitle = 'Add New Data';
        $this->updateMode = false;
    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'name' => 'required'
        ]);

        $create = Business::create([
            'name' => $this->name,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
        ]);

        $data->departments()->sync($this->selectedDepartmentRows);

        if($create){
            $this->resetInputFields();
            if($saveAndCreateNew) {
                $this->alert('success', 'New Business has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');

                $this->alert('success', 'New Business has been save successfully!');
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
        $data = Business::find($id);
        $this->name = $data->name;
        $this->contact_number = $data->contact_number;
        $this->address = $data->address;
        $this->selectedDepartmentRows = $data->departments()->pluck('department_id')->toArray();
        $this->modalTitle = 'Edit Data';
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        $data = Business::find($this->edit_id);
        $data->update([
            'name' => $this->name,
            'contact_number' => $this->contact_number,
            'address' => $this->address
        ]);

        $data->departments()->sync($this->selectedDepartmentRows);

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
        $delete = Business::find($this->approveConfirmed);
        $name = $delete->name;
        $delete->delete();
        if($delete){
            $this->alert('success', $name.' has been removed!');
        }
    }

    public function export()
    {
        return Excel::download(new BusinessExport, 'business.xlsx');
    }
}
