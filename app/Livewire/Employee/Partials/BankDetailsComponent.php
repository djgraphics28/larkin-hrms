<?php

namespace App\Livewire\Employee\Partials;

use Livewire\Component;
use App\Models\Employee;
use App\Models\BankDetail;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BankDetailsComponent extends Component
{
    use LivewireAlert;

    protected $listeners = ['remove', 'removeBank'];
    public $approveConfirmed;
    public $id;
    public $records;
    public $deleteIndex;

    public function mount($id)
    {
        $this->id = $id;
        $this->records = BankDetail::where('employee_id', $this->id)->get()->toArray();

        if (empty($this->records)) {
            $this->addBank();
        }
    }

    public function render()
    {
        return view('livewire.employee.partials.bank-details-component');
    }

    public function submit()
    {
        $this->validate([
            'records.*.account_name' => 'required',
            'records.*.account_number' => 'required',
            'records.*.bank_name' => 'required',
            'records.*.bsb_code' => 'required',
        ]);

        try {
            \DB::beginTransaction();

            foreach ($this->records as $record) {
                BankDetail::updateOrCreate(
                    ['employee_id' => $this->id, 'bank_name' => $record['bank_name']],
                    [
                        'employee_id' => $this->id,
                        'account_name' => $record['account_name'],
                        'account_number' => $record['account_number'],
                        'bank_name' => $record['bank_name'],
                        'bsb_code' => $record['bsb_code'],
                        'is_active' => $record['is_active'] ?? false,
                    ]
                );
            }

            $emp = Employee::find($this->id);
            $emp->update([
                'default_pay_method' => 'bank'
            ]);

            \DB::commit();

            $this->alert('success', 'Bank details saved successfully');
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->alert('error', 'Failed to save bank details. Please try again.');
        }
    }

    public function addBank()
    {
        $this->records[] = [
            'account_name' => '',
            'account_number' => '',
            'bank_name' => '',
            'bsb_code' => '',
            'is_active' => false,
        ];
    }

    public function alertConfirm($index)
    {
        $this->deleteIndex = $index;
        if (!isset($this->records[$index]['id'])) {
            $this->removeBank($index);
            return;
        }

        $this->approveConfirmed = $this->records[$index]['id'];


        $this->confirm('Are you sure you want to delete this?', [
            'confirmButtonText' => 'Yes Delete it!',
            'onConfirmed' => 'remove',
        ]);
    }

    public function removeBank($index)
    {
        unset($this->records[$index]);
        $this->records = array_values($this->records); // Re-index the array
    }

    public function remove()
    {
        BankDetail::destroy($this->approveConfirmed);
        $this->removeBank($this->deleteIndex); // Remove it from the Livewire component records
        $this->alert('success', 'Bank details deleted successfully');
    }
}
