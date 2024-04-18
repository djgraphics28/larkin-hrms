<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Imports\EmployeeImport;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ImportEmployeeComponent extends Component
{
    use WithFileUploads, LivewireAlert;

    public $file;

    public function render()
    {
        return view('livewire.employee.import-employee-component');
    }

    public function submit()
    {
        // $this->validate([
        //     'file' => 'required|mimes:xlsx'
        // ]);

        try {
            // Store the uploaded file
            $path = $this->file->store('temp');

            // Import the data using Laravel Excel
            Excel::import(new EmployeeImport(), $path);

            $this->alert('success', 'Employee data imported successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to import Excel file: ' . $e->getMessage());
        }
    }
}
