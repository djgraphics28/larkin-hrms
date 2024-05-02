<?php

namespace App\Livewire\Employee\Partials;

use App\Models\Asset;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AssetComponent extends Component
{
    use LivewireAlert;
    public $id;
    public $assets = [];
    public function render()
    {
        return view('livewire.employee.partials.asset-component');
    }

    public function mount()
    {
        $this->assets = Asset::where('employee_id', $this->id)->get();
    }
}
