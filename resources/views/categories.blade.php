@extends('layouts.app')
@use('App\Support\Site')

@section('title', 'All Categories')
@section('canonical', Site::url('categories'))

@section('content')
<main class="container">
    <h1 class="page-title">All Categories</h1>
    <div class="all-cats">
        @foreach ($categories as $main)
            <section class="all-cats__group">
                <h2><a href="{{ $main->url() }}">{{ $main->name }}</a></h2>
                @foreach ($main->children as $sub)
                    <a class="all-cats__sub" href="{{ $sub->url() }}">{{ $sub->name }} <small class="muted">({{ $sub->stores_count }})</small></a>
                @endforeach
            </section>
        @endforeach
    </div>
</main>
@endsection
