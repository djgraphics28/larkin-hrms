<?php

namespace App\Livewire\Employee\Partials;

use Livewire\Component;
use App\Models\Employee;
use Livewire\WithFileUploads;
use Spatie\MediaLibrary\InteractsWithMedia;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class DocumentsComponent extends Component
{
    use InteractsWithMedia, LivewireAlert, WithFileUploads;

    public $id;
    public $files = [];

    public function render()
    {
        return view('livewire.employee.partials.documents-component');
    }

    public function upload()
    {
        foreach ($this->files as $file) {
            // Assuming your model uses the HasMediaTrait
            $model = Employee::find($this->id); // Adjust the logic to get your actual model instance
            $model->addMedia($file->getRealPath())->toMediaCollection('default', 's3');
        }

        session()->flash('message', 'Files successfully uploaded.');
    }
}
