<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Store;

class CategoryController extends Controller
{
    /** All categories with their subcategories: yourdomain.com/categories */
    public function index()
    {
        $categories = Category::main()
            ->with(['children' => fn ($q) => $q->withCount('stores')])
            ->get();

        return view('categories', compact('categories'));
    }

    /**
     * One category page, like worthepenny:
     *   breadcrumb · sidebar with sibling subcategories · stores grouped A-Z
     * A main category shows the stores of all its subcategories.
     */
    public function show(string $slug)
    {
        $category = Category::with('parent')->where('slug', $slug)->firstOrFail();

        // the main category whose subcategories are listed in the sidebar
        $main = $category->parent ?? $category;
        $main->load('children');

        $ids = $category->parent_id
            ? [$category->id]
            : $category->children()->pluck('id')->push($category->id)->all();

        $groups = Store::whereHas('categories', fn ($q) => $q->whereIn('categories.id', $ids))
            ->orderBy('name')
            ->get(['stores.id', 'name', 'slug'])
            ->groupBy(fn (Store $s) => $s->letter());

        return view('category', compact('category', 'main', 'groups'));
    }
}
