<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ActiveStatus extends Component
{
    use LivewireAlert;

    public Model $model;

    public $field;

    public $is_active;

    public function mount()
    {
        $this->is_active = (bool) $this->model->getAttribute($this->field);
    }

    public function updating($field, $value)
    {
        $this->model->setAttribute($this->field, $value)->save();

        $this->alert('success', 'Status has been save successfully!');

    }

    public function render()
    {
        return view('livewire.active-status');
    }
}
