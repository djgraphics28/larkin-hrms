<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use App\Models\Employee;
use App\Models\BusinessUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SearchEmployee extends Component
{
    public $businessId;
    public $query;
    public $search_results;
    public $how_many;

    public function mount() {
        $businessUser = BusinessUser::where('user_id', Auth::user()->id)
                                     ->where('is_active', true)
                                     ->first();
        if (!$businessUser) {
            return redirect()->back();
        }

        $this->businessId = $businessUser->business_id;

        $this->query = '';
        $this->how_many = 5;
        $this->search_results = Collection::empty();
    }

    public function render() {
        return view('livewire.shared.search-employee');
    }

    public function updatedQuery() {
        $this->search_results = Employee::where('business_id', $this->businessId)->search(trim($this->query))
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
        // $this->selectEmployee(null);
    }

    public function selectEmployee($employee_id) {
        $this->dispatch('selectedEmployee',$employee_id);
    }
}
