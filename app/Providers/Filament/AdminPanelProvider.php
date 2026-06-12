<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\DashboardStatsOverview;
use App\Filament\Widgets\LatestOrdersTable;
use App\Filament\Widgets\OrdersSalesChart;
use App\Filament\Widgets\OrdersStatusChart;
use App\Filament\Widgets\TopSellingProductsChart;
use App\Http\Middleware\SetLocale;
use App\Models\StoreSetting;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->userMenuItems([
                MenuItem::make()
                    ->label('العربية')
                    ->icon('heroicon-o-language')
                    ->url(fn() => route('switch.language', ['locale' => 'ar'])),

                MenuItem::make()
                    ->label('English')
                    ->icon('heroicon-o-language')
                    ->url(fn() => route('switch.language', ['locale' => 'en'])),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->font('Cairo')
            ->colors([
                'primary' => Color::Amber,
            ])
            
            ->brandName(fn() => StoreSetting::current()->store_name_en ?: config('app.name'))
            ->brandLogo(function () {
                $logo = StoreSetting::current()->dashboard_logo;

                return $logo ? asset('storage/' . $logo) : null;
            })
            ->favicon(function () {
                $favicon = StoreSetting::current()->dashboard_favicon;

                return $favicon ? asset('storage/' . $favicon) : null;
            })
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn() => '
                    <script>
                        document.documentElement.setAttribute("lang", "' . app()->getLocale() . '");
                        document.documentElement.setAttribute("dir", "' . (app()->getLocale() === 'ar' ? 'rtl' : 'ltr') . '");
                    </script>
                ' . Blade::render("@include('filament.dashboard-appearance')")
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                DashboardStatsOverview::class,
                OrdersSalesChart::class,
                OrdersStatusChart::class,
                TopSellingProductsChart::class,
                LatestOrdersTable::class,

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                SetLocale::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}