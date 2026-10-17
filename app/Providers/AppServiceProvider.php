<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Categories for the header "Categories" menu, on every page.
        // Cached for 10 minutes; cleared automatically when a category changes (see Category model).
        View::composer('layouts.app', function ($view) {
            $view->with('menuCategories', Cache::remember('menu_categories', 600, fn () => Category::main()
                ->with('children')
                ->get()
                // plain arrays (not models) so they can be stored in the cache safely;
                // links are built in the view, so changing APP_DOMAIN takes effect immediately
                ->map(fn (Category $main) => [
                    'name' => $main->name,
                    'slug' => $main->slug,
                    'children' => $main->children
                        ->map(fn (Category $sub) => ['name' => $sub->name, 'slug' => $sub->slug])
                        ->all(),
                ])
                ->all()));
        });
    }
}
