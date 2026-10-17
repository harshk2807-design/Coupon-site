<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /** Search stores by name: yourdomain.com/search?q=temu */
    public function __invoke(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $stores = $q === ''
            ? collect()
            : Store::where('name', 'like', '%' . $q . '%')
                ->withCount('activeCoupons')
                ->orderBy('name')
                ->limit(48)
                ->get();

        // exactly one match -> go straight to that store
        if ($stores->count() === 1) {
            return redirect($stores->first()->pageUrl());
        }

        return view('search', compact('q', 'stores'));
    }
}
