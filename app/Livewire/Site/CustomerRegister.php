<?php

namespace App\Livewire\Site;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CustomerRegister extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $accepts_marketing = false;

    public function register(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('customers', 'email')],
            'phone' => ['required', 'string', 'max:50', Rule::unique('customers', 'phone')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => app()->getLocale() === 'ar' ? 'الاسم مطلوب' : 'Name is required',
            'email.required' => app()->getLocale() === 'ar' ? 'البريد الإلكتروني مطلوب' : 'Email is required',
            'email.email' => app()->getLocale() === 'ar' ? 'صيغة البريد الإلكتروني غير صحيحة' : 'Invalid email address',
            'email.unique' => app()->getLocale() === 'ar' ? 'هذا البريد مستخدم بالفعل' : 'This email is already used',
            'phone.required' => app()->getLocale() === 'ar' ? 'رقم الهاتف مطلوب' : 'Phone is required',
            'phone.unique' => app()->getLocale() === 'ar' ? 'رقم الهاتف مستخدم بالفعل' : 'This phone is already used',
            'password.required' => app()->getLocale() === 'ar' ? 'كلمة المرور مطلوبة' : 'Password is required',
            'password.min' => app()->getLocale() === 'ar' ? 'كلمة المرور يجب ألا تقل عن 8 أحرف' : 'Password must be at least 8 characters',
            'password.confirmed' => app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور غير مطابق' : 'Password confirmation does not match',
        ]);

        $customer = Customer::query()->create([
            'name' => trim($this->name),
            'email' => mb_strtolower(trim($this->email)),
            'phone' => trim($this->phone),
            'password' => Hash::make($this->password),
            'is_active' => true,
            'accepts_marketing' => $this->accepts_marketing,
        ]);

        auth('customer')->login($customer);

        request()->session()->regenerate();

        $this->redirectRoute('site.customer.account');
    }

    public function render()
    {
        return view('livewire.site.customer-register');
    }
}