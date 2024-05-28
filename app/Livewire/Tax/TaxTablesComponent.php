<?php

namespace App\Livewire\Tax;

use Livewire\Component;
use App\Models\TaxTable;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\TaxTableRange;
use Livewire\Attributes\Title;
use App\Exports\DepartmentExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TaxTablesComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove'];
    public $approveConfirmed;
    // filters
    #[Url]
    public $perPage = 10;
    #[Url]
    public $search = '';

    public $description;
    public $percentage;
    public $editId = null;

    protected $paginationTheme = 'bootstrap';

    public $selectAll = false;
    public $selectedRows = [];

    public $effective_date;
    public $ranges = [];

    #[Title('Tax Table')]
    public function render()
    {
        return view('livewire.tax.tax-tables-component', [
            'records' => $this->records
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->addRange();
    }

    public function getRecordsProperty()
    {
        return TaxTable::with('tax_table_ranges')->search(trim($this->search))->paginate($this->perPage);
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

    public function resetInputFields()
    {
        $this->description = '';
        $this->effective_date = '';
    }

    public function edit($id)
    {
        $this->editId = $id;
        $data = TaxTable::find($id);
        if($data) {
            $this->ranges = [];
            $this->description = $data->description;
            $this->effective_date = $data->effective_date;

        // Initialize $this->ranges with a default structure
        // $this->ranges[] = ['description' => null, 'from' => null, 'to' => null, 'percentage' => null];

            // Retrieve and assign tax table ranges if any exist
            $taxTableRanges = TaxTableRange::where('tax_table_id', $id)->get();
            if (!empty($taxTableRanges)) {
                foreach($taxTableRanges as $range) {
                    $this->ranges[] = ['description' => $range->description, 'from' => $range->from, 'to' => $range->to, 'percentage' => $range->percentage];
                }
            }
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

    public function addRange()
    {
        $this->ranges[] = ['description' => null, 'from' => null, 'to' => null, 'percentage' => null];
    }

    public function removeRange($index)
    {
        unset($this->ranges[$index]);
        $this->ranges = array_values($this->ranges); // Reset array keys
    }

    public function save()
    {

        if($this->description == '') {
            $this->alert('warning', 'Tax Description is required!');
            return;
        }

        if($this->effective_date == '') {
            $this->alert('warning', 'Effective Date is required!');
            return;
        }

        $this->validate([
            'description' => 'required',
            'effective_date' => 'required'
        ]);

        // Create or update TaxTable
        $taxTableData = [
            'description' => $this->description,
            'effective_date' => $this->effective_date
        ];

        if (is_null($this->editId)) {
            $taxTableData['created_by'] = Auth::user()->id;
            $taxTable = TaxTable::create($taxTableData);

            // Create or update TaxTableRanges
            foreach ($this->ranges as $range) {
                TaxTableRange::create(
                    [
                        'tax_table_id' => $taxTable->id,
                        'description' => $range['description'],
                        'from' => $range['from'],
                        'to' => $range['to'],
                        'percentage' => $range['percentage'],
                    ]
                );
            }
        } else {
            $taxTableData['updated_by'] = Auth::user()->id;
            $taxTable = TaxTable::updateOrCreate(['id' => $this->editId], $taxTableData);
            $taxTable->tax_table_ranges()->delete();
            // Create or update TaxTableRanges
            foreach ($this->ranges as $range) {
                TaxTableRange::create(
                    [
                        'tax_table_id' => $taxTable->id,
                        'description' => $range['description'],
                        'from' => $range['from'],
                        'to' => $range['to'],
                        'percentage' => $range['percentage'],
                    ]
                );
            }
        }

        $this->editId = null;
        $this->ranges = [];
        $this->addRange();
        $this->resetInputFields();

        $this->alert('success', 'Tax Table has been saved successfully!');
    }
}
