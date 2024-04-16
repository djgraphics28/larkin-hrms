<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use App\Models\Employee;
use Illuminate\Support\Collection;

class SearchEmployee extends Component
{
    public $query;
    public $search_results;
    public $how_many;

    public function mount() {
        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();
    }

    public function render() {
        return view('livewire.shared.search-employee');
    }

    public function updatedQuery() {
        $this->search_results = Employee::where('first_name', 'like', '%' . $this->query . '%')
            ->orWhere('last_name', 'like', '%' . $this->query . '%')
            ->orWhere('employee_number', 'like', '%' . $this->query . '%')
            ->orWhere('email', 'like', '%' . $this->query . '%')
            ->take($this->how_many)->get();
    }

    public function loadMore() {
        $this->how_many += 5;
        $this->updatedQuery();
    }

    public function resetQuery() {
        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();
        $this->selectEmployee(null);
    }

    public function selectEmployee($employee_id) {
        $this->dispatch('selectedEmployee',$employee_id);
    }
}
