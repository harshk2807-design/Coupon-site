<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Store;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/** Numbers shown on the admin dashboard. */
class SiteStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Stores', Store::count()),
            Stat::make('Active coupons', Coupon::active()->count())
                ->description(Coupon::count() - Coupon::active()->count() . ' expired (hidden)'),
            Stat::make('Categories', Category::whereNull('parent_id')->count() . ' main')
                ->description(Category::whereNotNull('parent_id')->count() . ' subcategories'),
        ];
    }
}
