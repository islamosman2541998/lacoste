<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\FooterService;
use App\Services\NavigationService;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::query()
            ->active()
            ->with('activeImages')
            ->where(function ($query) use ($slug) {
                $query->where('slug_ar', $slug)
                    ->orWhere('slug_en', $slug);
            })
            ->firstOrFail();

        $footer = app(FooterService::class)->footerData();
        $navigation = app(NavigationService::class)->navigationData();

        return view('site.pages.page-show', compact('page', 'footer', 'navigation'));
    }
}