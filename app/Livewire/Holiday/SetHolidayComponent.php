<?php

namespace App\Livewire\Holiday;

use Livewire\Component;
use App\Models\Holiday;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SetHolidayComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Holiday';
    public $updateMode = false;

    public $holiday_name;
    public $holiday_date;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    #[Title('Holiday')]
    public function render()
    {
        return view('livewire.holiday.set-holiday-component', [
            'records' => $this->records
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getRecordsProperty()
    {
        return Holiday::search(trim($this->search))->paginate($this->perPage);
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
            'holiday_name' => 'required',
            'holiday_date' => 'required',
        ]);

        $create = Holiday::create([
            'holiday_name' => $this->holiday_name,
            'holiday_date' => $this->holiday_date,
        ]);

        if ($create) {
            $this->resetInputFields();
            if ($saveAndCreateNew) {
                $this->alert('success', 'New Holiday has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'New Holiday has been save successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->holiday_name = '';
        $this->holiday_date = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = Holiday::find($id);
        $this->holiday_name = $data->holiday_name;
        $this->holiday_date = $data->holiday_date;
        $this->modalTitle = 'Edit ' . $this->holiday_name;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'holiday_name' => 'required',
            'holiday_date' => 'required'
        ]);

        $data = Holiday::find($this->edit_id);
        $data->update([
            'holiday_name' => $this->holiday_name,
            'holiday_date' => $this->holiday_date,
        ]);

        if ($data) {
            $this->dispatch('hide-add-modal');

            $this->resetInputFields();

            $this->alert('success', $data->holiday_name . ' has been updated!');
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
        $delete = Holiday::find($this->approveConfirmed);
        $holiday_name = $delete->holiday_name;
        $delete->delete();
        if ($delete) {
            $this->alert('success', $holiday_name . ' has been removed!');
        }
    }
}
