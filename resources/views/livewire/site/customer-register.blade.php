
<section class="customer-auth-page">
    <div class="site-container">
        <div class="customer-auth-card">
            <div class="customer-auth-icon">
                <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3M3.75 19.5a6.75 6.75 0 0 1 13.5 0M12 10.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                </svg>
            </div>

            <h1>{{ app()->getLocale() === 'ar' ? 'إنشاء حساب' : 'Create Account' }}</h1>

            <p>
                {{ app()->getLocale() === 'ar'
                    ? 'أنشئ حسابك لتتمكن من متابعة طلباتك بسهولة.'
                    : 'Create your account to track your orders easily.' }}
            </p>

            <div class="customer-auth-form">
                <div>
                    <label>{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}</label>
                    <input type="text" wire:model.defer="name" placeholder="{{ app()->getLocale() === 'ar' ? 'الاسم بالكامل' : 'Full name' }}">
                    @error('name') <span>{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</label>
                    <input type="email" wire:model.defer="email" placeholder="email@example.com">
                    @error('email') <span>{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>{{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone Number' }}</label>
                    <input type="text" wire:model.defer="phone" placeholder="{{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone number' }}">
                    @error('phone') <span>{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>{{ app()->getLocale() === 'ar' ? 'كلمة المرور' : 'Password' }}</label>
                    <input type="password" wire:model.defer="password" placeholder="********">
                    @error('password') <span>{{ $message }}</span> @enderror
                </div>

                <div>
                    <label>{{ app()->getLocale() === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }}</label>
                    <input type="password" wire:model.defer="password_confirmation" placeholder="********">
                </div>

                <label class="customer-auth-check">
                    <input type="checkbox" wire:model.defer="accepts_marketing">
                    <span>{{ app()->getLocale() === 'ar' ? 'أوافق على استقبال العروض والتحديثات' : 'I agree to receive offers and updates' }}</span>
                </label>

                <button type="button" wire:click="register" wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        {{ app()->getLocale() === 'ar' ? 'إنشاء الحساب' : 'Create Account' }}
                    </span>

                    <span wire:loading>
                        {{ app()->getLocale() === 'ar' ? 'جاري إنشاء الحساب...' : 'Creating account...' }}
                    </span>
                </button>
            </div>

            <div class="customer-auth-switch">
                <span>{{ app()->getLocale() === 'ar' ? 'لديك حساب بالفعل؟' : 'Already have an account?' }}</span>
                <a href="{{ route('site.customer.login') }}">
                    {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}
                </a>
            </div>
        </div>
    </div>
</section>