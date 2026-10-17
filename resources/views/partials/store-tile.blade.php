{{-- One store card. The link goes to the store's subdomain (or /store/slug) --}}
<a class="store-tile" href="{{ $store->pageUrl() }}">
    @include('partials.store-logo', ['store' => $store])
    <strong>{{ $store->name }}</strong>
    <small>{{ $store->active_coupons_count ?? 0 }} {{ Str::plural('Code', $store->active_coupons_count ?? 0) }}</small>
</a>
