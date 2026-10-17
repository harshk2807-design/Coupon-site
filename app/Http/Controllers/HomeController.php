<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Store;

class HomeController extends Controller
{
    /** Homepage: featured stores, latest coupons, categories. */
    public function index()
    {
        $stores = Store::withCount('activeCoupons')
            ->orderByDesc('is_featured')
            ->orderBy('name')
            ->limit(14)
            ->get();

        $coupons = Coupon::active()
            ->with('store')
            ->latest()
            ->limit(12)
            ->get();

        $categories = Category::main()->withCount('children')->get();

        return view('home', compact('stores', 'coupons', 'categories'));
    }
}
