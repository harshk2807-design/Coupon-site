@extends('layouts.app')

@section('title', $store->name . ' Coupons & Promo Codes ' . now()->format('M Y'))
@section('description', 'Save now with ' . $coupons->count() . ' active ' . $store->name . ' coupons & promo codes. Hand-tested and verified.')
@section('canonical', $store->pageUrl())

@section('content')
<div class="container store-layout">
    {{-- LEFT SIDEBAR --}}
    <aside class="sidebar">
        @include('partials.store-logo', ['store' => $store, 'size' => 96])
        @if ($store->rating > 0)
            <div class="stars" style="--rating: {{ $store->rating }}"></div>
            <div class="muted small">Rating: {{ number_format($store->rating, 1) }}</div>
        @endif
        @if ($store->url)
            <a class="btn btn--block" href="{{ $store->url }}" target="_blank" rel="nofollow sponsored noopener">
                {{ $store->button_text ?: 'Visit ' . $store->name }}
            </a>
        @endif

        @php($codeCount = $coupons->filter->isCode()->count())
        <div class="filters" data-filters>
            <h4>Coupon Type</h4>
            <label><input type="radio" name="f" value="all" checked> All ({{ $coupons->count() }})</label>
            <label><input type="radio" name="f" value="code"> Coupon Codes ({{ $codeCount }})</label>
            <label><input type="radio" name="f" value="deal"> Online Sales ({{ $coupons->count() - $codeCount }})</label>
        </div>

        @if ($store->categories->isNotEmpty())
            <div class="filters">
                <h4>Categories</h4>
                @foreach ($store->categories as $cat)
                    <a class="side-link" href="{{ $cat->url() }}">{{ $cat->name }}</a>
                @endforeach
            </div>
        @endif
    </aside>

    {{-- MAIN COLUMN --}}
    <main>
        <h1>{{ $store->name }} Coupons &amp; Promo Codes {{ now()->format('M Y') }}</h1>
        <p class="muted">Save now with {{ $coupons->count() }} active {{ $store->name }} coupons &amp; promo codes!</p>

        <div class="coupon-list">
            @forelse ($coupons as $coupon)
                @include('partials.coupon-card', ['coupon' => $coupon, 'best' => $loop->first])
            @empty
                <p>No active coupons right now. Check back soon!</p>
            @endforelse
        </div>

        @if ($store->about)
            <section class="box">
                <h2>About {{ $store->name }}</h2>
                {!! nl2br(e($store->about)) !!}
            </section>
        @endif

        @if ($related->isNotEmpty())
            <h2 class="section-title left">Popular Brands</h2>
            <div class="store-grid">
                @foreach ($related as $r)
                    @include('partials.store-tile', ['store' => $r])
                @endforeach
            </div>
        @endif
    </main>
</div>
@endsection
