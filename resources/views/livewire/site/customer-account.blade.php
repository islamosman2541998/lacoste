<section class="customer-account-page">
    <div class="site-container">
        <div class="customer-account-head">
            <div>
                <h1>{{ app()->getLocale() === 'ar' ? 'حسابي' : 'My Account' }}</h1>

                <p>
                    {{ app()->getLocale() === 'ar'
                        ? 'مرحبًا ' . $customer->name . '، يمكنك إدارة حسابك وطلباتك من هنا.'
                        : 'Hello ' . $customer->name . ', manage your account and orders here.' }}
                </p>
            </div>

            <button type="button" wire:click="logout">
                {{ app()->getLocale() === 'ar' ? 'تسجيل الخروج' : 'Logout' }}
            </button>
        </div>

        <div class="customer-account-grid">
            <a href="{{ route('site.customer.orders') }}" class="customer-account-card">
                <span>
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 3h10a2 2 0 0 1 2 2v14l-4-2-3 2-3-2-4 2V5a2 2 0 0 1 2-2Z" />
                    </svg>
                </span>

                <h2>{{ app()->getLocale() === 'ar' ? 'طلباتي' : 'My Orders' }}</h2>

                <p>
                    {{ app()->getLocale() === 'ar'
                        ? 'تابع كل طلباتك وحالة الشحن والدفع.'
                        : 'Track your orders, shipping, and payment status.' }}
                </p>
            </a>

            <a href="{{ route('site.orders.track') }}" class="customer-account-card">
                <span>
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h13v6M9 17H5a2 2 0 0 1-2-2V7h6v10Zm13 0a2 2 0 0 1-2 2h-1a2 2 0 0 1-4 0H9a2 2 0 0 1-4 0H4" />
                    </svg>
                </span>

                <h2>{{ app()->getLocale() === 'ar' ? 'تتبع طلب' : 'Track Order' }}</h2>

                <p>
                    {{ app()->getLocale() === 'ar'
                        ? 'تابع طلب معين باستخدام رقم الطلب.'
                        : 'Track a specific order using its order number.' }}
                </p>
            </a>

            <a href="{{ route('site.shop') }}" class="customer-account-card">
                <span>
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5M4.5 9.75h15l-.75 10.5h-13.5L4.5 9.75Z" />
                    </svg>
                </span>

                <h2>{{ app()->getLocale() === 'ar' ? 'استكمال التسوق' : 'Continue Shopping' }}</h2>

                <p>
                    {{ app()->getLocale() === 'ar'
                        ? 'اكتشف المنتجات والعروض المتاحة.'
                        : 'Explore products and current offers.' }}
                </p>
            </a>
        </div>
    </div>
</section>