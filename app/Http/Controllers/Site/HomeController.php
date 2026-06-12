<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use App\Services\FooterService;
use App\Services\HomepageSettingService;
use App\Services\NavigationService;

class HomeController extends Controller
{
    public function index()
    {
        $storeSettings = StoreSetting::current();

        $homepage = app(HomepageSettingService::class)->homepageData();
        $footer = app(FooterService::class)->footerData();
        $navigation = app(NavigationService::class)->navigationData();

        return view('site.pages.home', compact(
            'storeSettings',
            'homepage',
            'footer',
            'navigation'
        ));
    }
}