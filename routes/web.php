<?php

use App\Http\Controllers\Admin\InvoicePrintController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\ShopController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index'])->name('site.home');
Route::get('/pages/{slug}', [PageController::class, 'show'])
    ->name('site.pages.show');
Route::middleware(['web', 'auth'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/invoices/{invoice}/print', InvoicePrintController::class)
            ->name('invoices.print');
    });

    Route::view('/shop', 'site.pages.shop.index')->name('site.shop');
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('site.products.show');
    Route::view('/cart', 'site.pages.cart.index')->name('site.cart');
    Route::view('/checkout', 'site.pages.checkout.index')->name('site.checkout');
Route::get('/switch-language/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'ar'])) {
        abort(400);
    }

    session(['locale' => $locale]);

    return back();
})->name('switch.language');