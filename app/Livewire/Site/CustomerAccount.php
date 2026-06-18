<?php

namespace App\Livewire\Site;

use Livewire\Component;

class CustomerAccount extends Component
{
    public function logout(): void
    {
        auth('customer')->logout();

        request()->session()->regenerateToken();

        $this->redirectRoute('site.home');
    }

    public function render()
    {
        return view('livewire.site.customer-account', [
            'customer' => auth('customer')->user(),
        ]);
    }
}