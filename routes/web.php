<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InvoicePrintController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PageController;



Route::get('/', [HomeController::class, 'index'])->name('site.home');
Route::get('/pages/{slug}', [PageController::class, 'show'])
    ->name('site.pages.show');
Route::middleware(['web', 'auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/invoices/{invoice}/print', InvoicePrintController::class)
            ->name('invoices.print');
    });
Route::get('/switch-language/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'ar'])) {
        abort(400);
    }

    session(['locale' => $locale]);

    return back();
})->name('switch.language');