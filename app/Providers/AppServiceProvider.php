<?php

namespace App\Providers;

use App\Models\FooterLink;
use App\Models\FooterSetting;
use App\Models\NavigationLink;
use App\Models\PaymentMethodDisplay;
use App\Models\SocialLink;
use App\Models\StoreSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('site.*', function ($view) {
            $storeSettings = StoreSetting::query()->first();

            if (! $storeSettings) {
                $storeSettings = (object) [
                    'store_name' => config('app.name'),
                    'logo' => null,
                    'favicon' => null,
                    'email' => null,
                    'phone' => null,
                    'whatsapp' => null,
                    'address' => null,
                    'currency_code' => 'EGP',
                    'currency_symbol' => 'EGP',

                    'announcement_bar_enabled' => false,
                    'announcement_bar_text' => null,
                    'announcement_bar_url' => null,
                    'announcement_bar_open_in_new_tab' => false,
                    'announcement_bar_bg_color' => '#111827',
                    'announcement_bar_text_color' => '#ffffff',
                    'announcement_bar_speed' => 25,
                ];
            }

            $headerLinks = NavigationLink::query()
                ->where('is_active', true)
                ->where('location', 'header')
                ->orderBy('sort_order')
                ->get();

            $mobileLinks = NavigationLink::query()
                ->where('is_active', true)
                ->where('location', 'mobile')
                ->orderBy('sort_order')
                ->get();

            if ($mobileLinks->isEmpty()) {
                $mobileLinks = $headerLinks;
            }

            $navigation = [
                'header_links' => $headerLinks,
                'mobile_links' => $mobileLinks,
            ];

            $footerSettings = FooterSetting::query()
                ->where('is_active', true)
                ->first();

            $footerLinks = FooterLink::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $socialLinks = SocialLink::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $paymentMethods = PaymentMethodDisplay::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $view->with([
                'storeSettings' => $storeSettings,
                'navigation' => $navigation,
                'footerSettings' => $footerSettings,
                'footerLinks' => $footerLinks,
                'socialLinks' => $socialLinks,
                'paymentMethods' => $paymentMethods,
            ]);
        });
    }
}