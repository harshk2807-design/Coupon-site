<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Support\Site;

class StoreController extends Controller
{
    /**
     * One store page. $store is the slug, taken from:
     *   temu.yourdomain.com/coupon   (subdomain mode)   or
     *   yourdomain.com/store/temu    (path mode)
     */
    public function show(string $store)
    {
        $store = Store::with(['categories.parent'])->where('slug', strtolower($store))->first();

        // unknown store -> homepage
        if (! $store) {
            return redirect(Site::url('/'));
        }

        $coupons = $store->activeCoupons()
            ->orderByDesc('verified')
            ->latest()
            ->get();

        // "Popular brands": other stores in the same categories
        $related = Store::whereHas('categories', fn ($q) => $q->whereIn('categories.id', $store->categories->pluck('id')))
            ->whereKeyNot($store->id)
            ->withCount('activeCoupons')
            ->orderByDesc('is_featured')
            ->limit(6)
            ->get();

        return view('store', compact('store', 'coupons', 'related'));
    }

    /** All stores A-Z: yourdomain.com/stores */
    public function index()
    {
        $groups = Store::orderBy('name')->get(['id', 'name', 'slug'])->groupBy(fn (Store $s) => $s->letter());

        return view('stores', compact('groups'));
    }
}
