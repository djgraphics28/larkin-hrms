<?php

namespace App\Livewire\Business;

use App\Models\BusinessCompensation;
use App\Models\CompensationItem;
use Livewire\Component;
use App\Models\Business;
use App\Models\Department;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Exports\BusinessExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BusinessComponent extends Component
{
    use WithPagination, LivewireAlert;

    protected $listeners = ['remove','removeAllowanceApproved', 'removeDeductionApproved'];
    public $approveConfirmed;
    public $approveRemoveAllowance;
    public $approveRemoveDeduction;

    public $allowanceIndex;
    public $deductionIndex;
    // filters
    public $perPage = 10;
    #[Url]
    public $search = '';
    public $modalTitle = 'Add New Business|Branch';
    public $updateMode = false;

    public $businesId;
    public $code;
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
    public $allowances = [];
    public $deductions = [];

    public $allowanceItems = [];
    public $deductionItems = [];

    #[Title('Business')]
    public function render()
    {
        return view('livewire.business.business-component',[
            'records' => $this->records
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->departments = Department::where('is_active',1)->get();
        $this->allowanceItems = CompensationItem::where('type','Allowance')->get();
        $this->deductionItems = CompensationItem::where('type','Deduction')->get();
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
            'code' => 'required',
            'name' => 'required',
        ]);

        $create = Business::create([
            'code' => $this->code,
            'name' => $this->name,
            'contact_number' => $this->contact_number,
            'address' => $this->address,
        ]);

        $create->departments()->sync($this->selectedDepartmentRows);

        $create->users()->sync(Auth::user()->id);

        if($create){
            $this->resetInputFields();
            if($saveAndCreateNew) {
                return redirect(request()->header('Referer'))->with('success', $this->name.' has been save successfully!');
            } else {
                $this->dispatch('hide-add-modal');

                return redirect(request()->header('Referer'))->with('success', $this->name.' has been save successfully!');
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
        $this->code = $data->code;
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
            'code' => 'required',
            'name' => 'required',
        ]);

        $data = Business::find($this->edit_id);
        $data->update([
            'code' => $this->code,
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

    public function addAllowance($id)
    {
        $this->allowances = [];
        $this->dispatch('show-allowance-modal');
        $this->businesId = $id;
        $allowances = BusinessCompensation::with('compensation')->where('business_id',$id)->get();

        if(count($allowances) > 0) {
            $countAllowance = 0;
            foreach ($allowances as $allowance) {
                if($allowance->compensation->type == 'allowance') {
                    $this->allowances[] = ['id' => $allowance->id, 'name' => $allowance->compensation_item_id, 'label' => $allowance->label, 'amount' => $allowance->amount];
                    $countAllowance++;
                }
            }

            if($countAllowance == 0) {
                $this->addAllowanceRow();
            }
        } else {
            $this->addAllowanceRow();
        }

    }

    public function addDeduction($id)
    {
        $this->deductions = [];
        $this->dispatch('show-deduction-modal');
        $this->businesId = $id;
        $deductions = BusinessCompensation::with('compensation')->where('business_id',$id)->get();

        if(count($deductions) > 0) {
            $countDeduction = 0;
            foreach ($deductions as $deduction) {
                if($deduction->compensation->type == 'deduction') {
                    $this->deductions[] = ['id' => $deduction->id, 'name' => $deduction->compensation_item_id, 'label' => $deduction->label, 'amount' => $deduction->amount];
                    $countDeduction++;
                }
            }

            if($countDeduction == 0) {
                $this->addDeductionRow();
            }
        } else {
            $this->addDeductionRow();
        }
    }

    public function addAllowanceRow()
    {
        $this->allowances[] = ['id' => null, 'name' => null, 'label' => null, 'amount' => null];
    }

    public function removeAllowanceRow($index)
    {
        if(is_null($this->allowances[$index]['id'])) {
            unset($this->allowances[$index]);
            $this->allowances = array_values($this->allowances); // Reset array keys
        } else {
            $this->allowanceIndex = $index;
            $this->approveRemoveAllowance = $this->allowances[$index]['id'];
            $this->confirm('Are you sure you want to delete this?', [
                'confirmButtonText' => 'Yes Delete it!',
                'onConfirmed' => 'removeAllowanceApproved',
            ]);
        }
    }

    public function addDeductionRow()
    {
        $this->deductions[] = ['id' => null, 'name' => null, 'label' => null, 'amount' => null];
    }

    public function removeDeductionRow($index)
    {
        if(is_null($this->deductions[$index]['id'])) {
            unset($this->deductions[$index]);
            $this->deductions = array_values($this->deductions); // Reset array keys
        } else {
            $this->approveRemoveDeduction = $this->deductions[$index]['id'];
            $this->deductionIndex = $index;
            $this->confirm('Are you sure you want to delete this?', [
                'confirmButtonText' => 'Yes Delete it!',
                'onConfirmed' => 'removeDeductionApproved',
            ]);
        }
    }

    public function saveAllowance()
    {
        if(count($this->allowances) > 0) {
            foreach ($this->allowances as $allowance) {
                if(!is_null($allowance['id'])) {
                    BusinessCompensation::find($allowance['id'])->update([
                        'compensation_item_id' => $allowance['name'],
                        'label' => $allowance['label'],
                        'amount' => $allowance['amount'],
                    ]);
                } else {
                    BusinessCompensation::create([
                        'compensation_item_id' => $allowance['name'],
                        'label' => $allowance['label'],
                        'amount' => $allowance['amount'],
                        'business_id' => $this->businesId
                    ]);
                }
            }
        }

        $this->alert('success', 'Allowance has been removed!');
        $this->dispatch('hide-allowance-modal');
    }

    public function saveDeduction()
    {
        if(count($this->deductions) > 0) {
            foreach ($this->deductions as $deduction) {
                if(!is_null($deduction['id'])) {
                    BusinessCompensation::find($deduction['id'])->update([
                        'compensation_item_id' => $deduction['name'],
                        'label' => $deduction['label'],
                        'amount' => $deduction['amount'],
                    ]);
                } else {
                    BusinessCompensation::create([
                        'compensation_item_id' => $deduction['name'],
                        'label' => $deduction['label'],
                        'amount' => $deduction['amount'],
                        'business_id' => $this->businesId
                    ]);
                }
            }
        }

        $this->alert('success', 'Allowance has been removed!');
        $this->dispatch('hide-deduction-modal');
    }

    public function removeAllowanceApproved()
    {
        $delete = BusinessCompensation::find($this->approveRemoveAllowance);
        $delete->delete();
        if($delete){
            $this->alert('success', 'Allowance has been removed!');

            unset($this->allowances[$this->allowanceIndex]);
            $this->allowances = array_values($this->allowances); // Reset array keys
        }
    }

    public function removeDeductionApproved()
    {
        $delete = BusinessCompensation::find($this->approveRemoveDeduction);
        $delete->delete();
        if($delete){
            $this->alert('success', 'Deduction has been removed!');

            unset($this->deductions[$this->deductionIndex]);
            $this->deductions = array_values($this->deductions); // Reset array keys
        }
    }
}
