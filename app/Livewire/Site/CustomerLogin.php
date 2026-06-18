<?php

namespace App\Livewire\Site;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CustomerLogin extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->resetErrorBag();

        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => app()->getLocale() === 'ar'
                ? 'اكتب البريد الإلكتروني'
                : 'Email is required',

            'email.email' => app()->getLocale() === 'ar'
                ? 'صيغة البريد الإلكتروني غير صحيحة'
                : 'Invalid email address',

            'password.required' => app()->getLocale() === 'ar'
                ? 'اكتب كلمة المرور'
                : 'Password is required',
        ]);

        $email = mb_strtolower(trim($this->email));

        $customer = Customer::query()
            ->where('email', $email)
            ->first();

        if (! $customer) {
            $this->addError(
                'email',
                app()->getLocale() === 'ar'
                    ? 'هذا البريد الإلكتروني غير مسجل'
                    : 'This email is not registered'
            );

            return;
        }

        if (! $customer->is_active) {
            $this->addError(
                'login',
                app()->getLocale() === 'ar'
                    ? 'هذا الحساب غير مفعل حاليًا'
                    : 'This account is currently inactive'
            );

            return;
        }

        if (! Hash::check($this->password, $customer->password)) {
            $this->addError(
                'password',
                app()->getLocale() === 'ar'
                    ? 'كلمة المرور غير صحيحة'
                    : 'Incorrect password'
            );

            return;
        }

        auth('customer')->login($customer, $this->remember);

        request()->session()->regenerate();

        $this->redirectRoute('site.customer.account');
    }

    public function render()
    {
        return view('livewire.site.customer-login');
    }
}