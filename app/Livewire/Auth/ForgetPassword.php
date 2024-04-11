<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.guest')]
class ForgetPassword extends Component
{
    #[Title('Forgot Password')]
    public function render()
    {
        return view('livewire.auth.forget-password');
    }
}
