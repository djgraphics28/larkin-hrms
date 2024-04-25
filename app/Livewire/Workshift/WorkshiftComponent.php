<?php

namespace App\Livewire\Workshift;

use Carbon\Carbon;
use App\Models\WeekDay;
use Livewire\Component;
use App\Models\Workshift;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Exports\BusinessExport;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class WorkshiftComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Workshift';
    public $updateMode = false;

    public $title;
    public $description;
    public $number_of_hours = 7;
    public $start;
    public $end;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    public $selectedDayRows = [];

    public $weekDays = [];

    #[Title('Workshift')]
    public function render()
    {
        return view('livewire.workshift.workshift-component',[
            'records' => $this->records
        ]);
    }

    public function mount()
    {
        $this->weekDays = WeekDay::all();
    }

    public function getRecordsProperty()
    {
        return Workshift::with('day_offs')->search(trim($this->search))
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
        $this->modalTitle = 'Add New Workshift';
        $this->updateMode = false;
    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
            // 'number_of_hours' => 'required',
            'start' => 'required',
            'end' => 'required',
        ]);

        $start = Carbon::parse($this->start);
        $end = Carbon::parse($this->end);

        $diffInHours = $end->diffInHours($start);

        // if($this->number_of_hours != $diffInHours) {
        //     $this->alert('error', 'Please correct the start and end time!');
        //     return 0;
        // }

        $create = Workshift::create([
            'title' => $this->title,
            'description' => $this->description,
            'number_of_hours' => $diffInHours,
            'start' => $this->start,
            'end' => $this->end,
        ]);

        $create->day_offs()->sync($this->selectedDayRows);

        if($create){
            $this->resetInputFields();
            if($saveAndCreateNew) {
                $this->alert('success', 'New Workshift has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');

                $this->alert('success', 'New Workshift has been save successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->title = '';
        $this->description = '';
        $this->number_of_hours = 7;
        $this->start = '';
        $this->end = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = Workshift::find($id);
        $this->title = $data->title;
        $this->description = $data->description;
        $this->number_of_hours = $data->number_of_hours;
        $this->start = $data->start;
        $this->end = $data->end;
        $this->meridiem = $data->meridiem;
        $this->selectedDayRows = $data->day_offs()->pluck('week_day_id')->toArray();
        $this->modalTitle = 'Edit Workshift';
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
            // 'number_of_hours' => 'required',
            'start' => 'required',
            'end' => 'required',
        ]);

        $start = Carbon::parse($this->start);
        $end = Carbon::parse($this->end);

        $diffInHours = $end->diffInHours($start);

        $data = Workshift::find($this->edit_id);
        $data->update([
            'title' => $this->title,
            'description' => $this->description,
            'number_of_hours' => $diffInHours,
            'start' => $this->start,
            'end' => $this->end,
        ]);

        $data->day_offs()->sync($this->selectedDayRows);

        if($data) {
            $this->dispatch('hide-add-modal');

            $this->resetInputFields();

            $this->alert('success', $data->title.' has been updated!');
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
        $delete = Workshift::find($this->approveConfirmed);
        $name = $delete->title;
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
