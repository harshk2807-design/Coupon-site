@use('App\Support\Site')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Hand-Tested Coupons & Promo Codes') | {{ config('app.name') }}</title>
    <meta name="description" content="@yield('description', 'Verified coupon codes, promo codes and deals for your favourite stores.')">
    @hasSection('canonical')<link rel="canonical" href="@yield('canonical')">@endif
    <link rel="stylesheet" href="{{ asset('css/site.css') }}?v=1">
</head>
<body>
<header class="header">
    <div class="container header__inner">
        {{-- Links use Site::url() so they work from store subdomains too --}}
        <a class="logo" href="{{ Site::url('/') }}">Worth<span>Deals</span></a>

        <form class="search" action="{{ Site::url('search') }}" method="get">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Search stores and brands…" aria-label="Search stores">
        </form>

        <nav class="nav">
            <a href="{{ Site::url('stores') }}">Stores</a>

            {{-- Categories mega menu (opens on hover, or on click on phones) --}}
            <div class="nav__cats" data-menu>
                <button type="button" class="nav__cats-btn" data-menu-toggle aria-expanded="false">Categories ▾</button>
                <div class="mega">
                    <a class="mega__all" href="{{ Site::url('categories') }}">All Categories ›</a>
                    <div class="mega__grid">
                        @foreach ($menuCategories as $main)
                            <div class="mega__col">
                                <a class="mega__main" href="{{ Site::categoryUrl($main['slug']) }}">{{ $main['name'] }}</a>
                                @foreach (array_slice($main['children'], 0, 5) as $sub)
                                    <a class="mega__sub" href="{{ Site::categoryUrl($sub['slug']) }}">{{ $sub['name'] }}</a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <a class="nav__admin" href="{{ Site::url('admin') }}">Admin</a>
        </nav>
    </div>
</header>

@yield('content')

<footer class="footer">
    <div class="container footer__inner">
        <span>© {{ date('Y') }} {{ config('app.name') }} · Hand-tested coupons &amp; deals</span>
        <span><a href="{{ Site::url('stores') }}">All stores</a> · <a href="{{ Site::url('categories') }}">All categories</a></span>
    </div>
</footer>

@include('partials.code-modal')
<script src="{{ asset('js/site.js') }}?v=1"></script>
</body>
</html>
