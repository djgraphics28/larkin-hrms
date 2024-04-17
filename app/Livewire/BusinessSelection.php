<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Business;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BusinessSelection extends Component
{
    use LivewireAlert;

    public $businesses = [];
    public $businessSelect;

    public function render()
    {
        return view('livewire.business-selection');
    }

    public function mount()
    {
        $this->businesses = DB::table('business_user')
            ->join('businesses', 'business_user.business_id', '=', 'businesses.id')
            ->select(['businesses.id', 'businesses.name', 'business_user.is_active'])
            ->where('business_user.user_id', Auth::user()->id)
            ->get();

        $selected = DB::table('business_user')
            ->join('businesses', 'business_user.business_id', '=', 'businesses.id')
            ->select('businesses.id')
            ->where('business_user.user_id', Auth::user()->id)
            ->where('business_user.is_active', true)
            ->first();

        $this->businessSelect = $selected->id;
    }


    public function updatedBusinessSelect()
    {
        // Deactivate all businesses of the authenticated user
        DB::table('business_user')->where('user_id', Auth::user()->id)->update(['is_active' => false]);

        // Activate the selected business
        $data = DB::table('business_user')->where('user_id', Auth::user()->id)->where('business_id' , $this->businessSelect)->update(['is_active' => true]);
        $business = Business::find($this->businessSelect);

        sleep(1);
        return redirect(request()->header('Referer'))->with('success', $business->name.' has been selected!');;
    }
}
