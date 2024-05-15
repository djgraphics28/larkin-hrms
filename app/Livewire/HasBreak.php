<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class HasBreak extends Component
{
    use LivewireAlert;

    public Model $model;

    public $field;

    public $is_break;

    public function mount()
    {
        $this->is_break = (bool) $this->model->getAttribute($this->field);
    }

    public function updating($field, $value)
    {
        $this->model->setAttribute($this->field, $value)->save();

        if($value) {
            $this->alert('success', 'It seems like this attendance has been scheduled to include a break!');
        } else {
            $this->alert('success', 'It seems like this attendance has been scheduled without a break!');
        }
    }


    public function render()
    {
        return view('livewire.has-break');
    }
}
