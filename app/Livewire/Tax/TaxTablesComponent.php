<?php

namespace App\Livewire\Tax;

use Livewire\Component;
use App\Models\TaxTable;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Exports\DepartmentExport;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TaxTablesComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Tax';
    public $updateMode = false;

    public $description;
    public $range_to;
    public $range_from;
    public $percentage;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    #[Title('Tax')]
    public function render()
    {
        return view('livewire.tax.tax-tables-component', [
            'records' => $this->records
        ]);
    }

    public function getRecordsProperty()
    {
        return TaxTable::search(trim($this->search))->paginate($this->perPage);
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
        $this->modalTitle = 'Add New Tax';
        $this->updateMode = false;
    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'description' => 'required',
            'range_from' => 'required',
            'range_to' => 'required',
            'percentage' => 'required'
        ]);

        $create = TaxTable::create([
            'description' => $this->description,
            'from' => $this->range_from,
            'to' => $this->range_to,
            'percentage' => $this->percentage
        ]);

        if ($create) {
            $this->resetInputFields();
            if ($saveAndCreateNew) {
                $this->alert('success', 'New Tax has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'New Tax has been save successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->description = '';
        $this->range_from = '';
        $this->range_to = '';
        $this->percentage = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = TaxTable::find($id);
        $this->description = $data->description;
        $this->range_from = $data->from;
        $this->range_to = $data->to;
        $this->percentage = $data->percentage;
        $this->modalTitle = 'Edit ' . $this->description;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'description' => 'required',
            'range_from' => 'required',
            'range_to' => 'required',
            'percentage' => 'required'
        ]);

        $data = TaxTable::find($this->edit_id);
        $data->update([
            'description' => $this->description,
            'from' => $this->range_from,
            'to' => $this->range_to,
            'percentage' => $this->percentage
        ]);

        if ($data) {
            $this->dispatch('hide-add-modal');

            $this->resetInputFields();

            $this->alert('success', $data->description . ' has been updated!');
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
        $delete = TaxTable::find($this->approveConfirmed);
        $description = $delete->description;
        $delete->delete();
        if ($delete) {
            $this->alert('success', $description . ' has been removed!');
        }
    }

    public function export()
    {
        return Excel::download(new DepartmentExport, 'department.xlsx');
    }
}
