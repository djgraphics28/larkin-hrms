<?php

namespace App\Livewire\Asset;

use App\Models\Asset;
use Livewire\Component;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\AssetType;
use App\Models\BusinessUser;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AssetComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove','selectedEmployee'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Asset';
    public $updateMode = false;

    public $businessId;
    public $asset_code;
    public $name;
    public $note;
    public $quantity = 1;
    public $date_received;
    public $date_returned;
    public $is_working;
    public $serial_number;
    public $asset_type;
    public $employee = '';
    public $employeeName = '';
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];
    public $assetTypes = [];
    public $employees = [];

    #[Title('Assets')]
    public function render()
    {
        return view('livewire.asset.asset-component',[
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
        $this->assetTypes = AssetType::where('is_active', 1)->get();
        $this->employees = Employee::where('business_id', $this->businessId)->where('is_discontinued', false)->get();
    }

    public function getRecordsProperty()
    {
        return Asset::search(trim($this->search))
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
        $this->modalTitle = 'Add New Asset';
        $this->updateMode = false;

        $this->asset_code = Helpers::generateAssetCode($this->businessId);
    }

    public function submit($saveAndCreateNew)
    {
        $this->validate([
            'name' => 'required',
            'asset_type' => 'required',
        ]);

        $create = Asset::create([
            'asset_code' => $this->asset_code,
            'name' => $this->name,
            'asset_type_id' => $this->asset_type,
            'employee_id' => $this->employee,
            'note' => $this->note,
            'serial_number' => $this->serial_number,
            'business_id' => $this->businessId,
        ]);

        if($create){
            $this->resetInputFields();
            if($saveAndCreateNew) {
                $this->alert('success', 'New Asset has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');
                $this->alert('success', 'New Asset has been save successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->asset_type = '';
        $this->note = '';
        $this->date_received = '';
        $this->date_returned = '';
        $this->employee = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = Asset::find($id);
        $this->name = $data->name;
        $this->quantity = $data->quantity;
        $this->asset_code = $data->asset_code;
        $this->asset_type = $data->asset_type_id;
        $this->employee = $data->employee_id;
        $this->date_received = $data->date_received;
        $this->date_returned = $data->date_returned;
        $this->is_working = $data->is_working;
        $this->note = $data->note;
        $this->serial_number = $data->serial_number;
        $this->modalTitle = 'Edit '.$this->name;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'asset_type' => 'required',
        ]);

        $data = Asset::find($this->edit_id);
        $data->update([
            'name' => $this->name,
            'note' => $this->note,
            'is_working' => $this->is_working,
            'asset_type_id' => $this->asset_type,
            'quantity' => $this->quantity,
            'date_received' => $this->date_received,
            'date_returned' => $this->date_returned,
            'employee_id' => $this->employee == "" ? null : $this->employee,
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
        $delete = Asset::find($this->approveConfirmed);
        $name = $delete->name;
        $delete->delete();
        if($delete){
            $this->alert('success', $name.' has been removed!');
        }
    }

    public function selectedEmployee($employee_id)
    {
        $employee = Employee::find($employee_id);

        $this->employee = $employee_id;
        $this->employeeName = $employee->employee_number.' | '.$employee->first_name. ' ' .$employee->last_name;

    }
}
