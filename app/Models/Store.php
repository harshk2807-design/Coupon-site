<?php

namespace App\Models;

use App\Support\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Store extends Model
{
    protected $fillable = ['name', 'slug', 'logo', 'url', 'rating', 'button_text', 'about', 'is_featured'];

    protected $casts = ['rating' => 'float', 'is_featured' => 'boolean'];

    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    /** Only coupons that have not expired. */
    public function activeCoupons(): HasMany
    {
        return $this->coupons()->active();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /** Link to this store's page (subdomain or /store/slug, depending on settings). */
    public function pageUrl(): string
    {
        return Site::storeUrl($this->slug);
    }

    /** Logo can be an uploaded file (stored in storage/app/public) or a full https:// URL. */
    public function logoUrl(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        return Str::startsWith($this->logo, ['http://', 'https://'])
            ? $this->logo
            : Storage::disk('public')->url($this->logo);
    }

    /** First character for the A-Z list: "2K" -> "2", "amazon" -> "A". */
    public function letter(): string
    {
        return Str::upper(Str::substr($this->name, 0, 1));
    }
}
