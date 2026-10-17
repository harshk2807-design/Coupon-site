<?php

namespace App\Models;

use App\Support\Site;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id', 'sort_order'];

    /** Clear the cached header menu whenever a category is added, changed or deleted. */
    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('menu_categories'));
        static::deleted(fn () => Cache::forget('menu_categories'));
    }

    /** The main category this subcategory belongs to (null for main categories). */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /** Subcategories of a main category. */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order')->orderBy('name');
    }

    /** Stores in this category. */
    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class);
    }

    /** Category::main() -> only top-level categories */
    public function scopeMain(Builder $query): void
    {
        $query->whereNull('parent_id')->orderBy('sort_order')->orderBy('name');
    }

    public function url(): string
    {
        return Site::categoryUrl($this->slug);
    }
}
