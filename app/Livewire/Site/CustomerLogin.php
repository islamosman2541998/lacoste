<?php

namespace App\Livewire\Site;

use Livewire\Component;

class CustomerLogin extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => app()->getLocale() === 'ar' ? 'البريد الإلكتروني مطلوب' : 'Email is required',
            'email.email' => app()->getLocale() === 'ar' ? 'صيغة البريد الإلكتروني غير صحيحة' : 'Invalid email address',
            'password.required' => app()->getLocale() === 'ar' ? 'كلمة المرور مطلوبة' : 'Password is required',
        ]);

        $credentials = [
            'email' => mb_strtolower(trim($this->email)),
            'password' => $this->password,
            'is_active' => true,
        ];

        if (! auth('customer')->attempt($credentials, $this->remember)) {
            $this->addError(
                'email',
                app()->getLocale() === 'ar'
                    ? 'بيانات الدخول غير صحيحة'
                    : 'Invalid login details'
            );

            return;
        }

        request()->session()->regenerate();

        $this->redirectRoute('site.customer.account');
    }

    public function render()
    {
        return view('livewire.site.customer-login');
    }
}