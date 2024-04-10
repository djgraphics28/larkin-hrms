<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Business;

class BranchSelection extends Component
{
    public $branches = [];
    public $branch = 1;

    public function render()
    {
        return view('livewire.branch-selection');
    }

    public function mount()
    {
        $this->branches = Business::where('is_active', 1)->get();
    }
}
