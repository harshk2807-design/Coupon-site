{{--
  One coupon. "Show Code" works like worthepenny (see public/js/site.js):
    1. a NEW tab opens the store page with ?offer=ID -> shows the code popup
    2. the CURRENT tab goes to the store / affiliate link
--}}
@php($store = $coupon->store)
<div class="coupon {{ !empty($best) ? 'coupon--best' : '' }}" data-type="{{ $coupon->isCode() ? 'code' : 'deal' }}">
    @if (!empty($best))<div class="coupon__ribbon">🔥 TODAY'S BEST OFFER</div>@endif
    <div class="coupon__discount">{{ $coupon->discount ?: 'DEAL' }}</div>
    <div class="coupon__body">
        <div class="coupon__meta">
            <span class="badge">{{ $coupon->isCode() ? 'CODE' : 'DEAL' }}</span>
            @if ($coupon->verified)<span class="verified">✔ Verified</span>@endif
            @if (!empty($showStore))<a class="coupon__store" href="{{ $store->pageUrl() }}">{{ $store->name }}</a>@endif
        </div>
        <h3>{{ $coupon->title }}</h3>
        @if ($coupon->description)<p>{{ $coupon->description }}</p>@endif
        @if ($coupon->expires_at)<p class="small muted">Expires {{ $coupon->expires_at->format('d M Y') }}</p>@endif
    </div>
    <div class="coupon__action">
        @if ($coupon->isCode())
            <button type="button" class="btn btn--reveal"
                    data-reveal
                    data-id="{{ $coupon->id }}"
                    data-code="{{ $coupon->code }}"
                    data-title="{{ $coupon->title }}"
                    data-go="{{ $coupon->goUrl() }}"
                    data-page="{{ $store->pageUrl() }}">
                Show Code <span class="peek">{{ Str::substr($coupon->code, -3) }}</span>
            </button>
        @else
            <a class="btn" href="{{ $coupon->goUrl() }}" target="_blank" rel="nofollow sponsored noopener">Get Deal</a>
        @endif
    </div>
</div>
