@extends('layouts.app')
@use('App\Support\Site')

@section('canonical', Site::url('/'))

@section('content')
<main class="container">
    <section class="hero">
        <h1>Hand-Tested Coupons &amp; Promo Codes</h1>
        <p>Save money at your favourite stores with verified codes and deals.</p>
    </section>

    <h2 class="section-title">Explore Codes for Your Favorite Brands</h2>
    <div class="store-grid">
        @forelse ($stores as $store)
            @include('partials.store-tile', ['store' => $store])
        @empty
            <p class="muted">No stores yet. Add some in the admin panel.</p>
        @endforelse
    </div>
    <p class="center"><a class="link" href="{{ Site::url('stores') }}">See all stores ›</a></p>

    <h2 class="section-title">Today's Hottest Picks</h2>
    <div class="coupon-list">
        @foreach ($coupons as $coupon)
            @include('partials.coupon-card', ['coupon' => $coupon, 'showStore' => true])
        @endforeach
    </div>

    <h2 class="section-title">Browse by Category</h2>
    <div class="chips chips--center">
        @foreach ($categories as $cat)
            <a class="chip" href="{{ $cat->url() }}">{{ $cat->name }}</a>
        @endforeach
    </div>
</main>
@endsection
