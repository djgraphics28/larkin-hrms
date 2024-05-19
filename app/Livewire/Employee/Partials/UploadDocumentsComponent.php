<?php

namespace App\Livewire\Employee\Partials;

use Livewire\Component;
use App\Models\Employee;
use Illuminate\Http\File;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class UploadDocumentsComponent extends Component
{
    use WithFileUploads, LivewireAlert;

    public $id;
    public $files = [];
    public $documents = [];

    public function mount()
    {
        $employee = Employee::find($this->id);
        $documents = $employee->getMedia('documents');
    }

    public function save()
    {
        $this->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240', // 10MB Max
        ]);

        // foreach ($this->files as $file) {
        //     $employee = Employee::find($this->id);
        //     $employee->addMedia($file)->toMediaCollection('documents', 's3');
        // }

        foreach ($this->files as $file) {
            $employee = Employee::find($this->id);
            // Generate the path for the employee's documents folder
            $path = "employees/{$employee->id}/documents";

            // Add the file to the specified path in the S3 bucket
            $employee->addMedia($file)
                     ->usingFileName($file->getClientOriginalName())
                     ->withCustomProperties(['folder' => $path])
                     ->toMediaCollection('documents', 's3');
        }

        $this->alert('success', 'Employee Documents uploaded successfully');

    }

    public function render()
    {
        return view('livewire.employee.partials.upload-documents-component');
    }
}
