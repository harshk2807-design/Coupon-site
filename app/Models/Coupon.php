<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    protected $fillable = [
        'store_id', 'title', 'description', 'type', 'code',
        'discount', 'url', 'verified', 'expires_at',
    ];

    protected $casts = ['verified' => 'boolean', 'expires_at' => 'date'];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /** Coupon::active() -> not expired (a coupon is still shown on its expiry day). */
    public function scopeActive(Builder $query): void
    {
        $query->where(fn (Builder $q) => $q
            ->whereNull('expires_at')
            ->orWhereDate('expires_at', '>=', today()));
    }

    /** true = "Show Code" button, false = "Get Deal" button */
    public function isCode(): bool
    {
        return $this->type === 'code' && filled($this->code);
    }

    /** Where the shopper is sent: the coupon's own link, or the store link. */
    public function goUrl(): ?string
    {
        return $this->url ?: $this->store?->url;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->lt(today());
    }
}
