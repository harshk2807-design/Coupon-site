<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StoreController;
use App\Support\Site;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ROUTES  (which URL runs which code)
|--------------------------------------------------------------------------
| Every store and every category uses ONE route each ("dynamic routing").
| New stores/categories added in the admin panel work automatically.
| The admin panel itself lives at /admin (Filament adds those routes).
*/

if (config('coupons.use_subdomains')) {
    /*
    | SUBDOMAIN MODE:  https://temu.yourdomain.com/coupon
    | "{store}" is read from the address, e.g. "temu".
    | Needs a wildcard DNS record *.yourdomain.com pointing to this server.
    */
    // www.yourdomain.com/... -> yourdomain.com/... (www is not a store)
    Route::domain('www.' . config('coupons.domain'))->group(function () {
        Route::any('/{any?}', fn (?string $any = null) => redirect(Site::url($any ?? '/'), 301))
            ->where('any', '.*');
    });

    Route::domain('{store}.' . config('coupons.domain'))
        ->where(['store' => '[a-z0-9-]+'])
        ->group(function () {
            Route::get('/', fn (string $store) => redirect('/coupon', 301));
            Route::get('/coupon', [StoreController::class, 'show'])->name('store.subdomain');
            // anything else opened on a store subdomain -> same page on the main site
            Route::any('/{any}', fn (string $store, string $any) => redirect(Site::url($any), 301))
                ->where('any', '.*');
        });

    // old style links /store/temu -> temu.yourdomain.com/coupon
    Route::get('/store/{store}', fn (string $store) => redirect(Site::storeUrl($store), 301));
} else {
    /*
    | PATH MODE (temporary domains):  https://yourdomain.com/store/temu
    */
    Route::get('/store/{store}', [StoreController::class, 'show'])->name('store.show');
}

// Main site pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');           // all stores A-Z
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index'); // all categories
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/search', SearchController::class)->name('search');
