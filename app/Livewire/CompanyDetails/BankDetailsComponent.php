<?php

namespace App\Livewire\CompanyDetails;

use Livewire\Component;
use App\Models\Business;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Exports\BusinessExport;
use App\Models\CompanyBank;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BankDetailsComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Bank';
    public $updateMode = false;

    public $code;
    public $account_name;
    public $account_number;
    public $bsb;
    public $edit_id;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    public $selectAllBusiness = false;
    public $selectedBusinessRows = [];

    public $businesses = [];

    #[Title('Banks')]
    public function render()
    {
        return view('livewire.company-details.bank-details-component', [
            'records' => $this->records
        ]);
    }

    public function mount()
    {
        $this->businesses = Business::where('is_active', 1)->get();
    }

    public function getRecordsProperty()
    {
        return CompanyBank::search(trim($this->search))
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

    public function updatedSelectAllBusiness($value)
    {
        if ($value) {
            $this->selectedBusinessRows = $this->businesses->pluck('id');
        } else {
            $this->selectedBusinessRows = [];
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
            'account_name' => 'required',
            'account_number' => 'required',
            'bsb' => 'required'
        ]);

        $create = CompanyBank::create([
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'account_bsb' => $this->bsb,
        ]);

        $create->businesses()->sync($this->selectedBusinessRows);

        if ($create) {
            $this->resetInputFields();
            if ($saveAndCreateNew) {
                $this->alert('success', 'New Company Bank Details has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');

                $this->alert('success', 'New Company Bank Details has been save successfully!');
            }
        }
    }

    public function resetInputFields()
    {
        $this->account_name = '';
        $this->account_number = '';
        $this->bsb = '';
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->dispatch('show-add-modal');
        $data = CompanyBank::find($id);
        $this->account_name = $data->account_name;
        $this->account_number = $data->account_number;
        $this->bsb = $data->account_bsb;
        $this->selectedBusinessRows = $data->businesses()->pluck('business_id')->toArray();
        $this->modalTitle = 'Edit Data';
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'account_name' => 'required',
            'account_number' => 'required',
            'bsb' => 'required'
        ]);

        $data = CompanyBank::find($this->edit_id);
        $data->update([
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'account_bsb' => $this->bsb
        ]);

        $data->businesses()->sync($this->selectedBusinessRows);

        if ($data) {
            $this->dispatch('hide-add-modal');

            $this->resetInputFields();

            $this->alert('success', $data->account_name . ' has been updated!');
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
        $delete = CompanyBank::find($this->approveConfirmed);
        $name = $delete->account_name;
        $delete->delete();
        if ($delete) {
            $this->alert('success', $name . ' has been removed!');
        }
    }

    public function export()
    {
        return Excel::download(new BusinessExport, 'business.xlsx');
    }
}
